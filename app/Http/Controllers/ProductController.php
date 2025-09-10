<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

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

    public function create(Product $product){
        return view('products.create');
    }

    public function store(Request $request){
        //Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|url',
        ]);
        //dataの登録
        try{
            Product::create($validated);
        }
        catch(\Exception $e){
            // 登録失敗時の処理（例：ログ出力やエラーメッセージのリダイレクト）
            return back()->withInput()->with('error', '商品の登録に失敗しました。もう一度お試しください。');
        }

        return redirect()->route('products.index')
                            ->with('message','商品が正常に登録されました');
    }

    public function edit(Product $product){
        return view('products.edit',compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        //validationの実施
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|url',
        ]);
        //databaseの登録
        try{
            $product->updated($validated);
        }
        catch(\Exception $e){
            // 登録失敗時の処理（例：ログ出力やエラーメッセージのリダイレクト）
            return back()->withInput()->with('error', '商品の登録に失敗しました。もう一度お試しください。');
        }
        return redirect()->route('products.show', $product)
                        ->with('message', '商品情報が正常に更新されました。');
    }

    public function destroy(Product $product)
    {
        try{
            //データベースから商品を削除
            $product->delete();
        }
        catch(\Exception $e){
            //削除失敗の処理
            return back()->with('error', '商品の削除に失敗しました。もう一度お試しください。');
        }
        //削除成功時にリダイレクト
        return redirect()->route('products.index')
                        ->with('message', '商品が正常に削除されました。');
    }
}
