<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $dataUser = [
            [
                'fullname' => 'Admin',
                'user_uid' => rand(10000, 66666),
                'username' => 'admin',
                'email' => 'admin@danitama.com',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
                'division_id' => 5
            ],
        ];

        foreach($dataUser as $data) {
            User::create($data);
        }

    }
}
