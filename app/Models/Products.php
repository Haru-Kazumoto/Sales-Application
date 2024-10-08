<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'unit',
        'name',
        'category',
        'supplier',
        'redemp_price',
        'retail_price',
        'restaurant_price',
        'price_3',
        'dd_price',
        'normal_margin',
        'oh_depo',
        'saving',
        'bad_debt_dd',
        'saving_marketing',
        'tax_id',
        'product_type_id',
    ];

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
