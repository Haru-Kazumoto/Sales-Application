<?php

namespace App\Http\Services;

use App\Models\Parties;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PartiesServices {
    use Filterable;

    public function getPartiesByGroupAndType(string $type,?string $groupName)
    {
        $parties = Parties::where('type_parties', $type)
            ->whereHas('partiesGroup', function($query) use ($groupName) {
                $query->where('name', $groupName);
            })
            ->get();

        return $parties;
    }

    /**
     *  Get all parties by type and with filtered method
     * 
     * @param string $partiesType
     * @param mixed $filteredField
     * @param mixed $filteredQuery
     * @param mixed $pagination
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function getFilteredParties(
        string $partiesType, 
        ?string $filteredField = null, 
        ?string $filteredQuery = null, 
        ?int $pagination = 10
    ): Collection|LengthAwarePaginator
    {
        // Initialize query with eager loading for partiesGroup
        $query = Parties::with('partiesGroup')->where('type_parties', $partiesType);

        // Apply search filter if provided
        $query = $this->applySearchFilter($query, $filteredField, $filteredQuery);

        // Apply pagination with appends to retain filter fields in URL
        return $this->applyPagination($query, $pagination, [
            'filter_field' => $filteredField,
            'filter_query' => $filteredQuery,
        ]);
    }
}