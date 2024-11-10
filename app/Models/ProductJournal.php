<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductJournal extends Model
{
    use HasFactory;

    protected $table = "product_journal";

    protected $fillable = [
        'quantity',
        'amount',
        'action',
        'description',
        'expiry_date',
        'warehouse_id',
        'transactions_id',
        'product_id',
        'batch_code',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transactions::class, 'transactions_id');
    }
}
