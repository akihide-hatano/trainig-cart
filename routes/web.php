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

    //ProductsのCRUD操作
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products',[ProductController::class,'index'])->name('products.index');
    Route::get('/products/{item}',[ProductController::class,'show'])->name('products.show');
    Route::post('/products/{item}',[ProductController::class,'store'])->name('products.store');
    Route::patch('/products/{item}',[ProductController::class,'update'])->name('products.update');
    Route::get('/products/{item}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{item}',[ProductController::class,'destroy'])->name('product.destroy');

});


require __DIR__.'/auth.php';