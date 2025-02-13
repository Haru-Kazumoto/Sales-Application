<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasFactory;

    protected $table = 'product_prices';

    protected $fillable = [
        'redemp_price',
        'retail_price',
        'grosir_price',
        'end_user_price',
        'all_segment_price',
        'percentage',
        'transportation_cost',
        'oh_depo',
        'budget_marketing',
        'bad_debt',
        'saving',
        'margin_retail',
        'margin_grosir',
        'margin_end_user',
        'margin_all_segment',
        'rounded_all_segment_price',
        'product_id',
        'shipping_id',
        'sub_shipping_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
