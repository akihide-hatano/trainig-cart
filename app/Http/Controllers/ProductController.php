<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use function Ramsey\Uuid\v1;

class ProductController extends Controller
{

public function index(Request $request)
{
    $q    = $request->input('q');             // キーワード
    $sort = $request->input('sort', 'new');   // 並び順

    $query = Product::query();

    // 検索条件
    if (!empty($q)) {
        $query->where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%");
    }
    // ソート条件
    if ($sort === 'price_asc') {
        $query->orderBy('price', 'asc');
    } elseif ($sort === 'price_desc') {
        $query->orderBy('price', 'desc');
    } else { // new
        $query->latest();
    }
    $products = $query->paginate(12)->withQueryString();

    return view('products.index', compact('products', 'q', 'sort'));
}


    public function show(Product $product){
        return view('products.show', compact('product'));
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){
        Log::info('商品を登録しました');
        //Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // ←ファイル前提
        ]);

        Log::debug('バリデーション成功', $validated);

        //画像ファイルの保存
        $imagePath = null;
        if( $request->hasFile('image')){
            // storage/app/public/products に画像を保存
            // storeメソッドがユニークなファイル名を自動生成
            $imagePath = $request->file('image')->store('products', 'public');
            Log::info('画像が保存されました。パス:' . $imagePath);
        }
        // dd('store hit', $request->all(), $request->file('image'));
        //dataの登録
        try{
            Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'image' => $imagePath,
            ]);
            Log::info('商品が正常に登録されました。');
        }
        catch(\Exception $e){
            // 登録失敗時の処理（例：ログ出力やエラーメッセージのリダイレクト）
            return back()->withInput()->with('error', '商品の登録に失敗しました。もう一度お試しください。');
        }
        return redirect()->route('products.index')
        ->with('success','商品が正常に登録されました');
    }

    public function edit(Product $product){
        return view('products.edit',compact('product'));
    }

public function update(Request $request, Product $product)
{


    // validationの実施
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // nullを許容
    ]);

    // ①画像ファイルの保存
    // $imagePath = null;
    if ($request->hasFile('image')) {
        // storage/app/public/products に画像を保存
        $imagePath = $request->file('image')->store('products', 'public');

        // ②古い画像ファイルを削除するロジックを追加
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
    }
    //databaseの登録
    try{
        // 画像パスを更新データに追加
        if (isset($imagePath)) {
            $product->update(array_merge($validated, ['image' => $imagePath]));
        } else {
            // 画像がアップロードされていない場合は、他のフィールドのみを更新
            $product->update($validated);
        }
    } catch(\Exception $e){
        // 登録失敗時の処理（例：ログ出力やエラーメッセージのリダイレクト）
        return back()->withInput()->with('error', '商品の登録に失敗しました。もう一度お試しください。');
    }
    return redirect()->route('products.show', $product)
                    ->with('success', '商品情報が正常に更新されました。');
}

    public function destroy(Product $product)
    {
        Log::info("商品ID:{$product->id}の削除リクエストを受信しました。");

        try{
            //商品が画像に紐づいている場合、画像を削除する
            if($product->image){
                Storage::disk('public')->delete($product->image);
                Log::info("商品ID:{$product->id}に関連付けられた画像が削除されました。");
            }
                //データベースから商品を削除
                $product->delete();
                Log::info("商品ID:{$product->id}が正常に削除されました。", [
                    'file' => __FILE__,      // 実行中のファイル名
                    'line' => __LINE__,      // 実行中の行番号
                    'method' => __METHOD__,  // 実行中のメソッド名
            ]);
        }
        catch(\Exception $e){
            //削除失敗の処理
            Log::error([
                "商品ID:{$product->id}の削除中にエラーが発生しました。",
                'message' => $e->getMessage(),
                'trance' => $e->getTraceAsString()
            ]);
            return back()->with('error', '商品の削除に失敗しました。もう一度お試しください。');
        }
        //削除成功時にリダイレクト
        return redirect()->route('products.index')
                        ->with('success', '商品が正常に削除されました。');
    }
}
