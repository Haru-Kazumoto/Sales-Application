<?php

namespace App\Imports;

use App\Models\Parties;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TransportImport implements ToCollection, WithStartRow
{

    public function startRow(): int
    {
        return 2; // Data mulai dari baris ke-3
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $parties_group_id = DB::table('parties_groups as pg')
            ->where('pg.name', 'Angkutan')
            ->value('id');

        if (!$parties_group_id) {
            throw new \Exception("Parties Group dengan nama 'Angkutan' tidak ditemukan.");
        }

        DB::transaction(function() use ($collection, $parties_group_id) {
            $data = [];

            foreach ($collection as $row) 
            {
                if ($row[0] === null) {
                    continue;
                }

                $data[] = [
                    'name' => $row[0],
                    'address' => $row[1],
                    'pic' => $row[2],
                    'phone' => $row[3],
                    'pic_2' => $row[4],
                    'phone_2' => $row[5],
                    'code' => 'TRN-' . rand(1000,9999),
                    'parties_group_id' => $parties_group_id,
                    'type_parties' => 'EXTERNAL_TRANSPORTATION',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            if (!empty($data)) {
                Parties::insert($data); // Batch insert
            }
        });
    }

}
