<?php

namespace App\Http\Services;
use App\Models\Transactions;
use App\Traits\Filterable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransactionServices 
{
    use Filterable;

    /**
     * Summary of getTransactions
     * 
     * @param mixed $tx_type_id
     * @param mixed $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function getTransactions(
        int $tx_type_id, 
        ?string $filterField = null, 
        ?string $filterQuery = null, 
        ?string $order_by = 'desc',
        ?int $paginate = null,
        ?string $dateRange = null,
    ): Collection|LengthAwarePaginator
    {
        $query = Transactions::with([
            'transactionDetails', 
            'transactionItems.product' 
        ])
            ->where('transaction_type_id', $tx_type_id);

        $query = $this->applySearchFilter($query, $filterField, $filterQuery);

        $query->orderByRaw("
            CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM transaction_details 
                    WHERE transaction_details.transactions_id = transactions.id 
                    AND transaction_details.category = 'Transportation' 
                    AND transaction_details.value = '-'
                ) THEN 0 
                ELSE 1 
            END
        ");

        if(!is_null($order_by))
        {
            $query->orderBy('created_at', 'desc');
        }

        // Tambahkan pengecekan untuk date range
        if (!is_null($dateRange)) {
            $query = $this->scopeFilterByDateRange($query, $dateRange);
        }

        return $this->applyPagination($query, $paginate);
    }
}