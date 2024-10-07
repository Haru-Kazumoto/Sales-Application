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
            ['name' => 'CUSTOMER', 'message' => 'Hi {name} mohon segera lengkapin pembayaran, butuh uwank', 'placeholder' => null],
            ['name' => 'CUSTOMER', 'message' => 'Hi {name} mohon segera lengkapin pembayaran anda dibulan ini', 'placeholder' => null]
        ];

        foreach($messages as $message) {
            MessageTemplate::create($message);
        }
    }
}
