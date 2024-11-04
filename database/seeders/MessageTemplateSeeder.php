<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            ['name' => 'SALES', 'message' => 'Hi {salesman} mohon segera tagih pembayaran customer anda sebesar {tagihan}', 'placeholder' => null],
            ['name' => 'CUSTOMER', 'message' => 'Hi {nama_customer} mohon segera lengkapin pembayaran anda dibulan ini', 'placeholder' => null]
        ];

        foreach($messages as $message) {
            MessageTemplate::create($message);
        }
    }
}
