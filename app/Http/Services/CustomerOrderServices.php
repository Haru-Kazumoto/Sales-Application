<?php

namespace App\Http\Services;
use App\Models\Transactions;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;

class CustomerOrderServices {

    use Filterable;

    public function getTransactions(
        ?string $transactionType = null, 
        ?string $generated = "false",
        ?int $perPage = null, 
        ?string $category = null, 
        ?string $value = null
    )
    {
        // Build the base query
        $query = Transactions::with('transactionType', 'transactionDetails', 'transactionItems')
            ->whereHas('transactionDetails', function($query) {
                $query->where('category', 'Shipping Warehouse')
                    ->whereNotIn('value', ['DIRECT', 'DIRECT_DEPO', 'DO']);
            });

        if(!is_null($generated)) {
            $query->whereHas('transactionDetails', function($query) use ($generated) {
                $query->where('category', 'Generating')->where('value', $generated);
            });
        }

        // Apply filters using helper functions
        $query = $this->applyTransactionTypeFilter($query, $transactionType);
        $query = $this->applyTransactionDetailsFilter($query, $category, $value);

        // Apply ordering
        $query->orderByDesc('created_at');

        // Conditionally apply pagination if $perPage is provided, otherwise just get the results
        return $this->applyPagination($query, $perPage);
    }
}