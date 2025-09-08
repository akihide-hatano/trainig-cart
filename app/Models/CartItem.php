<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    /**
     * Get the product that the cart item belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}