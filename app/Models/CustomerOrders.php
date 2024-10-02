<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_number',
        'order_created',
        'customer_name',
        'legality',
        'customer_address',
        'term',
        'due_date',
        'salesman',
        'amount',
        'discount_total',
        'sub_total',
        'total_after_ppn',
        'total',
        'status_co'
    ];

    public function customerOrderProducts(): HasMany
    {
        return $this->hasMany(CustomerOrderProduct::class);
    }
}
