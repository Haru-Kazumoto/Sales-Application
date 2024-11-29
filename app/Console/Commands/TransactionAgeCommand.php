<?php

namespace App\Console\Commands;

use App\Models\Transactions;
use Illuminate\Console\Command;

class TransactionAgeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:increment-age';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To incement the transaction_age on transactions for invoices age';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         //get invoice transaction
         Transactions::query()
            ->where('transaction_type_id', 6)
            ->selectRaw('transactions.*, 
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
            ->havingRaw('status_payment NOT IN ("PAID", "FULLY PAID")')
            ->chunk(200, function($transactions) {
                foreach($transactions as $transaction)
                {
                    $transaction->transaction_age += 1;
                    $transaction->save();
                }
            });
    }
}
