<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_number',
        'month',
        'distributor_name',
        'area',
        'program',
        'sub_total',
        'grand_total',
    ];

    public function paymentPromos(): HasMany
    {
        return $this->hasMany(PaymentPromo::class);
    }

    public function customerOrderProducts(): HasMany 
    {
        return $this->hasMany(CustomerOrderProduct::class);
    }

    public function getPromoProducts() 
    {
        return PromoClaim::with('customerOrderProducts')
            ->whereNotNull('discount_1')
            ->orWhereNotNull('discount_2')
            ->orWhereNotNull('discount_3')
            ->get();
    }
}
