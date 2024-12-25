<?php

namespace App\Imports;

use App\Models\Parties;
use App\Models\PartiesGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CustomerImport implements ToCollection, WithStartRow
{

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
        // dd($collection);

        $type = PartiesGroup::where('name', 'Others')->first();

        DB::transaction(function() use ($collection, $type) {
            // dd($collection);
            foreach($collection as $row) {
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
