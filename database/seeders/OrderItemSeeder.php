<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders   = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            // 各注文に 1〜3 個の商品を紐づける
            $selectedProducts = $products->random(rand(1, 3));
            $total = 0;

            foreach ($selectedProducts as $product) {
                $qty   = rand(1, 5);
                $price = $product->price;
                $subtotal = $price * $qty;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'unit_price' => $price,
                    'quantity'   => $qty,
                    'subtotal'   => $subtotal,
                ]);
                $total += $subtotal;
            }
            // Order の合計を更新
            $order->update(['total_amount' => $total]);
        }
    }
}
