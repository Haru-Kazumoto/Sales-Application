<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalElementPrice extends Model
{
    use HasFactory;

    protected $table = 'global_element_price';

    protected $fillable = [
        'name_element',
        'price_element',
    ];
}
