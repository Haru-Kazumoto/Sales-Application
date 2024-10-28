<?php

namespace App\Http\Services;

use App\Models\Parties;

class PartiesServices {
    public function getPartiesByGroupAndType(string $type,?string $groupName)
    {
        $parties = Parties::where('type_parties', $type)
            ->whereHas('partiesGroup', function($query) use ($groupName) {
                $query->where('name', $groupName);
            })
            ->get();

        return $parties;
    }
}