<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menus extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_name',
        'icon_name',
        'link_to'
    ];

    public function roles(): BelongsToMany 
    {
        return $this->belongsToMany(Role::class, 'role_menu');
    }
}