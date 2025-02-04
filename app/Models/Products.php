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
        'product_sub_type_id',
        'supplier_id',
        'package',
        'all_segment_price',
        'product_type',
        'product_company',
        'transportation_cost',
        'margin_retail',
        'margin_end_user',
        'margin_grosir',
        'discount_vendor',
        'region_delivery_id',
        'percentage',
        'rounded_all_segment_price',
    ];

    public function tradePromos()
    {
        return $this->belongsToMany(TradePromo::class, 'product_trade_promo', 'product_id', 'trade_promo_id')
            ->withTimestamps();
    }


    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function productSubType(): BelongsTo
    {
        return $this->belongsTo(ProductSubType::class, 'product_sub_type_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function parties(): BelongsTo
    {
        return $this->belongsTo(Parties::class, 'supplier_id');
    }

    public function promoProduct(): BelongsTo
    {
        return $this->belongsTo(PromoProgram::class, 'promo_product_id');
    }

    public function productJournals(): HasMany
    {
        return $this->hasMany(ProductJournal::class, 'product_id');
    }

    public function regionDelivery(): BelongsTo
    {
        return $this->belongsTo(RegionDelivery::class, 'region_delivery_id');
    }
}
