<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuaccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menu induk
        $parentMenus = [
            ['menu_name' => 'Sales Reports', 'menu_icon' => 'BarChartOutline', 'menu_key' => 'sales-reports', 'base_menu_access_for' => 'ADMIN'], // 1
            ['menu_name' => 'User Management', 'menu_icon' => 'PeopleOutline', 'menu_key' => 'user-management', 'base_menu_access_for' => 'ADMIN'], // 2
            ['menu_name' => 'Klaim', 'menu_icon' => 'DocumentOutline', 'menu_key' => 'claim', 'base_menu_access_for' => 'ADMIN'], // 7
            ['menu_name' => 'Penjualan', 'menu_icon' => 'PieChartOutline', 'menu_key' => 'sales', 'base_menu_access_for' => 'ADMIN'], // 10
            ['menu_name' => 'Pembelian', 'menu_icon' => 'BagCheckOutline', 'menu_key' => 'purchasing', 'base_menu_access_for' => 'ADMIN'], // 17
            ['menu_name' => 'Barang Masuk', 'menu_icon' => 'CubeOutline', 'menu_key' => 'barang-masuk', 'base_menu_access_for' => 'ADMIN'], // 23
            ['menu_name' => 'Penjualan', 'menu_icon' => 'CartSharp', 'menu_key' => 'sales', 'base_menu_access_for' => 'ADMIN'], // 37
        ];

        // Insert menu induk and store the IDs
        $menuIds = []; // Initialize array to store parent menu IDs
        foreach ($parentMenus as $menu) {
            $parentId = \App\Models\MenuAccess::create($menu)->id;
            $menuIds[] = $parentId; // Store parent ID for later use
        }

        // Menu anak
        $dataToInsert = [
            // User Management
            ['menu_name' => 'User', 'menu_icon' => 'PersonAddOutline', 'menu_key' => 'user', 'menu_url' => '/user-management', 'parent_id' => $menuIds[1], 'base_menu_access_for' => 'ADMIN'], // 3
            ['menu_name' => 'Role', 'menu_icon' => 'Contract', 'menu_key' => 'role', 'menu_url' => '/role-management', 'parent_id' => $menuIds[1], 'base_menu_access_for' => 'ADMIN'], // 4
            ['menu_name' => 'Divisi', 'menu_icon' => 'Analytics', 'menu_key' => 'division', 'menu_url' => '/division-management', 'parent_id' => $menuIds[1], 'base_menu_access_for' => 'ADMIN'], // 5
            ['menu_name' => 'Akses Menu', 'menu_icon' => 'CogOutline', 'menu_key' => 'menu-access', 'menu_url' => '/menu-access-management', 'parent_id' => $menuIds[1], 'base_menu_access_for' => 'ADMIN'], // 6
            
            // Klaim
            ['menu_name' => 'Klaim Promo', 'menu_icon' => 'DocumentTextOutline', 'menu_key' => 'claim-promo', 'menu_url' => '/claim-promo', 'parent_id' => $menuIds[2], 'base_menu_access_for' => 'ADMIN'], // 8
            ['menu_name' => 'List Klaim Promo', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'claim-promo-list', 'menu_url' => '/claim-promo-list', 'parent_id' => $menuIds[2], 'base_menu_access_for' => 'ADMIN'], // 9
            
            // Penjualan
            ['menu_name' => 'Invoice DNP', 'menu_icon' => 'ReceiptOutline', 'menu_key' => 'invoice-dnp', 'menu_url' => '/invoice-dnp', 'parent_id' => $menuIds[3], 'base_menu_access_for' => 'ADMIN'], // 11
            ['menu_name' => 'Invoice DKU', 'menu_icon' => 'ReceiptOutline', 'menu_key' => 'invoice-dku', 'menu_url' => '/invoice-dku', 'parent_id' => $menuIds[3], 'base_menu_access_for' => 'ADMIN'], // 12
            ['menu_name' => 'List Invoice', 'menu_icon' => 'ReorderFourSharp', 'menu_key' => 'list-invoice', 'menu_url' => '/list-invoices', 'parent_id' => $menuIds[3], 'base_menu_access_for' => 'ADMIN'], // 13
            
            // Pembelian
            ['menu_name' => 'Buat PO', 'menu_icon' => 'DocumentTextOutline', 'menu_key' => 'create-po', 'menu_url' => '/purchase-order', 'parent_id' => $menuIds[4], 'base_menu_access_for' => 'ADMIN'], // 18
            ['menu_name' => 'List PO', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'list-po', 'menu_url' => '/purchase-orders', 'parent_id' => $menuIds[4], 'base_menu_access_for' => 'ADMIN'], // 19
            
            // Barang Masuk
            ['menu_name' => 'Stok Gudang', 'menu_icon' => 'FileTrayFullOutline', 'menu_key' => 'stock-goods', 'menu_url' => '/stock-goods', 'parent_id' => $menuIds[5], 'base_menu_access_for' => 'ADMIN'], // 24
            
            // Penjualan CO
            ['menu_name' => 'Buat CO', 'menu_icon' => 'DocumentOutline', 'menu_key' => 'create-co', 'menu_url' => '/create-co', 'parent_id' => $menuIds[6], 'base_menu_access_for' => 'ADMIN'], // 38
            ['menu_name' => 'List CO', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'list-co', 'menu_url' => '/list-co', 'parent_id' => $menuIds[6], 'base_menu_access_for' => 'ADMIN'], // 39
        ];

        // Insert child menus
        foreach ($dataToInsert as $data) {
            \App\Models\MenuAccess::create($data);
        }
    }
}

