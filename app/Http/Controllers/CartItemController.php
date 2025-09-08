<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
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

        $total = 0;
        foreach($items as $i){
            $total += (int)$i->product->price * (int)$i->quantity;
        }

        return view('cart.index', compact('items', 'total'));
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
            'prodict_id'=>$data['product_id'],
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
}
?>