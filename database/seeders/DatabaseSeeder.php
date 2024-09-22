<?php

use Database\Seeders\DivisionSeeder;
use Database\Seeders\LookupSeeder;
use Database\Seeders\MenuaccessSeeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\StorehouseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            DivisionSeeder::class,
            LookupSeeder::class,
            RolesSeeder::class,
            StorehouseSeeder::class,
            //temp
            UserSeeder::class,
            // MenuaccessSeeder::class,
        ]);
    }
}