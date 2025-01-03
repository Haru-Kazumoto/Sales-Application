<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // ['name' => "Booking Order"],
            // ['name' => "Surat Jalan"],
            // ['name' => "Barang Masuk"],
            // ['name' => "Barang Keluar"],
            ['name' => 'Klaim Promo']
        ];

        foreach($data as $d) 
        {
            TransactionType::create($d);
        }
    }
}
