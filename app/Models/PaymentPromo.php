<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentPromo extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_status',
        'payment_date',
        'payment_nominal'
    ];

    public function promoClaim(): BelongsTo
    {
        return $this->belongsTo(PromoClaim::class);
    }
}
