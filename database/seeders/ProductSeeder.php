<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // 💡 Productモデルをuse

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 💡 DB::table()->insert()の代わりにProduct::create()を使用
        Product::create([
            'name' => 'クラシック Tシャツ',
            'description' => '快適な着心地のコットンTシャツです。',
            'price' => 2500,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=T-Shirt'
        ]);

        Product::create([
            'name' => 'プレミアムジーンズ',
            'description' => '耐久性に優れた、スリムフィットの高品質ジーンズです。',
            'price' => 8000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Jeans'
        ]);
        
        Product::create([
            'name' => '防水スニーカー',
            'description' => '雨の日でも安心して履ける、軽量で防水性の高いスニーカーです。',
            'price' => 12000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Sneakers'
        ]);
    }
}