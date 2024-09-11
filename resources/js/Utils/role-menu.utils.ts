import { SkullOutline, CartOutline,FileTrayFullOutline,FileTrayStackedOutline,CubeOutline, WalletOutline,NotificationsOutline, PeopleOutline, ReceiptOutline,SettingsOutline, BarChartOutline, BagCheckOutline, DocumentsOutline, DocumentTextOutline, ShieldCheckmarkOutline, RepeatSharp  , WarningOutline, Cart } from '@vicons/ionicons5';
import { NIcon } from 'naive-ui';
import {h} from 'vue';

// Utility function to render icons safely (without wrapping twice)
export function renderIcon(icon) {
    return () => h(NIcon, null, { default: () => h(icon) });
}

// Example menu based on role
export const roleMenus = {
    ADMIN: [
        { label: 'Sales Reports', key: 'sales-reports', icon: BarChartOutline, href: '/admin/sales-reports' },
        { label: 'Customers', key: 'customers', icon: PeopleOutline, href: '/admin/customers' },
        { label: 'Products', key: 'products', icon: CartOutline, href: '/admin/products' }
    ],
    FINANCE: [
        {
            label: 'Pembelian',
            key: 'profile',
            icon: BagCheckOutline,
            children: [
                {
                    label: 'Buat PO',
                    key: 'create-po',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/finance/create-po',
                },
                {
                    label: 'List PO',
                    key: 'list-po',
                    icon: renderIcon(DocumentsOutline),
                    href: '/finance/list-po',
                }
            ]
        },
        {
            label: 'Penerimaan Barang',
            key: 'item-receives',
            icon: SettingsOutline,
            children: [
                {
                    label: 'Buat SO',
                    key: 'create-so',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/finance/create-so',
                },
                {
                    label: 'List SO',
                    key: 'list-so',
                    icon: renderIcon(DocumentsOutline),
                    href: '/finance/list-so',
                }
            ]
        },
        {
            label: 'Tagihan',
            key: 'invoices',
            icon: ReceiptOutline,
            children: [
                {
                    label: 'Aging',
                    key: 'aging',
                    icon: renderIcon(NotificationsOutline),
                    href: '/finance/aging',
                },
                {
                    label: 'Tagihan',
                    key: 'invoice',
                    icon: renderIcon(WalletOutline),
                    href: '/finance/invoices'
                }
            ]
        },
        // {
        //     label: 'Klaim Promo',
        //     key: 'claim-promo',
        //     icon: GiftOutline,
        //     href: '/finance/claim-promo'
        // }
    ],
    PROCUREMENT: [
        {
            label: 'Pembelian',
            icon: BagCheckOutline,
            children: [
                {
                    label: 'Buat PO',
                    key: 'create-po',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/procurement/purchase-order',
                },
                {
                    label: 'List PO',
                    key: 'list-po',
                    icon: renderIcon(DocumentsOutline),
                    href: '/procurement/list-po',
                }
            ]
        },
        {
            label: 'Penerimaan Barang',
            icon: SettingsOutline,
            children: [
                {
                    label: 'Buat SO',
                    key: 'create-so',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/procurement/sales-order',
                },
                {
                    label: 'List SO',
                    key: 'list-so',
                    icon: renderIcon(DocumentsOutline),
                    href: '/procurement/list-so',
                }
            ]
        },
        {
            label: 'Transaksi',
            icon: ReceiptOutline,
            children: [
                {
                    label: 'Aging',
                    key: 'aging',
                    icon: renderIcon(NotificationsOutline),
                    href: '/procurement/aging',
                },
                {
                    label: 'Transaksi',
                    key: 'transaction',
                    icon: renderIcon(WalletOutline),
                    href: '/procurement/transaction-list'
                }
            ]
        },
    ],
    WAREHOUSE: [
        {
            label: "Barang Masuk",
            icon: CubeOutline,
            key: 'barang-masuk',
            href: '/warehouse/incoming-item'
        },
        {
            label: "Gudang DNP",
            icon: FileTrayStackedOutline,
            children: [
                {
                    label: 'Stok',
                    key: 'stock',
                    icon: renderIcon(FileTrayFullOutline),
                    href: '/warehouse/stocks'
                },
                {
                    label: "List barang expired",
                    key: 'items-expired',
                    icon: renderIcon(ShieldCheckmarkOutline),
                    href: '/warehouse/expired-items-list'
                },
                {
                    label: "Pemusnahan",
                    key: 'items-destroyed',
                    icon: renderIcon(SkullOutline),
                    href: '/warehouse/items-destroyed'
                },
                {
                    label: "Retur Barang",
                    key: 'return-items',
                    icon: renderIcon(RepeatSharp),
                    href: '/warehouse/return-items',
                }
            ]
        },
        {
            label: "Gudang DKU",
            icon: CartOutline,
            children: [],
        },
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: CartOutline,
        }
    ]
};