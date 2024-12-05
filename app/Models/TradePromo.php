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

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
