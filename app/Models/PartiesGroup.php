<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartiesGroup extends Model
{
    use HasFactory;

    protected $table = 'parties_groups';

    protected $fillable = ['name', 'description'];

    public function Parties(): HasMany
    {
        return $this->hasMany(Parties::class);
    }
}
