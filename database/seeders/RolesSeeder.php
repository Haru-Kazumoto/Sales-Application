<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

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

        Role::create($roles);
    }
}
