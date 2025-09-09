<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 10件分の注文データを作成
        for ($i = 1; $i <= 10; $i++){
            Order::create([
                'user_id'      => rand(1, 3), // 1〜3のユーザーIDをランダムで割り当て
                'total_amount' => rand(3000, 20000), // 3000〜20000円
                'status'       => $i % 2 === 0 ? 'paid' : 'pending',
                'placed_at'    => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
