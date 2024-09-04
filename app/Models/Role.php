<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menus::class, 'role_menu');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
