<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
          return view('products.index', compact('products'));
    }

    public function show(Product $product){
        return view('products.show', compact('product'));
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
}
