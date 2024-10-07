<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use HasFactory;

    protected $table = "transaction_items";

    protected $fillable = [
        'unit',
        'quantity',
        'tax_amount',
        'amount',
        'discount_1',
        'discount_2',
        'discount_3',
        'transactions_id',
        'product_id',
        'tax_id',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactions::class, 'transactions_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}
