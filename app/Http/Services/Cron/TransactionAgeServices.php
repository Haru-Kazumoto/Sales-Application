<?php

namespace App\Http\Services\Cron;

use App\Models\Transactions;
use Illuminate\Support\Facades\DB;

class TransactionAgeServices
{
    public function __invoke()
    {
        /**
         *  TODO : create logic get transactions where transaction_type_id is 6
         *         and also paste the logic for get the transaction status.  
         *         Then update the transaction_age daily if the transaction status 
         *         is not PAID or FULLY PAID(paid with one payment) then increment the age,
         *         otherwhise stop the increment.
         * 
         * NOTE : Don't use for each instead use raw sql for maximum the perform of code
         */ 

         //get invoice transaction
        $query = Transactions::query()
            ->where('transaction_type_id', 6)
            ->selectRaw('transactions.*, 
                (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) as total_left, 
                CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = total THEN "UNPAID"
                    WHEN due_date <= CURDATE() THEN "OVERDUE"
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 THEN "INSTALMENT" 
                    ELSE "PAID" 
                END as status_payment'
            );
        
        dd($query->get()->toArray());
    }
}
