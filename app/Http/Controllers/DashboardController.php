<?php

namespace App\Http\Controllers;

use App\Models\PointEarningConfiguration;
use App\Models\SavedArticle;
use App\Models\ForumQuestion;
use App\Models\ForumReply;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $savedArticlesCount = SavedArticle::where('user_id', $userId)->count();
        $forumPostsCount = ForumQuestion::where('user_id', $userId)->count();
        $ordersCount = Order::where('user_id', $userId)->count();

        $loyaltyPoint = $user->loyaltyPoint ?? $user->loyaltyPoint()->create([
            'current_points' => 0,
            'total_earned_points' => 0,
            'total_redeemed_points' => 0,
        ]);

        $recentActivities = Order::where('user_id', $userId)
            ->where('payment_status', 'completed')
            ->whereNotNull('payment_completed_at')
            ->latest('payment_completed_at')
            ->limit(8)
            ->get()
            ->map(function (Order $order) {
                $points = PointEarningConfiguration::calculatePoints($order->total);
                $desc = $points > 0
                    ? 'Rp '.number_format($order->total, 0, ',', '.').' · +'.$points.' poin loyalitas'
                    : 'Rp '.number_format($order->total, 0, ',', '.');

                return (object) [
                    'type' => 'order',
                    'title' => 'Pembelian selesai · Order #'.$order->id,
                    'description' => $desc,
                    'time' => $order->payment_completed_at->diffForHumans(),
                ];
            })
            ->all();

        return view('dashboard.index', compact(
            'savedArticlesCount',
            'forumPostsCount',
            'ordersCount',
            'loyaltyPoint',
            'recentActivities'
        ));
    }

    public function savedArticles()
    {
        $user = Auth::user();
        $savedArticles = $user->savedArticles()->with('article')->paginate(10);
        return view('dashboard.saved-articles', compact('savedArticles'));
    }

    public function myForumQuestions()
    {
        $userId = Auth::id();
        $questions = ForumQuestion::where('user_id', $userId)
            ->withCount('replies')
            ->latest()
            ->paginate(10);
        return view('dashboard.my-forum-questions', compact('questions'));
    }

    public function myForumReplies()
    {
        $userId = Auth::id();
        $replies = ForumReply::where('user_id', $userId)
            ->with('question')
            ->latest()
            ->paginate(10);
        return view('dashboard.my-forum-replies', compact('replies'));
    }

    public function myOrders()
    {
        $userId = Auth::id();
        $status = request('status');

        $query = Order::where('user_id', $userId)->with('items.product')->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('dashboard.orders.index', compact('orders', 'status'));
    }

    public function myOrderShow(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('dashboard.orders.show', compact('order'));
    }
}
