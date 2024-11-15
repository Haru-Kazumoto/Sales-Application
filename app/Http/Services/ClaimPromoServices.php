<?php

namespace App\Http\Services;

use App\Models\Transactions;
use Illuminate\Support\Facades\DB;

class ClaimPromoServices
{

    public function getDataClaims(?int $pagination = null)
    {
        $query = DB::table('transactions')
            ->select(
                'transactions.*',
                'transaction_items.*',
                DB::raw("
                    (
                        SELECT value FROM transaction_details WHERE transaction_details.transactions_id = transactions.id 
                        AND transaction_details.category = 'CO Date' LIMIT 1
                    ) as co_date
                "),
                DB::raw("
                    (
                        SELECT value FROM transaction_details WHERE transaction_details.transactions_id = transactions.id
                        AND transaction_details.category = 'Customer' LIMIT 1
                    ) as customer
                "),
                DB::raw("
                    (
                        SELECT name FROM products WHERE products.id = transaction_items.product_id LIMIT 1
                    ) as product_name
                ")
            )
            ->join('transaction_items', 'transaction_items.transactions_id', '=', 'transactions.id')
            ->where('transactions.transaction_type_id', 8)
            ->where('transaction_items.has_claimed', false)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('transaction_items.discount_1')
                    ->where('transaction_items.discount_1', '!=', 0);
                })
                ->orWhere(function ($q) {
                    $q->whereNotNull('transaction_items.discount_2')
                    ->where('transaction_items.discount_2', '!=', 0);
                })
                ->orWhere(function ($q) {
                    $q->whereNotNull('transaction_items.discount_3')
                    ->where('transaction_items.discount_3', '!=', 0);
                });
            })
            ->orderByDesc('transactions.created_at');

        if ($pagination) {
            return $query->paginate($pagination);
        }

        return $query->get();
    }



}
