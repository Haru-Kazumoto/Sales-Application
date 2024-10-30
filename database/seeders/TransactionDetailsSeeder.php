<?php

namespace Database\Seeders;

use App\Models\TransactionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Gudang', 'category' => 'Warehouse', 'value' => 'DNP', 'data_type' => 'string', 'transactions_id' => 96160266],
            ['name' => 'Gudang', 'category' => 'Warehouse', 'value' => 'DNP', 'data_type' => 'string',  'transactions_id' => 96160267],
        ];

        foreach($data as $d) 
        {
            TransactionDetail::create($d);
        }
    }
}
