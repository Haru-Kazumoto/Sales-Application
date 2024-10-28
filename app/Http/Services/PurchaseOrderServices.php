<?php

namespace App\Http\Services;

use App\Models\Transactions;
use App\Traits\Filterable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PurchaseOrderServices 
{
    use Filterable;

    /**
     * Getting all purchase order data with transportation (as number plate) is null
     * 
     * @param mixed $filterField
     * @param mixed $filterQuery
     * @param mixed $order_by
     * @param mixed $paginate
     * @return \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPurchaseOrderWithNumberPlateIsNull(
        ?string $filterRangeDate = null,
        ?string $filterField = null, 
        ?string $filterQuery = null, 
        ?string $order_by = 'asc',
        ?int $paginate = null
    ): Collection|LengthAwarePaginator
    {
        $query = Transactions::query()->whereHas('transactionDetails', function($query) {
            $query->where('category','Transportation')->where('value', '-');
        })->with('transactionDetails');

        $query = $this->scopeFilterByDateRange($query,$filterRangeDate);
        $query = $this->applySearchFilter($query,$filterField,$filterQuery);

        if(!is_null($order_by))
        {
            $query->orderBy('created_at', $order_by);
        }

        return $this->applyPagination($query,$paginate);
    }
}