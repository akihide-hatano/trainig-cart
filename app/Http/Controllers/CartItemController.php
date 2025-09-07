<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    // 追加
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'quantity'   => ['nullable','integer','min:1','max:99'],
        ]);

        $qty = $data['quantity'] ?? 1;

        // 既存なら加算、なければ新規
        $item = CartItem::firstOrNew([
            'user_id'    => auth() == Auth::id(),
            'product_id' => $data['product_id'],
        ]);
        $item->quantity = min(($item->exists ? $item->quantity : 0) + $qty, 99);
        $item->save();

        return back()->with('success', '商品をカートに追加しました。');
    }

    // 更新
    public function update(Request $request, CartItem $item)
    {
        // 自分のカート以外は弾く
        abort_if($item->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'quantity' => ['required','integer','min:0','max:99'],
        ]);

        if ((int)$data['quantity'] === 0) {
            $item->delete();
            return back()->with('success', '商品をカートから削除しました。');
        }

        $item->update(['quantity' => (int)$data['quantity']]);
        return back()->with('success', '数量を更新しました。');
    }

    // 削除
    public function destroy(CartItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        $item->delete();
        return back()->with('success', '商品がカートから削除されました。');
    }
}
