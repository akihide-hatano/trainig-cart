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
        //今日の支払いと保留について
        $today = now()->startOfDay();

        $query = Order::where('user_id',$user->id)
                ->where('placed_at','>=',$today)
                ->latest('placed_at');

        $todayPaid = (clone $query)->where('status','paid')->get();
        $todayPending = (clone $query)->where('status','pending')->get();

        // トップに流す商品（新着12件）
        $newProducts = Product::latest()->limit(12)->get();

        //userの購入したitemをwithでとる
        $recentOrders = Order::withCount('items')
                        ->where('user_id',$user->id)
                        ->latest()
                        ->limit(5)
                        ->get();
        return view('dashboard',compact('user','newProducts','todayPaid','todayPending','recentOrders'));

    }

}
