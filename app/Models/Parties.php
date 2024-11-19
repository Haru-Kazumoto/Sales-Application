<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function partiesGroup(): BelongsTo
    {
        return $this->belongsTo(PartiesGroup::class, 'parties_group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
