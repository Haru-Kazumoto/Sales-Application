<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parties extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type_parties',
        'phone',
        'fax',
        'handphone',
        'email',
        'website',
        'npwp',
        'ktp',
        'contact_person',
        'address',
        'postal_code',
        'term_payment',
        'legality',
        'city',
        'province',
        'country',
        'description',
        'number_plate',
        'parties_group_id',
        'users_id',
        'ktp_image',
        'npwp_image',
        'segment_customer',
        'company',
        'taxpayer',
        'payment_customer',
        'return_address',
        'owner',
        'pic',
    ];

    public function partiesGroup(): BelongsTo
    {
        return $this->belongsTo(PartiesGroup::class, 'parties_group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transactions::class);
    }

    public function customerRegion(): BelongsTo
    {
        return $this->belongsTo(CustomerRegion::class, 'region_id');
    }
}
