<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        // 今日の「支払い済み」「保留」注文
        $today = now()->startOfDay();
        $todayPaid    = Order::where('user_id', $user->id)->where('status','paid')->where('placed_at','>=',$today)->latest('placed_at')->get();
        $todayPending = Order::where('user_id', $user->id)->where('status','pending')->where('placed_at','>=',$today)->latest('placed_at')->get();
        // 最近の購入履歴（直近5件）
        $recentOrders = Order::withCount('items')
            ->where('user_id', $user->id)
            ->latest('placed_at')
            ->limit(5)
            ->get();

        // トップに流す商品（新着12件）
        $newProducts = Product::latest()->limit(12)->get();

    // dd([
    //     'user'         => $user,
    //     'todayPaid'    => $todayPaid,
    //     'todayPending' => $todayPending,
    //     'recentOrders' => $recentOrders,
    //     'newProducts'  => $newProducts,
    // ]);

        return view('dashboard', compact('user','todayPaid','todayPending','recentOrders','newProducts'));
    }

}
