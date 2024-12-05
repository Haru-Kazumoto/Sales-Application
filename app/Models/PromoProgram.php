<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoProgram extends Model
{
    use HasFactory;

    protected $table = 'promo_products';

    protected $fillable = [
        'name',
        'description',
        'min',
        'max',
        'promo_value_1',
        'promo_value_2',
        'promo_value_3',
        'file_attachment',
        'start_date',
        'end_date',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'promo_product_id');
    }
}
