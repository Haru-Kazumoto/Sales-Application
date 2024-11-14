<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['fullname','phone', 'username', 'email', 'password', 'role_id', 'user_uid','division_id','is_active'];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo 
    {
        return $this->belongsTo(Role::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function menuAccess(): BelongsToMany
    {
        return $this->belongsToMany(MenuAccess::class, 'menu_access_user');
    }
    
    public function transactions(): HasMany
    {
        return $this->hasMany(Transactions::class);
    }

    public function parties(): HasMany
    {
        return $this->hasMany(Parties::class, 'users_id');
    }

    public function userTarget(): HasOne
    {
        return $this->hasOne(UserTarget::class, 'user_id');
    }

}
