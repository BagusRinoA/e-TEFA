<?php

namespace App\Http\Controllers;

use App\Models\RedemptionItem;
use App\Models\RedemptionTransaction;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class RedemptionController extends Controller
{
    protected $loyaltyService;

    /**
     * Constructor untuk memastikan user sudah login dan bukan admin
     */
    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->is_admin) {
                abort(403, 'Admin cannot access user loyalty features.');
            }
            return $next($request);
        });

        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Menampilkan loyalty dashboard user
     */
    public function dashboard()
    {
        $user = Auth::user();
        $loyaltyPoint = $user->loyaltyPoint ?? $user->loyaltyPoint()->create([
            'current_points' => 0,
            'total_earned_points' => 0,
            'total_redeemed_points' => 0,
        ]);

        $recentTransactions = $user->redemptionTransactions()
            ->with('item')
            ->latest()
            ->limit(5)
            ->get();

        $availableItems = RedemptionItem::where('is_active', true)
            ->where('stock', '>', 0)
            ->limit(6)
            ->get();

        return view('loyalty.dashboard', compact('loyaltyPoint', 'recentTransactions', 'availableItems'));
    }

    /**
     * Menampilkan daftar item yang dapat ditukar
     */
    public function shop(Request $request)
    {
        $query = RedemptionItem::where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('min_points')) {
            $query->where('points_cost', '>=', $request->min_points);
        }

        if ($request->filled('max_points')) {
            $query->where('points_cost', '<=', $request->max_points);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->availability === 'sold_out') {
                $query->where('stock', '=', 0);
            }
        }

        $items = $query->paginate(12);
        $user = Auth::user();
        $userPoints = $user->loyaltyPoint?->current_points ?? 0;

        return view('loyalty.shop', compact('items', 'userPoints'));
    }

    /**
     * Menampilkan detail item
     */
    public function showItem(RedemptionItem $item)
    {
        $user = Auth::user();
        $loyaltyPoint = $user->loyaltyPoint ?? $user->loyaltyPoint()->create([
            'current_points' => 0,
            'total_earned_points' => 0,
            'total_redeemed_points' => 0,
        ]);

        $userRedemptionCount = $item->getUserRedemptionCount($user->id);
        $canRedeem = $loyaltyPoint->current_points >= $item->points_cost
            && $item->stock > 0
            && $userRedemptionCount < $item->max_redemption_per_user;

        return view('loyalty.item-detail', compact('item', 'loyaltyPoint', 'canRedeem', 'userRedemptionCount'));
    }

    /**
     * Proses penukaran poin dengan item
     */
    public function redeem(Request $request, RedemptionItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $quantity = $request->integer('quantity');

        $shippingData = [
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_postal_code' => $request->shipping_postal_code,
        ];

        try {
            $transaction = $this->loyaltyService->createRedemptionRequest(
                $user,
                $item,
                $quantity,
                $shippingData
            );

            return redirect()->route('loyalty.transaction-detail', $transaction->id)
                ->with('success', 'Permintaan penukaran berhasil dibuat. Menunggu proses pengiriman.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan detail transaksi
     */
    public function transactionDetail(RedemptionTransaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $transaction->load(['item', 'user']);
        return view('loyalty.transaction-detail', compact('transaction'));
    }

    /**
     * Menampilkan riwayat penukaran user
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = $user->redemptionTransactions()->with('item');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15);
        $stats = [
            'pending' => $user->redemptionTransactions()->where('status', 'pending')->count(),
            'completed' => $user->redemptionTransactions()->where('status', 'completed')->count(),
            'cancelled' => $user->redemptionTransactions()->where('status', 'cancelled')->count(),
        ];

        return view('loyalty.history', compact('transactions', 'stats'));
    }

    /**
     * Batalkan transaksi pending
     */
    public function cancelTransaction(RedemptionTransaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$transaction->isPending()) {
            return back()->with('error', 'Hanya transaksi pending yang bisa dibatalkan.');
        }

        try {
            $this->loyaltyService->cancelRedemption($transaction, 'Dibatalkan oleh user');

            return back()->with('success', 'Transaksi berhasil dibatalkan dan poin dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * API endpoint - Dapatkan poin user (untuk AJAX)
     */
    public function getPoints()
    {
        $user = Auth::user();
        $loyaltyPoint = $user->loyaltyPoint ?? $user->loyaltyPoint()->create([
            'current_points' => 0,
            'total_earned_points' => 0,
            'total_redeemed_points' => 0,
        ]);

        return response()->json([
            'current_points' => $loyaltyPoint->current_points,
            'total_earned' => $loyaltyPoint->total_earned_points,
            'total_redeemed' => $loyaltyPoint->total_redeemed_points,
        ]);
    }
}
