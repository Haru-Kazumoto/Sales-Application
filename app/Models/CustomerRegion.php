<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerRegion extends Model
{
    use HasFactory;

    protected $table = 'customer_region';

    protected $fillable = [
        'region',
        'transportation_cost',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Parties::class);
    }
}
