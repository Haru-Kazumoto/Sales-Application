<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClaimExport implements FromCollection,WithHeadings, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('transactions as tx')
            ->join('transaction_items as ti', 'ti.transactions_id', '=', 'tx.id')
            ->select([
                'tx.document_code',
                DB::raw("(
                        SELECT DATE_FORMAT(transaction_details.value, '%d %M %Y')  
                        FROM transaction_details 
                        WHERE transaction_details.transactions_id = tx.id 
                        AND transaction_details.category = 'CO Date' LIMIT 1
                    ) as co_date"),
                DB::raw("(SELECT value FROM transaction_details WHERE transaction_details.transactions_id = tx.id AND transaction_details.category = 'Customer' LIMIT 1) as customer"),
                DB::raw("(SELECT name FROM products WHERE products.id = ti.product_id LIMIT 1) as product_name"),
                'ti.quantity',
                'ti.amount as amount_exc_ppn',
                DB::raw("ROUND(ti.amount * ti.quantity, 0) as total"),
                'ti.discount_1 as discount_local',
                DB::raw("ROUND((ti.amount * (ti.discount_1 / 100)), 0) as total_diskon_lokal"),
                'ti.discount_2 as discount_ws',
                DB::raw("ROUND(((ti.amount - (ti.amount * (ti.discount_1 / 100))) * (ti.discount_2 / 100)), 0) as total_diskon_ws"),
                'ti.discount_3 as discount_dekat_ed',
                DB::raw("ROUND(((ti.amount - (ti.amount * (ti.discount_1 / 100)) - ((ti.amount - (ti.amount * (ti.discount_1 / 100))) * (ti.discount_2 / 100))) * (ti.discount_3 / 100)), 0) as total_diskon_dekat_ed"),
                DB::raw("ROUND(
                    (
                    (ti.amount * (ti.discount_1 / 100)) +
                    ((ti.amount - (ti.amount * (ti.discount_1 / 100))) * (ti.discount_2 / 100)) +
                    ((ti.amount - (ti.amount * (ti.discount_1 / 100)) - ((ti.amount - (ti.amount * (ti.discount_1 / 100))) * (ti.discount_2 / 100))) * (ti.discount_3 / 100))
                    ) * ti.quantity, 0
                ) as total_price_all"),
            ])
            ->where('tx.transaction_type_id', 8)
            ->where('ti.has_claimed', 1)
            ->get();

        // Format data
        return $data->map(function ($row) {
            return [
                'document_code' => $row->document_code, //
                'co_date' => $row->co_date, //
                'customer' => $row->customer,
                'product_name' => $row->product_name,
                'quantity' => $row->quantity,
                'amount_exc_ppn' => $this->formatRupiah($row->amount_exc_ppn),
                'total' => $this->formatRupiah($row->total),
                'discount_local' => $row->discount_local."%",
                'total_diskon_lokal' => $this->formatRupiah($row->total_diskon_lokal),
                'discount_ws' => $row->discount_ws."%",
                'total_diskon_ws' => $this->formatRupiah($row->total_diskon_ws),
                'discount_dekat_ed' => $row->discount_dekat_ed."%",
                'total_diskon_dekat_ed' => $this->formatRupiah($row->total_diskon_dekat_ed),
                'total_price_all' => $this->formatRupiah($row->total_price_all),
            ];
        });
    }


    public function headings(): array
    {
        return [
            "NOMOR DOKUMEN",
            "TANGGAL DOKUMEN",
            "CUSTOMER",
            "NAMA PRODUK",
            "KUANTITI",
            "HARGA (EXCLUDE PPN)",
            "TOTAL HARGA",
            "DISKON LOKAL",
            "TOTAL DISKON LOKAL",
            "DISKON WS",
            "TOTAL DISKON WS",
            "DISKON DEKAT ED",
            "TOTAL DISKON DEKAT ED",
            "TOTAL ALL"
        ];
    }

    protected function formatRupiah($amount): string
    {
        return 'Rp ' . number_format($amount, 2, ',', '.');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 23,
            'C' => 30,
            'D' => 40,
            'E' => 13,
            'F' => 23,
            'G' => 24,
            'H' => 18,
            'I' => 18,
            'J' => 12,
            'K' => 20,
            'L' => 18,
            'M' => 25,
            'N' => 24,
        ];
    }

}
