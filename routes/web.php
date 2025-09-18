<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 💡 CartItemのルーティングをすべて定義
    Route::post('/cart/items', [CartItemController::class, 'store'])->name('cart.items.store');
    Route::patch('/cart/items/{item}', [CartItemController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{item}', [CartItemController::class, 'destroy'])->name('cart.items.destroy');
    // **汎用的なパスを後に記述**
    Route::get('/cart', [CartItemController::class, 'index'])->name('cart.index');
    Route::get('/cart/{item}', [CartItemController::class, 'show'])->name('cart.show');
    Route::get('/cart/{item}/edit', [CartItemController::class, 'edit'])->name('cart.edit');

    // index / create / store / show / edit / update / destroy
    Route::get   ('/products',              [ProductController::class, 'index'])->name('products.index');
    Route::get   ('/products/create',       [ProductController::class, 'create'])->name('products.create');
    Route::post  ('/products',              [ProductController::class, 'store'])->name('products.store');
    Route::get   ('/products/{product}',    [ProductController::class, 'show'])->name('products.show');
    Route::get   ('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
    Route::patch ('/products/{product}',    [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',    [ProductController::class, 'destroy'])->name('products.destroy');


    // Orders のルーティング
    Route::get   ('/orders',              [OrderController::class, 'index'])->name('orders.index');
    Route::get   ('/orders/create',       [OrderController::class, 'create'])->name('orders.create');
    Route::post  ('/orders',              [OrderController::class, 'store'])->name('orders.store');
    Route::get   ('/orders/{order}',      [OrderController::class, 'show'])->name('orders.show');
    Route::get   ('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::patch ('/orders/{order}',      [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}',      [OrderController::class, 'destroy'])->name('orders.destroy');

    // OrderItems のルーティング
    Route::get   ('/orders/{order}/items',               [OrderItemController::class, 'index'])->name('orderitems.index');
    Route::get   ('/orders/{order}/items/create',        [OrderItemController::class, 'create'])->name('orderitems.create');
    Route::post  ('/orders/{order}/items',               [OrderItemController::class, 'store'])->name('orderitems.store');
    Route::get   ('/orders/{order}/items/{orderitem}',   [OrderItemController::class, 'show'])->name('orderitems.show');
    Route::get   ('/orders/{order}/items/{orderitem}/edit', [OrderItemController::class, 'edit'])->name('orderitems.edit');
    Route::patch ('/orders/{order}/items/{orderitem}',   [OrderItemController::class, 'update'])->name('orderitems.update');
    Route::delete('/orders/{order}/items/{orderitem}',   [OrderItemController::class, 'destroy'])->name('orderitems.destroy');
});


require __DIR__.'/auth.php';