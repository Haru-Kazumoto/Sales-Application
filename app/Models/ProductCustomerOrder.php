<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductCustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'quantity',
        'package',
        'price',
        'discount_1',
        'total_price_discount_1',
        'discoutn_2',
        'total_price_discount_2',
        'discount_3',
        'total_price_discount_3',
    ];

    public function customerOrder(): BelongsTo
    {
        return $this->belongsTo(CustomerOrders::class);
    }

    public function promoClaim(): BelongsTo
    {
        return $this->belongsTo(PromoClaim::class);
    }
}
