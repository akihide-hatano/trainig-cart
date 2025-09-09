<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

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

        Product::create([
            'name' => 'レザーバッグ',
            'description' => '高級感のあるレザーを使用したショルダーバッグ。',
            'price' => 15000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Bag'
        ]);

        Product::create([
            'name' => 'スポーツウォッチ',
            'description' => '防水性能とタイマー機能付きの多機能腕時計。',
            'price' => 9000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Watch'
        ]);

        Product::create([
            'name' => 'フーディーパーカー',
            'description' => '柔らかい裏起毛素材で、秋冬にぴったりのパーカー。',
            'price' => 6000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Parker'
        ]);

        Product::create([
            'name' => 'サマーハット',
            'description' => '通気性が良く、夏のお出かけに最適なストローハット。',
            'price' => 3000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Hat'
        ]);

        Product::create([
            'name' => 'レザーベルト',
            'description' => 'シンプルで高品質なレザーを使用したベルト。',
            'price' => 4500,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Belt'
        ]);

        Product::create([
            'name' => 'ランニングシューズ',
            'description' => '軽量でクッション性に優れたランニング専用シューズ。',
            'price' => 11000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=RunningShoes'
        ]);

        Product::create([
            'name' => 'デニムジャケット',
            'description' => 'カジュアルコーデに合わせやすい定番デニムジャケット。',
            'price' => 13000,
            'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=DenimJacket'
        ]);
    }
}