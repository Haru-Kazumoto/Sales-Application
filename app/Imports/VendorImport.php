<?php

namespace App\Imports;

use App\Models\Parties;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VendorImport implements ToCollection, WithStartRow
{

    /**
     * Tentukan baris awal data yang akan diproses.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 3; // Data mulai dari baris ke-3
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        DB::transaction(function() use ($collection) {
            foreach($collection as $row) 
            {
                Parties::create([
                    'code' => rand(1000,5000),
                    'name' => $row[1],
                    'type_parties' => 'VENDOR',
                    'taxpayer' => 'PKP',
                    'legality' => 'PT',
                    'term_payment' => 45,
                    'parties_group_id' => 6
                ]);
            }
        });
    }
}
