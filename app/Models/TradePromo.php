<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradePromo extends Model
{
    use HasFactory;

    protected $table = "trade_promo";

    protected $fillable = [
        'grosir_account',
        'discount_price',
        'quota',
        'is_active',
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_trade_promo', 'trade_promo_id', 'product_id')
            ->withTimestamps();
    }


    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
