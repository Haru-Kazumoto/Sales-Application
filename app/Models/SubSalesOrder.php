<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubSalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_number',
        'proof_number',
        'sales_order_number',
        'order_date',
        'located',
        'supplier',
        'storehouse',
        'send_date',
        'transportation',
        'sender',
        'delivery_type',
        'employee_name',
        'sub_total',
        'total_after_ppn',
        'total_price',
        'note',
        'term_of_payment',
    ];

    public function subSalesOrderProducts(): HasMany
    {
        return $this->hasMany(SubSalesOrderProduct::class);
    }
    
}
