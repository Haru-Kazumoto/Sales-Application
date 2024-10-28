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
        if (!is_null($filterField) && !is_null($filterQuery)) 
        {
            $query->where($filterField, 'like', "%{$filterQuery}%");
        }

        return $query;
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
        switch ($range) {
            case 'this_week':
                // Filter for the current week
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(), // Start of this week
                    Carbon::now()->endOfWeek()    // End of this week
                ]);
                break;

            case 'this_month':
                // Filter for the current month
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfMonth(), // Start of this month
                    Carbon::now()->endOfMonth()    // End of this month
                ]);
                break;

            case 'this_year':
                // Filter for the current year
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfYear(),  // Start of this year
                    Carbon::now()->endOfYear()     // End of this year
                ]);
                break;

            default:
                // If no valid range is provided, return all results
                break;
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
    public function applyPagination(Builder $query, ?int $pagination = null): Collection|LengthAwarePaginator
    {
        if (!is_null($pagination)) {
            return $query->paginate($pagination);
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
