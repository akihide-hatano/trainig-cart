<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 💡 CartItemのルーティングをすべて定義
    Route::get('/cart', [CartItemController::class, 'index'])->name('cart.index');
    Route::get('/cart/{item}', [CartItemController::class, 'show'])->name('cart.show');
    Route::get('/cart/{item}/edit', [CartItemController::class, 'edit'])->name('cart.edit');
    Route::post('/cart/items', [CartItemController::class, 'store'])->name('cart.items.store');
    Route::patch('/cart/items/{item}', [CartItemController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{item}', [CartItemController::class, 'destroy'])->name('cart.items.destroy');

    // index / create / store / show / edit / update / destroy
    Route::get   ('/products',              [ProductController::class, 'index'])->name('products.index');
    Route::get   ('/products/create',       [ProductController::class, 'create'])->name('products.create');
    Route::post  ('/products',              [ProductController::class, 'store'])->name('products.store');
    Route::get   ('/products/{product}',    [ProductController::class, 'show'])->name('products.show');
    Route::get   ('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
    Route::patch ('/products/{product}',    [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',    [ProductController::class, 'destroy'])->name('products.destroy');

});


require __DIR__.'/auth.php';