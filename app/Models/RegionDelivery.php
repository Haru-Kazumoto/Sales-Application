<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionDelivery extends Model
{
    use HasFactory;

    protected $table = 'region_delivery';

    protected $fillable = [
        'region_name',
        'region_code',
        'region_price',
    ];

    public function products()
    {
        return $this->hasMany(Products::class);
    }
}
