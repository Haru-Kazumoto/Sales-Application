<?php

namespace App\Imports;

use App\Models\Parties;
use App\Models\PartiesGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CustomerImport implements ToCollection, WithStartRow, WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            // 'VENDOR' => new VendorImport(),
            'DATABASE 2025' => new CustomerImport(),
        ];
    }

    /**
     * Tentukan baris awal data yang akan diproses.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Data mulai dari baris ke-3
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $type = PartiesGroup::where('name', 'Others')->first();
        DB::transaction(function() use ($collection, $type) {
            foreach ($collection as $row) {
                // Cek apakah kode sudah ada di database
                $existingParty = Parties::where('code', $row[2])->first();

                // Jika kode sudah ada, skip iterasi ini
                if ($existingParty) {
                    continue;
                }

                // Jika tidak ada, buat data baru
                Parties::create([
                    'company' => $row[1],
                    'code' => $row[2],
                    'npwp' => $row[3],
                    'ktp' => $row[4],
                    'name' => $row[5],
                    'legality' => "PT",
                    'phone' => $row[9],
                    'address' => $row[10],
                    'term_payment' => $row[12],
                    'taxpayer' => 'PKP',
                    'type_parties' => "CUSTOMER",
                    'payment_customer' => $row[13],
                    'parties_group_id' => $type->id,
                    'owner' => $row[7],
                    'pic' => $row[8],
                    'return_address' => $row[11],
                    // 'segment_customer' => $row[13],
                ]);
            }
        });
    }
}
