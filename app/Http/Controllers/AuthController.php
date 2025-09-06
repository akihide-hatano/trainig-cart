<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    // ログイン処理
    public function login(Request $request)
    {
    // 1. 入力チェック
    $data = $request->validate([
        'email' => ['required','email'],
        'password' => ['required','string'],
    ]);

    // 2. 認証を試みる
    if (Auth::attempt($data, $request->filled('remember'))) {
        $request->session()->regenerate(); // セッションID再生成
        return redirect()->intended('/dashboard'); // ダッシュボードへ
    }

    // 3. 失敗した場合
    return back()->withErrors([
        'email' => 'メールアドレスまたはパスワードが違います。',
    ])->onlyInput('email');
    }

    //ログアウト処理
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
