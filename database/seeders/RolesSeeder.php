<?php

namespace Database\Seeders;

use App\Models\Role;
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
            ['role_name' => 'ADMIN', 'role_uid' => strval(rand(10000,66666))],
            ['role_name' => 'USER', 'role_uid' => strval(rand(10000,66666))],
        ];

        foreach($roles as $role) {
            Role::create($role);
        }
    }
}
