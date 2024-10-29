<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait Filterable
{
    /**
     * Apply search filter to a query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $filterField
     * @param mixed $filterQuery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySearchFilter(Builder $query, ?string $filterField = null, ?string $filterQuery = null): Builder
    {
        return $filterField && $filterQuery 
            ? $query->where($filterField, 'like', "%{$filterQuery}%")
            : $query;
    }

    /**
     * Filter records based on created_at field using date range.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $range
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByDateRange(Builder $query, $range)
    {
        $dateRanges = [
            'this_week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'this_year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
        ];

        if (array_key_exists($range, $dateRanges)) {
            $query->whereBetween('created_at', $dateRanges[$range]);
        }

        return $query;
    }

    /**
     * Apply pagination to a query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $pagination
     * @return \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function applyPagination(Builder $query, ?int $pagination = null, array $appends = []): Collection|LengthAwarePaginator
    {
        $query->orderByDesc('created_at');

        // Jika pagination diinginkan
        if ($pagination) {
            $paginator = $query->paginate($pagination);

            if (!empty($appends)) {
                $paginator->appends($appends); // Memastikan filter tetap terlampir
            }

            return $paginator;
        }

        return $query->get();
    }

    /**
     * Apply transaction type filter
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $transactionType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyTransactionTypeFilter(Builder $query, ?string $transactionType): Builder
    {
        if (!is_null($transactionType)) {
            $query->whereHas('transactionType', function ($query) use ($transactionType) {
                $query->where('name', $transactionType);
            });
        }
        
        return $query;
    }

    /**
     * Apply transaction details filter
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $category
     * @param string|null $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyTransactionDetailsFilter(Builder $query, ?string $category, ?string $value): Builder
    {
        if (!is_null($category) && !is_null($value)) {
            $query->whereHas('transactionDetails', function ($query) use ($category, $value) {
                $query->where('category', $category)
                    ->where('value', $value);
            });
        }
        
        return $query;
    }
}
