<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Validation\Rule;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('items')
                ->withCount('items')
                ->where('user_id',Auth::id())
                ->orderByDesc('placed_at')
                ->paginate(10);

        return view('order.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validationを設定
        $data = $request->validate([
            'total_amount' => ['required','integer','min:0'],
            'status' => ['required',Rule::in(['pending','paid','cancelled'])],
            'placed_at' => ['nullable','date'],
        ]);

        //createでorderを作る
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $data['total_amount'],
            'status'       => $data['status'],
            'placed_at'    => $data['placed_at'] ?? now(),
        ]);

        return redirect()->route('orders.show',$order)
                        ->with('success','注文を作成しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product');

        return view('order.show',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        abort_if($order->user_id !== Auth::id(),403);
        return view('order_edit',compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(),403);

        $data = $request->validate([
        // 通常ユーザーは status の変更だけ（例：キャンセル）に絞るのが安全
            'status'       => ['required', Rule::in(['pending','paid','cancelled'])],
        ]);

        $order->updata($data);
        return redirect()->route('orders.show',$order)->with('success','注文が更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        abort_if($order->user_id !== Auth::id(),403);

         // 実務は cancel へ状態遷移の方が安全
        $order->delete();
        return redirect()->route('orders.index')->with('success', '注文を削除しました。');
    }
}
