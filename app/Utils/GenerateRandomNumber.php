<?php

use App\Models\Transactions;

class GenerateRandomNumber {
    public static function generateDocumentCode(int $tx_type_id): string
    {
        $year = now()->format('y'); 
        $month = now()->format('m'); 

        $latestTransaction = Transactions::where('transaction_type_id', $tx_type_id)
            ->where('document_code', 'LIKE', "{$year}{$month}-%") 
            ->orderBy('document_code', 'desc')
            ->first();

        // Generate nomor invoice baru
        if ($latestTransaction) {
            $lastIncrement = (int) substr($latestTransaction->document_code, -5); // Ambil 5 digit terakhir
            $newIncrement = str_pad($lastIncrement + 1, 5, '0', STR_PAD_LEFT); // Tambahkan 1 dan format jadi 5 digit
        } else {
            $newIncrement = '00001'; // Jika belum ada nomor di bulan ini
        }

        return "{$year}{$month}-{$newIncrement}";
    }
}