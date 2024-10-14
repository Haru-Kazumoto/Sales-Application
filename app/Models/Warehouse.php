<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = "warehouse";

    protected $fillable = ['name','value'];

    public function productJournals(): HasMany
    {
        return $this->hasMany(ProductJournal::class);
    }
}
