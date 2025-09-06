<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CartItem::create([
            'cart_id'    => 1,     // CartSeederで作ったカート
            'product_id' => 1,     // ProductSeederで入れた商品
            'quantity'        => 2,
            'unit_price' => 180,   // ProductSeederの価格に合わせる
        ]);
    }
}
