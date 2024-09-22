<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestProducts extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'product_code',
        'product_name',
        'amount',
        'package',
        'product_price',
        'total_price',
        'ppn',
        'purchase_order_id'
    ];

    protected function casts(): array
    {
        return [
            'procuct_price' => 'float',
            'total_price' => 'float',
            'ppn' => 'float',
        ];
    }

    public function purcaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
