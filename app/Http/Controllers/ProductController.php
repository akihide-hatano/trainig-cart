<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        dd($products);
          return view('products.index', compact('products'));
    }

    public function show(Product $product){
        dd($product); // デバッグ用
        return view('products.show', compact('product'));
    }
}
