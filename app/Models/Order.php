<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'total_amount',  // 合計金額
        'status',        // 状態（例：pending, paid, shipped, cancelled）
        'placed_at',     // 注文日時
    ];

    protected $casts =[
        'usr_id' => 'integer',
        'total_amount' => 'integer',
        'place_at' => 'datetime',
    ];

    /**
     * 関連: 注文アイテム（OrderItem がぶら下がる）
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * 関連: ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
