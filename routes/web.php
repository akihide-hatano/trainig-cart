<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
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

    //カートのCRUD操作
    Route::get('/carts',[CartController::class,'index'])->name('cart.index');
    Route::get('/carts/create',[CartController::class,'create'])->name('cart.create');
    Route::post('/carts',[CartController::class,'store'])->name('cart.store');
    Route::get('/carts/{cart}',[CartController::class,'show'])->name('cart.show');
    Route::patch('/carts/{cart}',[CartController::class,'update'])->name('cart.update');
    Route::get('carts/{cart}/edit', [CartController::class, 'edit'])->name('cart.edit');
    Route::delete('/carts/{cart}',[CartController::class,'destroy'])->name('cart.destroy');

    // 💡 CartItemのCRUD操作ルート
    Route::post('/cart/items', [CartItemController::class, 'store'])->name('cart.items.store');
    Route::patch('/cart/items/{item}', [CartItemController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{item}', [CartItemController::class, 'destroy'])->name('cart.items.destroy');

    //ProductsのCRUD操作
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products',[ProductController::class,'index'])->name('product.index');
    Route::get('/products/{item}',[ProductController::class,'show'])->name('product.show');
    Route::post('/products/{item}',[ProductController::class,'store'])->name('product.store');
    Route::patch('/products/{item}',[ProductController::class,'update'])->name('product.update');
    Route::get('/products/{item}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{item}',[ProductController::class,'destroy'])->name('product.destroy');

});


require __DIR__.'/auth.php';
