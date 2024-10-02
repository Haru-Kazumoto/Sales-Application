<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubSalesOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'amount',
        'package',
        'product_price',
        'total_price',
        'ppn',
        'sub_sales_order_id'
    ];

    protected function casts(): array
    {
        return [
            'procuct_price' => 'float',
            'total_price' => 'float',
            'ppn' => 'float',
        ];
    }

    public function subSalesOrder(): BelongsTo
    {
        return $this->belongsTo(SubSalesOrder::class, 'sub_sales_order_id');
    }
}
