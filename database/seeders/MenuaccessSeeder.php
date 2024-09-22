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
        $dataToInsert = [
            ['menu_name' => 'Sales Report', 'menu_icon' => 'BarChartOutline', 'menu_key' => 'sales-reports', 'menu_url' => '/admin/sales-reports'],
            ['menu_name' => 'User Management', 'menu_icon' => 'PeopleOutline', 'menu_key' => 'user-management', 'menu_url' => '/admin/user-management'],
            ['menu_name' => 'Pembelian', 'menu_icon' => 'BagCheckOutline', 'menu_key' => 'profile', 'menu_url' => ''],
            ['menu_name' => 'Buat PO', 'menu_icon' => 'DocumentTextOutline', 'menu_key' => 'create-po', 'menu_url' => '/finance/create-po'],
            ['menu_name' => 'List PO', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'list-po', 'menu_url' => '/finance/list-po'],
            ['menu_name' => 'Penerimaan Barang', 'menu_icon' => 'SettingsOutline', 'menu_key' => 'item-receives', 'menu_url' => ''],
            ['menu_name' => 'Buat SO', 'menu_icon' => 'DocumentTextOutline', 'menu_key' => 'create-so', 'menu_url' => '/finance/create-so'],
            ['menu_name' => 'List SO', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'list-so', 'menu_url' => '/finance/list-so'],
            ['menu_name' => 'Tagihan', 'menu_icon' => 'ReceiptOutline', 'menu_key' => 'invoices', 'menu_url' => ''],
            ['menu_name' => 'Aging', 'menu_icon' => 'NotificationsOutline', 'menu_key' => 'aging', 'menu_url' => '/finance/aging'],
            ['menu_name' => 'Tagihan', 'menu_icon' => 'WalletOutline', 'menu_key' => 'invoice', 'menu_url' => '/finance/invoices'],
            ['menu_name' => 'Pembelian', 'menu_icon' => 'BagCheckOutline', 'menu_key' => 'purchasing', 'menu_url' => ''],
            ['menu_name' => 'Buat PO', 'menu_icon' => 'DocumentTextOutline', 'menu_key' => 'create-po', 'menu_url' => '/procurement/purchase-order'],
            ['menu_name' => 'List PO', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'list-po', 'menu_url' => '/procurement/purchase-orders'],
            ['menu_name' => 'Penerimaan Barang', 'menu_icon' => 'SettingsOutline', 'menu_key' => 'receiving-item', 'menu_url' => ''],
            ['menu_name' => 'Buat SO', 'menu_icon' => 'DocumentTextOutline', 'menu_key' => 'create-so', 'menu_url' => '/procurement/sales-order'],
            ['menu_name' => 'List SO', 'menu_icon' => 'DocumentsOutline', 'menu_key' => 'list-so', 'menu_url' => '/procurement/list-so'],
            ['menu_name' => 'Transaksi', 'menu_icon' => 'ReceiptOutline', 'menu_key' => 'transaction', 'menu_url' => ''],
            ['menu_name' => 'Aging', 'menu_icon' => 'NotificationsOutline', 'menu_key' => 'aging', 'menu_url' => '/procurement/aging'],
            ['menu_name' => 'Transaksi', 'menu_icon' => 'WalletOutline', 'menu_key' => 'transaction', 'menu_url' => '/procurement/transaction-list'],
            ['menu_name' => 'Barang Masuk', 'menu_icon' => 'CubeOutline', 'menu_key' => 'barang-masuk', 'menu_url' => '/warehouse/incoming-item'],
            ['menu_name' => 'Gudang DNP', 'menu_icon' => 'FileTrayStackedOutline', 'menu_key' => 'gudang-dnp', 'menu_url' => ''],
            ['menu_name' => 'Stok', 'menu_icon' => 'FileTrayFullOutline', 'menu_key' => 'stock', 'menu_url' => '/warehouse/stocks'],
            ['menu_name' => 'List barang expired', 'menu_icon' => 'ShieldCheckmarkOutline', 'menu_key' => 'items-expired', 'menu_url' => '/warehouse/expired-items-list'],
            ['menu_name' => 'Pemusnahan', 'menu_icon' => 'SkullOutline', 'menu_key' => 'items-destroyed', 'menu_url' => '/warehouse/items-destroyed'],
            ['menu_name' => 'Retur Barang', 'menu_icon' => 'RepeatSharp', 'menu_key' => 'return-items', 'menu_url' => '/warehouse/return-item'],
            ['menu_name' => 'Gudang DKU', 'menu_icon' => 'CartOutline', 'menu_key' => 'gudang-dku', 'menu_url' => ''],
            ['menu_name' => 'Surat Jalan', 'menu_icon' => 'CartOutline', 'menu_key' => 'travel-document', 'menu_url' => ''],
        ];

        foreach($dataToInsert as $data) 
        {
            \App\Models\MenuAccess::create($data);
        }
    }
}
