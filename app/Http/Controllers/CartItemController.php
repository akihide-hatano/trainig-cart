<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{

    /**
     * カート内の商品一覧を表示する
     */
    public function index(){
        $items = CartItem::with('product')
                ->where('user_id',Auth::id())
                ->get();

        // productsの3件最新のものをとる
        $products = Product::latest()->take(3)->get();


        $total = 0;
        foreach($items as $i){
            $total += (int)$i->product->price * (int)$i->quantity;
        }

        return view('cart.index', compact('items', 'total','products'));
    }

    /**
     * カート内の特定のアイテムを表示する (練習用)
     *
     * @param  \App\Models\CartItem  $item
     */
    public function show(CartItem $item){

        abort_if($item->user_id == Auth::id(),403);
        return view('cart.show',compact('item'));
    }

    /**
     * カートに新しい商品を追加する
     */
    public function store(Request $request){
        $data = $request->validate([
            'product_id'=>['required','integer','exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            ]);
        $qty = $data['quantity'] ?? 1;

        $item = CartItem::firstOrNew([
            'user_id'=>Auth::id(),
            'product_id'=>$data['product_id'],
        ]);

        //数値の計算を変更しました
        if($item->exists){
            $newQuantity = $item->quantity + $qty;
        }else{
            $newQuantity = $qty;
        }

        //数値が99を超えないように制限
        if($newQuantity > 99){
            throw ValidationException::withMessages([
            'quantity' => 'カートに追加できる商品の合計数量は99個までです。',
            ]);
        }
        $item->quantity = $newQuantity;
        $item->save();
        return back()->with('success', '商品をカートに追加しました。');
    }

    /**
     * カート内のアイテム編集フォームを表示する (練習用)
     *
     * @param  \App\Models\CartItem  $item
     */
    public function edit(CartItem $item)
    {
        // 自分のカートアイテム以外は拒否
        abort_if($item->user_id !== Auth::id(), 403);
        return view('cart.edit', compact('item'));
    }

    /**
     * カート内の商品の数量を更新する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $item
     */
    public function update(Request $request,CartItem $item){
        // 自分のカートアイテム以外は拒否
        abort_if($item->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        //quantityが0の場合消す
        if((int)$data['quantity'] === 0){
            $item->delete();
            return back()->with('success', '商品をカートから削除しました。');
        }

        //quantityっていう部分で99以上
        if ((int)$data['quantity'] > 99) {
            // バリデーションエラーを投げる
            throw ValidationException::withMessages([
                'quantity' => '数量は99個までです。'
            ]);
        }

        $item->update(['quantity' => (int)$data['quantity']]);
        return back()->with('success', '数量を更新しました。');
    }

    /**
     * カートから商品を削除する
     *
     * @param  \App\Models\CartItem  $item
     */
    public function destroy(CartItem $item)
    {
        // 自分のカートアイテム以外は拒否
        abort_if($item->user_id !== Auth::id(), 403);

        $item->delete();
        return back()->with('success', '商品がカートから削除されました。');
    }
}
?>