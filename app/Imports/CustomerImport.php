<?php

namespace App\Imports;

use App\Models\Parties;
use App\Models\PartiesGroup;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CustomerImport implements ToCollection, WithStartRow, WithMultipleSheets
{
    public $dataReceived = 0;

    public function sheets(): array
    {
        return [
            // 'VENDOR' => new VendorImport(),
            'DATABASE DKU-DNP' => new CustomerImport(),
        ];
    }

    /**
     * Tentukan baris awal data yang akan diproses.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        dd($collection);
        $type = PartiesGroup::where('name', 'Others')->first();
        $dataReceived = 0;
        DB::transaction(function() use ($collection, $type, $dataReceived) {
            foreach ($collection as $row) {
                
                // Cek apakah kode sudah ada di database
                $existingParty = Parties::where('code', $row[2])->first();
                
                // Jika kode sudah ada, skip iterasi ini
                if ($existingParty) {
                    continue;
                }

                // cari id salesman dari kode karyawan
                $salesman = User::where('user_uid', $row[0])->first();

                // Jika tidak ada, buat data baru
                Parties::create([
                    'users_id' => $salesman?->id,
                    'company' => $row[2],
                    'code' => $row[3],
                    'npwp' => $row[4],
                    'ktp' => $row[5],
                    'name' => $row[6],
                    'legality' => "-",
                    'phone' => $row[9],
                    'address' => $row[10],
                    'term_payment' => $row[12],
                    'taxpayer' => 'PKP',
                    'type_parties' => "CUSTOMER",
                    'payment_customer' => $row[13],
                    'parties_group_id' => $type->id,
                    'owner' => $row[7],
                    'pic' => $row[8],
                    'return_address' => $row[11]
                    // 'segment_customer' => $row[13],
                ]);

                $dataReceived++;
            }
        });

        $this->dataReceived = $dataReceived;
    }
}
