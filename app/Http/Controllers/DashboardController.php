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
                ->lateset('placed_at');

        $todayPaid = (clone $query)->where('status','paid')->get();
        $todayPending = (clone $query)->where('status','pending')->get();

        // dd() と違って、両方の結果をまとめて出力
        dump($todayPaid);
        dump($todayPending);
    }

}
