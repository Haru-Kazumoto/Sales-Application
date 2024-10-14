<?php

namespace App\Http\Services;
use App\Models\Transactions;

class CustomerOrderServices {
    public function getTransactions(?string $transactionType = null, ?int $perPage = null, ?string $category = null, ?string $value = null)
    {
        // Build the base query
        $query = Transactions::with('transactionType', 'transactionDetails', 'transactionItems');

        // Conditionally apply the transactionType filter if provided
        if ($transactionType) {
            $query->whereHas('transactionType', function ($query) use ($transactionType) {
                $query->where('name', $transactionType);
            });
        }

        // Conditionally apply the transactionDetails filter if both category and value are provided
        if ($category && $value) {
            $query->whereHas('transactionDetails', function ($query) use ($category, $value) {
                $query->where('category', $category)
                    ->where('value', $value);
            });
        }

        // Apply ordering
        $query->orderBy('created_at', 'asc');

        // Conditionally apply pagination if $perPage is provided, otherwise just get the results
        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }


}