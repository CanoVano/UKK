<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ShopController;

Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\CartController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Checkout Route
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    
    // Order Routes
    Route::get('/my-orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{order_number}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
    Route::match(['get', 'post'], '/order/{order_number}/sync', [\App\Http\Controllers\OrderController::class, 'syncStatus'])->name('order.sync');
});

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalRevenue = \App\Models\Order::whereIn('status', ['paid', 'ready_for_pickup', 'completed'])->sum('total_price');
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::whereIn('status', ['pending', 'preparing'])->count();
        $totalProducts = \App\Models\Product::count();
        $recentOrders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // Chart Data: 7 Hari Terakhir
        $dates = [];
        $revenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = \App\Models\Order::whereIn('status', ['paid', 'ready_for_pickup', 'completed'])
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $dates[] = \Carbon\Carbon::parse($date)->translatedFormat('d M');
            $revenues[] = $revenue;
        }
        $chartData = [
            'labels' => $dates,
            'data' => $revenues,
        ];

        // Top 4 Produk Terlaris
        $topProducts = \App\Models\OrderItem::select('product_variant_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'), \Illuminate\Support\Facades\DB::raw('SUM(subtotal) as total_revenue'))
            ->has('productVariant')
            ->groupBy('product_variant_id')
            ->orderByDesc('total_sold')
            ->take(4)
            ->with('productVariant.product')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'pendingOrders', 'totalProducts', 'recentOrders', 'chartData', 'topProducts'
        ));
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
});
