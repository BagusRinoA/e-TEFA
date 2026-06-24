<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedemptionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminForumController;
use App\Http\Controllers\Admin\AdminLoyaltyController;
use App\Http\Controllers\Admin\AdminRedemptionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pages
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Forum
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create')->middleware('auth');
Route::post('/forum', [ForumController::class, 'store'])->name('forum.store')->middleware('auth');
Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/forum/{id}/edit', [ForumController::class, 'edit'])->name('forum.edit')->middleware('auth');
Route::put('/forum/{id}', [ForumController::class, 'update'])->name('forum.update')->middleware('auth');
Route::delete('/forum/{id}', [ForumController::class, 'destroy'])->name('forum.destroy')->middleware('auth');
Route::post('/forum/{id}/reply', [ForumController::class, 'reply'])->name('forum.reply')->middleware('auth');
Route::delete('/forum/reply/{replyId}', [ForumController::class, 'deleteReply'])->name('forum.reply.delete')->middleware('auth');
Route::post('/forum/{id}/upvote', [ForumController::class, 'upvoteQuestion'])->name('forum.upvote')->middleware('auth');
Route::post('/forum/reply/{replyId}/upvote', [ForumController::class, 'upvoteReply'])->name('forum.reply.upvote')->middleware('auth');

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/{id}/save-toggle', [ArticleController::class, 'toggleSave'])->name('articles.save-toggle')->middleware('auth');

// Cart & checkout
Route::get('/cart', [CheckoutController::class, 'cart'])->name('cart.index')->middleware('auth');
Route::post('/cart/prepare-checkout', [CheckoutController::class, 'prepareCheckout'])->name('cart.prepare-checkout')->middleware('auth');

Route::get('/checkout', [CheckoutController::class, 'checkoutPage'])->name('checkout')->middleware('auth');
Route::post('/checkout/add', [CheckoutController::class, 'addToCart'])->name('checkout.add')->middleware('auth');
Route::post('/checkout/buy-now', [CheckoutController::class, 'buyNow'])->name('checkout.buy-now')->middleware('auth');
Route::post('/checkout/remove', [CheckoutController::class, 'removeFromCart'])->name('checkout.remove')->middleware('auth');
Route::post('/checkout/update-quantity', [CheckoutController::class, 'updateQuantity'])->name('checkout.update-quantity')->middleware('auth');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');

// Payment (Midtrans)
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/redirect/{order}', [PaymentController::class, 'redirectToMidtrans'])->name('payment.redirect');
    Route::get('/payment/finish/{order}', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/error/{order}', [PaymentController::class, 'error'])->name('payment.error');
    Route::get('/payment/pending/{order}', [PaymentController::class, 'pending'])->name('payment.pending');
});
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// Loyalty & Redemption (Protected - User Only)
Route::middleware(['auth', 'web'])->group(function () {
    Route::middleware('nonAdminOnly')->group(function () {
        // Loyalty Dashboard
        Route::get('/loyalty/dashboard', [RedemptionController::class, 'dashboard'])->name('loyalty.dashboard');

        // Redemption Shop
        Route::get('/loyalty/shop', [RedemptionController::class, 'shop'])->name('loyalty.shop');
        Route::get('/loyalty/item/{item}', [RedemptionController::class, 'showItem'])->name('loyalty.item-detail');
        Route::post('/loyalty/redeem/{item}', [RedemptionController::class, 'redeem'])->name('loyalty.redeem');

        // Redemption Transactions
        Route::get('/loyalty/transaction/{transaction}', [RedemptionController::class, 'transactionDetail'])->name('loyalty.transaction-detail');
        Route::get('/loyalty/history', [RedemptionController::class, 'history'])->name('loyalty.history');
        Route::post('/loyalty/transaction/{transaction}/cancel', [RedemptionController::class, 'cancelTransaction'])->name('loyalty.transaction.cancel');

        // API
        Route::get('/api/loyalty/points', [RedemptionController::class, 'getPoints'])->name('api.loyalty.points');
    });
});

// User Dashboard (Protected - No Admin Users)
Route::middleware(['auth', 'web'])->group(function () {
    Route::middleware('nonAdminOnly')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/saved-articles', [DashboardController::class, 'savedArticles'])->name('dashboard.saved-articles');
        Route::get('/dashboard/my-forum-questions', [DashboardController::class, 'myForumQuestions'])->name('dashboard.my-forum-questions');
        Route::get('/dashboard/my-forum-replies', [DashboardController::class, 'myForumReplies'])->name('dashboard.my-forum-replies');

        // My Orders
        Route::get('/dashboard/orders', [DashboardController::class, 'myOrders'])->name('dashboard.orders');
        Route::get('/dashboard/orders/{order}', [DashboardController::class, 'myOrderShow'])->name('dashboard.orders.show');
    });

    // Profile (Accessible by all authenticated users including admins)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/profile/privacy', [ProfileController::class, 'updatePrivacy'])->name('profile.privacy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Report routes
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/sales', [AdminDashboardController::class, 'sales'])->name('sales');
        Route::get('/sales-print', [AdminDashboardController::class, 'salesPrint'])->name('sales.print');
    });

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');

    Route::get('/forum', [AdminForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{forumQuestion}', [AdminForumController::class, 'show'])->name('forum.show');
    Route::delete('/forum/{type}/{id}', [AdminForumController::class, 'destroy'])->name('forum.destroy');

    Route::resource('articles', AdminArticleController::class)->only(['index', 'create', 'store', 'destroy']);

    // Loyalty & Redemption Management
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::resource('configurations', AdminLoyaltyController::class)->except(['show']);
        Route::patch('configurations/{configuration}/toggle-status', [AdminLoyaltyController::class, 'toggleStatus'])->name('configurations.toggle-status');
    });

    Route::prefix('redemption')->name('redemption.')->group(function () {
        // Redemption Items Management
        Route::resource('items', AdminRedemptionController::class)->except(['show']);
        Route::patch('items/{item}/toggle-status', [AdminRedemptionController::class, 'toggleStatus'])->name('items.toggle-status');

        // Redemption Transactions Management
        Route::get('transactions', [AdminRedemptionController::class, 'transactions'])->name('transactions');
        Route::get('transactions/{transaction}', [AdminRedemptionController::class, 'showTransaction'])->name('transactions.show');
        Route::patch('transactions/{transaction}/update-status', [AdminRedemptionController::class, 'updateStatus'])->name('transactions.update-status');
    });
});
