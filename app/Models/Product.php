<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * まとめて代入可能なカラム
     */
    protected $fillable = [
        'name',
        'description',
        'price',   // 整数（例：税込の円）
        'image',   // 画像URL or パス
    ];

    /**
     * キャスト
     */
    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * 関連：カートアイテム（1商品に紐づく複数のカート行）
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * 関連：注文アイテム（1商品に紐づく複数の注文明細）
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
