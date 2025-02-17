<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = ['name'];

    public function subShippings(): HasMany
    {
        return $this->hasMany(SubShipping::class);
    }

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }
}
