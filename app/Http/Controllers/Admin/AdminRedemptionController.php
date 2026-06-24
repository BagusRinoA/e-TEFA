<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedemptionItem;
use App\Models\RedemptionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminRedemptionController extends Controller
{
    /**
     * Menampilkan daftar item yang dapat ditukar
     */
    public function index()
    {
        $items = RedemptionItem::latest()->paginate(12);
        return view('admin.redemption.items.index', compact('items'));
    }

    /**
     * Menampilkan form create
     */
    public function create()
    {
        return view('admin.redemption.items.create');
    }

    /**
     * Menyimpan item baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_cost' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'max_redemption_per_user' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('redemption-items', 'public');
        }

        RedemptionItem::create($data);

        return redirect()->route('admin.redemption.items.index')->with('success', 'Item penukar berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit
     */
    public function edit(RedemptionItem $item)
    {
        return view('admin.redemption.items.edit', compact('item'));
    }

    /**
     * Update item
     */
    public function update(Request $request, RedemptionItem $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_cost' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'max_redemption_per_user' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($item->image_url && Storage::disk('public')->exists($item->image_url)) {
                Storage::disk('public')->delete($item->image_url);
            }
            $data['image_url'] = $request->file('image')->store('redemption-items', 'public');
        }

        $item->update($data);

        return redirect()->route('admin.redemption.items.index')->with('success', 'Item penukar berhasil diperbarui.');
    }

    /**
     * Hapus item
     */
    public function destroy(RedemptionItem $item)
    {
        // Hapus gambar jika ada
        if ($item->image_url && Storage::disk('public')->exists($item->image_url)) {
            Storage::disk('public')->delete($item->image_url);
        }

        $item->delete();
        return redirect()->route('admin.redemption.items.index')->with('success', 'Item penukar berhasil dihapus.');
    }

    /**
     * Menampilkan daftar redemption transactions
     */
    public function transactions()
    {
        $transactions = RedemptionTransaction::with(['user', 'item'])
            ->latest()
            ->paginate(15);

        return view('admin.redemption.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail transaction
     */
    public function showTransaction(RedemptionTransaction $transaction)
    {
        $transaction->load(['user', 'item']);
        return view('admin.redemption.transactions.show', compact('transaction'));
    }

    /**
     * Update transaction status
     */
    public function updateStatus(Request $request, RedemptionTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $newStatus = $request->input('status');
        $notes = $request->input('notes', $transaction->notes);

        if ($transaction->status === $newStatus) {
            return redirect()->back()->with('info', 'Status sudah ' . $newStatus);
        }

        // Jika dibatalkan, kembalikan poin
        if ($newStatus === 'cancelled' && in_array($transaction->status, ['pending', 'processing', 'shipped'])) {
            $loyaltyPoint = $transaction->user->loyaltyPoint;
            if ($loyaltyPoint) {
                $loyaltyPoint->addPoints($transaction->points_spent);
            }
            $transaction->update(['status' => 'cancelled', 'notes' => $notes]);
            return redirect()->route('admin.redemption.transactions')->with('success', 'Transaksi dibatalkan dan poin dikembalikan.');
        }

        // Jika selesai, kurangi stok (jika belum dikurangi sebelumnya)
        // Kita asumsikan stok dikurangi saat dikirim (shipped) atau completed.
        // Mari kita kurangi stok saat status berubah dari pending ke processing.
        if ($newStatus === 'processing' && $transaction->status === 'pending') {
            if (!$transaction->item->decrementStock($transaction->quantity)) {
                return redirect()->back()->with('error', 'Stok item tidak cukup.');
            }
        }

        if ($newStatus === 'completed') {
            $transaction->complete();
        } else {
            $transaction->update(['status' => $newStatus, 'notes' => $notes]);
        }

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui menjadi ' . ucfirst($newStatus) . '.');
    }

    /**
     * Toggle status item
     */
    public function toggleStatus(RedemptionItem $item)
    {
        $item->update(['is_active' => !$item->is_active]);
        return redirect()->route('admin.redemption.items.index')->with('success', 'Status item berhasil diubah.');
    }
}
