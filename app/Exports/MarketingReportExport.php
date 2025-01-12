<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MarketingReportExport implements FromCollection,WithHeadings, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('report_marketing')->get();
    }

    public function headings(): array
    {
        return [
            "TAHUN",
            "PT",
            "BULAN",
            "SALESMAN",
            "CUSTOMER",
            "NO PO/CO",
            "PRODUK",
            "KEMASAN",
            "GOLONGAN",
            "SEGMEN PRODUK",
            "DELIVERY",
            "ALOKASI",
            "HARGA TEBUS",
            "ANGKUTAN",
            "MARGIN NORMAL",
            "OH DEPO",
            "CASHBACK + PPH 4%",
            "BONGKAR",
            "SAVING",
            "BAD DEBT",
            "BUDGET MARKETING",
            "PPN 11%",
            "HARGA JUAL NORMAL",
            "HARGA JUAL CUSTOMER",
            "SELISIH (+/-)",
            "QUANTITY",
            "SURPLUR/DEFISIT",
            "TOTAL HARGA TEBUS",
            "TOTAL ANGKUTAN",
            "TOTAL OH",
            "TOTAL CASHBACK",
            "TOTAL ONGKOS BONGKAR",
            "TOTAL BAD DEBT",
            "TOTAL BUDGET MARKETING",
            "TOTAL PPN 11%",
            "TOTAL MARGIN NORMAL",
            "TOTAL NETT MARGIN",
            "GRAND TOTAL OMZET",
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
