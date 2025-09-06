<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

//geustならlogin画面
Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'showLoginForm'])->name('login.form');
    Route::post('login',[AuthController::class,'login'])->name('login');
});

//承認したらユーザーにdashboadを見せる。
Route::middleware('auth')->group(function(){
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
