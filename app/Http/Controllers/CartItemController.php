<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

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
public function store(Request $request)
{

    $data = $request->validate([
        'product_id' => ['required','integer','exists:products,id'],
        'quantity'   => ['nullable','integer','min:1','max:99'],
        'go_cart'    => ['nullable','boolean'],
    ]);
    $qty = $data['quantity'] ?? 1;

    // 追加する商品の現在価格を取得
    $product = Product::findOrFail($data['product_id']);

    // ユーザー×商品で既存を探す。なければ新規作成（保存までしてくれる）
    $item = CartItem::firstOrCreate(
        [
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
        ],
        [
            'unit_price' => (int) $product->price,
            'quantity'   => 0,  // 新規作成時の初期値
        ]
    );

    // 数量を加算して最大 99 に制限
    $newQuantity = $item->quantity + $qty;
    if ($newQuantity > 99) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'quantity' => 'カートに追加できる商品の合計数量は99個までです。',
        ]);
    }

    $item->update(['quantity' => $newQuantity]);

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

    // checkout機能を確認する
    public function checkout()
    {
        $userId = Auth::id();

        $order = DB::transaction(function() use($userId){

            $items = CartItem::with('product')
                ->where('user_id',$userId)
                ->lockForUpdate()
                ->get();

            if($items->isEmpty()){
                abort(400,'カートが空です');
            }

            //合計計算
            $total = 0;
            foreach($items as $ci){
                $unit = $ci->unit_price ?? (int)$ci->product->price;
                $total += $unit * $ci->quantity;
            }

            // 注文作成
            $order = Order::create([
                'user_id'      => $userId,
                'total_amount' => $total,
                'status'       => 'paid',   // 外部決済なら pending にする
                'placed_at'    => now(),
            ]);

            //明細作成
            foreach($items as $ci){
                $order->items()->create([
                    'product_id' => $ci->product_id,
                    'unit_price' =>$ci->unit_price ?? (int)$ci->product->price,
                    'quantity' =>$ci->quantity,
                    'subtotal' => ($ci->unit_price ?? (int)$ci->product->price) * $ci->quantity,
                ]);
            }
            //カートを空にする
            CartItem::where('user_id',$userId)->delete();

            //returnで定義
            return $order;
        });

        //トランザクションを抜けても$orderを使えるように
        return redirect()->route('orders.show',$order)
                        ->with('success','ご注文が覚醒しました。');
    }
}
?>