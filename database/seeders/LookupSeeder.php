<?php

namespace Database\Seeders;

use App\Models\Lookup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataToInsert = [
            ['label' => '10 HARI', 'value' => '10_HARI', 'category' => 'PAYMENT_TERM'],
            ['label' => '15 HARI', 'value' => '15_HARI', 'category' => 'PAYMENT_TERM'],
            ['label' => '20 HARI', 'value' => '20_HARI', 'category' => 'PAYMENT_TERM'],
            ['label' => '25 HARI', 'value' => '25_HARI', 'category' => 'PAYMENT_TERM'],
            ['label' => '30 HARI', 'value' => '30_HARI', 'category' => 'PAYMENT_TERM'],
            ['label' => 'DKU', 'value' => 'DKU', 'category' => 'STORE_LOCATION'],
            ['label' => 'DNP', 'value' => 'DNP', 'category' => 'STORE_LOCATION'],
            ['label' => 'DD', 'value' => 'DD', 'category' => 'STORE_LOCATION'],
            ['label' => 'Customer', 'value' => 'CUSTOMER', 'category' => 'TYPE_PARTIES'],
            ['label' => 'Supplier', 'value' => 'SUPPLIER', 'category' => 'TYPE_PARTIES'],
            ['label' => 'Vendor', 'value' => 'VENDOR', 'category' => 'TYPE_PARTIES'],
        ];

        foreach($dataToInsert as $data) {
            Lookup::create($data);
        }
    }
}
