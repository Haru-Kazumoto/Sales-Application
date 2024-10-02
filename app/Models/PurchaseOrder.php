<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_number',
        'supplier',
        'storehouse', //take name storehouse from storehouse table
        'located', //enum
        'purchase_order_date',
        'send_date',
        'payment_term',
        'due_date',
        'sender',
        'delivery_type',
        'transportation',
        'employee_name',
        'notes',
        'sub_total',
        'total_price',
        'total_ppn',
        'isDocumentHasGenerated',
    ];      

    protected function casts(): array
    {
        return [
            'purchase_order_date' => 'datetime',
            'send_date' => 'datetime',
            'due_date' => 'datetime',
            'sub_total' => 'float',
            'total_price' => 'float',
        ];
    }

    public function purchaseOrderProducts(): HasMany
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id');
    }

}
