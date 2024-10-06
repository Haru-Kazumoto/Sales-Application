<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'value',
        'data_type',
        'transactions_id',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactions::class, 'transactions_id');
    }
}
