<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimention extends Model
{
    use HasFactory;

    protected $table = 'dimention';

    protected $fillable = [
        'dimention_name', // string
        'min_value', // integer
        'max_value', // integer
        'price_dimention', // float
    ];
}
