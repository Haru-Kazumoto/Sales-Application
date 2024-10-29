<?php

namespace App\Http\Services;

use App\Models\Lookup;
use Illuminate\Database\Eloquent\Collection;

class LookupServices {

    /**
     * Get lookup by the column 
     * 
     * @param string $column
     * @param string $value
     * @return void
     */
    public function getAllLookupBy(string $column, string $value): Collection
    {
        $query = Lookup::query()->where($column, $value);

        return $query->get();
    }
}