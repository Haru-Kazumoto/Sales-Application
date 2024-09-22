<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuAccess extends Model
{
    use HasFactory;

    protected $table = 'menu_access';

    protected $fillable = ['menu_name', 'menu_icon', 'menu_key', 'menu_url', 'base_menu_access_for'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'menu_access_user');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuAccess::class, 'parent_id');
    }
}
