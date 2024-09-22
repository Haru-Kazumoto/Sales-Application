import { SkullOutline, CartOutline,FileTrayFullOutline,FileTrayStackedOutline,CubeOutline, WalletOutline,NotificationsOutline, PeopleOutline, ReceiptOutline,SettingsOutline, BarChartOutline, BagCheckOutline, DocumentsOutline, DocumentTextOutline, ShieldCheckmarkOutline, RepeatSharp  , WarningOutline, Cart, People, PersonAddOutline, Contract, Analytics, CogOutline, DocumentOutline, PushOutline, ShieldHalf, PieChartOutline, ReorderFourSharp, CartSharp, KeyOutline } from '@vicons/ionicons5';
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
        { 
            label: 'User Management', 
            key: 'user-management', 
            icon: PeopleOutline, 
            children: [
                {
                    label: "User",
                    key: 'user',
                    icon: renderIcon(PersonAddOutline),
                    href: '/admin/user-management',
                },
                {
                    label: "Role",
                    key: 'role',
                    icon: renderIcon(Contract),
                    href: '/admin/role-management',
                },
                {
                    label: 'Divisi',
                    key: 'division',
                    icon: renderIcon(Analytics),
                    href: '/admin/division-management'
                },
                {
                    label: 'Akses Menu',
                    key: 'menu-access',
                    icon: renderIcon(CogOutline),
                    href: '/admin/menu-access-management'
                }
            ]
        },
    ],
    FINANCE: [
        {
            label: 'Klaim',
            key: 'claim',
            icon: DocumentOutline,
            children: [
                {
                    label: 'Klaim Promo',
                    key: 'claim-promo',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/finance/claim-promo',
                },
                {
                    label: 'List Klaim Promo',
                    key: 'claim-promo-list',
                    icon: renderIcon(DocumentsOutline),
                    href: '/finance/claim-promo-list',
                }
            ]
        },
    ],
    AGING_FINANCE: [
        {
            label: "Penjualan",
            key: "sales",
            icon: renderIcon(PieChartOutline),
            children: [
                {
                    label: "Invoice DNP",
                    key: "invoice-dnp",
                    href: '/aging-finance/invoice-dnp',
                    icon: renderIcon(ReceiptOutline),
                },
                {
                    label: "Invoice DKU",
                    key: "invoice-dku",
                    href: '/aging-finance/invoice-dku',
                    icon: renderIcon(ReceiptOutline),
                },
                {
                    label: "List Invoice",
                    key: "list-invoice",
                    href: '/aging-finance/list-invoices',
                    icon: renderIcon(ReorderFourSharp),
                },
            ],
        },
        {
            label: 'Transaksi',
            key: 'transaction',
            icon: renderIcon(ReceiptOutline),
            children: [
                {
                    label: 'Aging',
                    key: 'aging',
                    href: '/aging-finance/aging',
                    icon: renderIcon(BarChartOutline),
                },
                {
                    label: "List transaksi",
                    key: "list-transaction",
                    href: '/aging-finance/list-transactions',
                    icon: renderIcon(ReorderFourSharp),
                }
            ]
        }
    ],
    PROCUREMENT: [
        {
            label: 'Pembelian',
            key: 'purchasing',
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
                    href: '/procurement/purchase-orders',
                }
            ]
        },
        {
            label: 'Penerimaan Barang',
            key: 'receiving-item',
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
            key: 'transaction',
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
            label: "Stok Gudang",
            icon: FileTrayFullOutline,
            key: 'stock-goods',
            href: '/warehouse/stock-goods'
        },
        {
            label: "Gudang DNP",
            icon: FileTrayStackedOutline,
            key: 'dnp-storehouse',
            children: [
                {
                    label: 'Stok',
                    key: 'stock',
                    icon: renderIcon(FileTrayFullOutline),
                    href: '/warehouse/dnp-stock-goods'
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
                    href: '/warehouse/return-item',
                }
            ]
        },
        {
            label: "Gudang DKU",
            icon: CartOutline,
            key: 'dku-storehouse',
            children: [],
        },
        {
            label: 'Booking Barang',
            icon: CartOutline,
            key: 'booking-items',
            children: [
                {
                    label: "Booking Request",
                    icon: renderIcon(CartOutline),
                    key: 'booking-request',
                    href: '/warehouse/booking-requests',
                },
                {
                    label: "List Booking",
                    icon: renderIcon(CartOutline),
                    key: 'booking-list',
                    href: '/warehouse/list-booking',
                }
            ]
        },
        {
            label: "Barang Rusak",
            icon: PushOutline,
            children: [
                {
                    label: "Retur Barang",
                    icon: renderIcon(ShieldHalf),
                    key: 'retur-barang',
                    href: '/warehouse/return-goods',
                }
            ],
        },
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: CartOutline,
        }
    ],
    SALES: [
        {
            label: "Penjualan",
            key: 'sales',
            icon: CartSharp,
            children : [
                {
                    label: 'Buat CO',
                    icon: renderIcon(DocumentOutline),
                    key: 'create-co',
                    href: '/sales/create-co',
                },
                {
                    label: 'List CO',
                    icon: renderIcon(DocumentsOutline),
                    key: 'list-co',
                    href: '/sales/list-co',
                }
            ]
        }
    ]
};