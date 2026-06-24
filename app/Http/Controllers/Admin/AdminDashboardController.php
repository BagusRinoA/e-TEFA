<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\RedemptionTransaction;
use App\Models\PointEarningConfiguration;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count('*');
        $totalUsers = User::count('*');
        $totalProducts = Product::count('*');
        $totalRevenue = Order::sum('total');

        $activeUsers = User::count('*');
        $recentOrders = Order::with('user')->latest()->take(4)->get();
        $lowStockProducts = Product::query()->where('stock', '<', 10)->orderBy('stock')->take(4)->get();
        $lowStockCount = $lowStockProducts->count();

        $startOfCurrentMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // Revenue Growth
        $currentMonthRevenue = Order::query()->where([['created_at', '>=', $startOfCurrentMonth]])->sum('total');
        $lastMonthRevenue = Order::query()->where([['created_at', '>=', $startOfLastMonth], ['created_at', '<=', $endOfLastMonth]])->sum('total');

        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } elseif ($currentMonthRevenue > 0) {
            $revenueGrowth = 100;
        }

        // Orders Growth
        $currentMonthOrders = Order::query()->where([['created_at', '>=', $startOfCurrentMonth]])->count();
        $lastMonthOrders = Order::query()->where([['created_at', '>=', $startOfLastMonth], ['created_at', '<=', $endOfLastMonth]])->count();
        $ordersGrowth = $currentMonthOrders - $lastMonthOrders;

        // Users Growth
        $newUsersThisWeek = User::query()->where([['created_at', '>=', now()->subWeek()]])->count();

        // Loyalty Stats
        $loyaltyStats = LoyaltyService::getAdminStats();
        $pendingRedemptions = RedemptionTransaction::where('status', 'pending')->count();
        $pointConfigurations = PointEarningConfiguration::where('is_active', true)->count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'totalRevenue',
            'activeUsers',
            'recentOrders',
            'lowStockProducts',
            'lowStockCount',
            'revenueGrowth',
            'ordersGrowth',
            'newUsersThisWeek',
            'loyaltyStats',
            'pendingRedemptions',
            'pointConfigurations'
        ));
    }

    public function sales(Request $request)
    {
        $query = Order::query()->where('status', 'Completed');

        // Filters: date range (from,to), or month + year, or year
        if ($request->filled('from') && $request->filled('to')) {
            try {
                $from = Carbon::parse($request->input('from'))->startOfDay();
                $to = Carbon::parse($request->input('to'))->endOfDay();
                $query->whereBetween('created_at', [$from, $to]);
            } catch (\Exception $e) {
                // ignore invalid dates
            }
        } elseif ($request->filled('month')) {
            $month = (int) $request->input('month');
            $year = $request->input('year') ? (int) $request->input('year') : now()->year;
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        } elseif ($request->filled('year')) {
            $year = (int) $request->input('year');
            $query->whereYear('created_at', $year);
        }

        $totalRevenue = (clone $query)->sum('total');
        $totalOrders = (clone $query)->count();

        // Top selling products within the same filters
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', 'complete')
            ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
                try {
                    $from = Carbon::parse($request->input('from'))->startOfDay();
                    $to = Carbon::parse($request->input('to'))->endOfDay();
                    return $q->whereBetween('orders.created_at', [$from, $to]);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->get();

        $orders = $query->with('user')->latest()->paginate(20)->appends($request->query());

        return view('admin.report.sales', compact('orders', 'totalRevenue', 'totalOrders', 'topProducts'));
    }

    public function salesPrint(Request $request)
    {
        $query = Order::query()->where('status', 'Completed');

        if ($request->filled('from') && $request->filled('to')) {
            try {
                $from = Carbon::parse($request->input('from'))->startOfDay();
                $to = Carbon::parse($request->input('to'))->endOfDay();
                $query->whereBetween('created_at', [$from, $to]);
            } catch (\Exception $e) {
            }
        } elseif ($request->filled('month')) {
            $month = (int) $request->input('month');
            $year = $request->input('year') ? (int) $request->input('year') : now()->year;
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        } elseif ($request->filled('year')) {
            $year = (int) $request->input('year');
            $query->whereYear('created_at', $year);
        }

        $totalRevenue = (clone $query)->sum('total');
        $totalOrders = (clone $query)->count();

        // Top selling products for print view
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', 'complete')
            ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
                try {
                    $from = Carbon::parse($request->input('from'))->startOfDay();
                    $to = Carbon::parse($request->input('to'))->endOfDay();
                    return $q->whereBetween('orders.created_at', [$from, $to]);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->get();

        $orders = $query->with('user')->latest()->get();

        return view('admin.report.sales-print', compact('orders', 'totalRevenue', 'totalOrders', 'topProducts'));
    }

    public function orders()
    {
        $orders = Order::query()->with('user', 'items')->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function users()
    {
        $users = User::query()->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }
}
