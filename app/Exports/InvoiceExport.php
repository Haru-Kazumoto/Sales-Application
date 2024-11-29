<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoiceExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    protected $index = 1;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transactions::query()
            ->with(['transactionDetails','transactionItems.product'])
            ->where('transaction_type_id', 6) // 6 is invoices
            ->whereDate('created_at', Carbon::today())
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
            ->orderByDesc('created_at')
            ->get();
        
        return $query;
    }

    public function headings(): array
    {
        return [
            "#",
            "PT",
            "CUSTOMER",
            "NOMOR FAKTUR",
            "NILAI PEMBAYARAN",
        ];
    }

    // TODO : TAMBAHIN Warehouse DI TX DETAILS SAAT PEMBUATAN INVOICE GUA LUPA BJIR BIAR JADI DI SINI GAMPANG BUAT AMBILNYA
    public function map($row): array
    {
        $warehouse = $row->transactionDetails->firstWhere('category', 'Warehouse');
        $customer = $row->transactionDetails->firstWhere('category', 'Customer');

        return [
            $this->index++,
            $warehouse ? $warehouse->value : 'NOT FILLED YET',
            $customer ? $customer->value : 'NOT FILLED YET',
            $row->document_code,
            $this->formatRupiah($row->total), // saya ingin total ini seperti rupiah, contoh: Rp 753.000,00
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 20,
            'C' => 22,
            'D' => 20,
            'E' => 21,
        ];
    }

    /**
     * Format angka ke dalam format Rupiah.
     *
     * @param float|int $amount
     * @return string
     */
    protected function formatRupiah($amount): string
    {
        return 'Rp ' . number_format($amount, 2, ',', '.');
    }
}
