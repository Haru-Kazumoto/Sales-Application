<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'SUPERADMIN'],
            ['role_name' => 'ADMIN'],
            ['role_name' => 'FINANCE'],
            ['role_name' => 'WAREHOUSE'],
        ];

        DB::table('roles')->insert($roles);
    }
}
