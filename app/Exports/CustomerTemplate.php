<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerTemplate implements WithHeadings, WithColumnWidths
{
    public function headings(): array
    {
        return [
            "SALES",
            "PT",
            "CUSTOMER",
            "CHANNEL CUSTOMER",
            "OWNER",
            "PIC/PURCH",
            "TELEPON",
            "ALAMAT CUSTOMER",
            "ALAMAT KIRIM",
            "TOP",
            "METODE PEMBAYARAN"
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 10,
            'C' => 21,
            'D' => 20,
            'E' => 20,
            'F' => 40,
            'G' => 20,
            'H' => 31,
            'I' => 30,
            'J' => 30,
            'K' => 75,
            'L' => 75,
            'M' => 7,
            'N' => 25,
            'O' => 18
        ];
    }
}
