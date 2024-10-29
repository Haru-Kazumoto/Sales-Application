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
            ['name' => 'Salesman', 'category' => 'Salesman', 'value' => 'Sales User', 'data_type' => 'string', 'transactions_id' => 96160266],
            ['name' => 'Salesman', 'category' => 'Salesman', 'value' => 'Sales User', 'data_type' => 'string', 'transactions_id' => 96160267],
        ];

        foreach($data as $d) 
        {
            TransactionDetail::create($d);
        }
    }
}
