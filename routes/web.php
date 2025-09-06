<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
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
    Route::get('/cart',[CartController::class,'index'])->name('cart.index');
    Route::post('/cart',[CartController::class,'store'])->name('cart.store');
    Route::patch('/cart/{item}',[CartController::class,'update'])->name('cart.update');
    Route::delete('/cart/{item}',[CartController::class,'destroy'])->name('cart.destroy');

    //カートのCRUD操作
    Route::get('/product',[ProductController::class,'index'])->name('product.index');
    Route::post('/product',[ProductController::class,'store'])->name('product.store');
    Route::patch('/product/{item}',[ProductController::class,'update'])->name('product.update');
    Route::delete('/product/{item}',[ProductController::class,'destroy'])->name('product.destroy');

});


require __DIR__.'/auth.php';
