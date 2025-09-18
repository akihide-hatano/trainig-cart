<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * カート内の商品一覧を表示する
     */
    public function index()
    {
        return view('cart.index');
    }

    /**
     * カートに新しい商品を追加する
     */
    public function store(Request $request)
    {
        // TODO: ここにカートへの追加ロジックを記述
        // リクエストから商品IDと数量を取得し、新しいCartItemを作成します
        return redirect()->route('cart.index')->with('success', '商品がカートに追加されました。');
    }

    /**
     * カート内の商品の数量を更新する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $item
     */
    public function update(Request $request, CartItem $item)
    {
        // TODO: ここに更新ロジックを記述
        // $itemの数量を更新します
        return redirect()->route('cart.index')->with('success', '数量が更新されました。');
    }

    /**
     * カートから商品を削除する
     *
     * @param  \App\Models\CartItem  $item
     */
    public function destroy(CartItem $item)
    {
        // TODO: ここに削除ロジックを記述
        // $itemを削除します
        return redirect()->route('cart.index')->with('success', '商品がカートから削除されました。');
    }
}
