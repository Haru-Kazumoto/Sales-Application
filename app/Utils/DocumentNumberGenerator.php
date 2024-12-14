<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class DocumentNumberGenerator
{
    /**
     * Generate a document number.
     *
     * @param string $prefix Prefix for the document (e.g., "PO" or null for invoices)
     * @param string $table Database table where the document numbers are stored
     * @param string $column Column name where the document numbers are stored
     * @param string $filterColumn Column name to filter specific rows (e.g., "transaction_type_id")
     * @param int|null $filterValue Value to filter specific rows (e.g., 6 for invoices)
     * @return string Generated document number
     */
    public static function generate($prefix, $table, $column,$tx_type_id)
    {
        // Get the current year and month
        $year = now()->format('y'); // 2-digit year
        $month = now()->format('m'); // 2-digit month

        // Build query to get the latest document number
        $query = DB::table($table)
            ->select($column)
            ->where($column, 'LIKE', "{$prefix}{$year}{$month}-%")
            ->where('transaction_type_id', $tx_type_id);

        // Get the latest document number
        $latestDocument = $query->orderBy($column, 'desc')->first();

        // Calculate the new increment number
        if ($latestDocument) {
            $lastIncrement = (int) substr($latestDocument->{$column}, -5); // Extract last 5 digits
            $newIncrement = str_pad($lastIncrement + 1, 5, '0', STR_PAD_LEFT); // Increment and pad
        } else {
            $newIncrement = '00001'; // Default to 00001
        }

        // Return the generated document number
        return "{$prefix}{$year}{$month}-{$newIncrement}";
    }

    /**
     * Generate a CO document number.
     *
     * @param string $prefix Prefix for the document (e.g., "PO" or null for invoices)
     * @param string $table Database table where the document numbers are stored
     * @param string $column Column name where the document numbers are stored
     * @param string $filterColumn Column name to filter specific rows (e.g., "transaction_type_id")
     * @param int|null $filterValue Value to filter specific rows (e.g., 6 for invoices)
     * @return string Generated document number
     */
    public static function generateCoNumber($prefix, $table, $column)
    {
        // Get the current year and month
        $year = now()->format('y'); // 2-digit year
        $month = now()->format('m'); // 2-digit month

        // Build query to get the latest document number
        $query = DB::table($table)
            ->select($column)
            ->where($column, 'LIKE', "{$prefix}{$year}{$month}-%")
            ->whereIn('transaction_type_id', [8,70]);

        // Get the latest document number
        $latestDocument = $query->orderBy($column, 'desc')->first();

        // Calculate the new increment number
        if ($latestDocument) {
            $lastIncrement = (int) substr($latestDocument->{$column}, -5); // Extract last 5 digits
            $newIncrement = str_pad($lastIncrement + 1, 5, '0', STR_PAD_LEFT); // Increment and pad
        } else {
            $newIncrement = '00001'; // Default to 00001
        }

        // Return the generated document number
        return "{$prefix}{$year}{$month}-{$newIncrement}";
    }
}
