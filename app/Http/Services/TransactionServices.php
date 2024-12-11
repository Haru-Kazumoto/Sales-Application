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
        ?int $paginate = null,
        ?string $dateRange = null,
        ?bool $showInvoicePayment = false,
    ): Collection|LengthAwarePaginator
    {
        $query = Transactions::with([
            'transactionDetails', 
            'transactionItems.product' ,
        ])
        ->where('transaction_type_id', $tx_type_id);
    
        // Terapkan filter pencarian
        $query = $this->applySearchFilter($query, $filterField, $filterQuery);
    
        // Menghitung total pembayaran dan sisa tagihan jika tidak menampilkan pembayaran invoice
        if ($showInvoicePayment === false) {
            $query->selectRaw('transactions.*, 
                (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) as total_left, 
                CASE 
                    WHEN (SELECT COUNT(*) FROM invoice_payment WHERE transaction_id = transactions.id) = 1 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = 0 THEN "FULLY PAID"
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = total THEN "UNPAID"
  	                WHEN due_date <= CURDATE() THEN "OVERDUE"
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 THEN "INSTALMENT" 
                    ELSE "PAID" 
                END as status_payment'
            )
            ->orderByDesc('created_at');
        }
    
        // // Pengurutan berdasarkan kondisi tertentu
        // $query->orderByRaw("
        //     CASE 
        //         WHEN EXISTS (
        //             SELECT 1 
        //             FROM transaction_details 
        //             WHERE transaction_details.transactions_id = transactions.id 
        //             AND transaction_details.category = 'Transportation' 
        //             AND transaction_details.value = '-'
        //         ) THEN 0 
        //         ELSE 1 
        //     END
        // ")
    
        // Tambahkan pengecekan untuk date range
        if (!is_null($dateRange)) {
            $query = $this->scopeFilterByDateRange($query, $dateRange);
        }
    
        // Terapkan pagination jika ada
        if (!is_null($paginate)) {
            return $this->applyPagination($query, $paginate);
        }
    
        return $query->get();
    }
    
}