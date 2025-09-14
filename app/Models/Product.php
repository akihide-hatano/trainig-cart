<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * まとめて代入可能なカラム
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
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

        public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/600x600?text=No+Image';
        }

        // すでに外部URLならそのまま
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // ローカル保存（storage/app/public/...）の場合
        return asset('storage/'.$this->image);
    }
}
