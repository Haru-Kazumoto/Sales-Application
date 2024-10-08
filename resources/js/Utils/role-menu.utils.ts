import { SkullOutline, CartOutline, FileTrayFullOutline, FileTrayStackedOutline, CubeOutline, WalletOutline, NotificationsOutline, PeopleOutline, ReceiptOutline, SettingsOutline, BarChartOutline, BagCheckOutline, DocumentsOutline, DocumentTextOutline, ShieldCheckmarkOutline, RepeatSharp, WarningOutline, Cart, People, PersonAddOutline, Contract, Analytics, CogOutline, DocumentOutline, PushOutline, ShieldHalf, PieChartOutline, ReorderFourSharp, CartSharp, KeyOutline, LogoWhatsapp, Close, Skull, Bookmarks, BookmarkOutline, AlbumsOutline } from '@vicons/ionicons5';
import { NIcon } from 'naive-ui';
import { h } from 'vue';

// Utility function to render icons safely (without wrapping twice)
export function renderIcon(icon) {
    return () => h(NIcon, null, { default: () => h(icon) });
}

// Example menu based on role
export const roleMenus = {
    ADMIN: [
        {
            label: 'User Management',
            key: 'user-management',
            icon: PeopleOutline,
            children: [
                {
                    label: "User",
                    key: 'user',
                    icon: renderIcon(PersonAddOutline),
                    href: '/user-management',
                },
                {
                    label: "Role",
                    key: 'role',
                    icon: renderIcon(Contract),
                    href: '/role-management',
                },
                {
                    label: 'Divisi',
                    key: 'division',
                    icon: renderIcon(Analytics),
                    href: '/division-management'
                },
                {
                    label: 'Akses Menu',
                    key: 'menu-access',
                    icon: renderIcon(CogOutline),
                    href: '/menu-access-management'
                }
            ]
        },
        {
            label: "Customer",
            key: 'customer',
            icon: PeopleOutline,
            href: '/customer-management',
        },
        {
            label: "Supplier",
            key: 'supplier',
            icon: PeopleOutline,
            href: '/supplier-management',
        },
        {
            label: "Products",
            key: 'products',
            icon: CartOutline,
            href: '/product-management',
        }
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
                    href: '/claim-promo',
                },
                {
                    label: 'List Klaim Promo',
                    key: 'claim-promo-list',
                    icon: renderIcon(DocumentsOutline),
                    href: '/claim-promo-list',
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
                    href: '/invoice-dnp',
                    icon: renderIcon(ReceiptOutline),
                },
                {
                    label: "Invoice DKU",
                    key: "invoice-dku",
                    href: '/invoice-dku',
                    icon: renderIcon(ReceiptOutline),
                },
                {
                    label: "List Invoice",
                    key: "list-invoice",
                    href: '/list-invoices',
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
                    href: '/aging',
                    icon: renderIcon(BarChartOutline),
                },
                {
                    label: "List transaksi",
                    key: "list-transaction",
                    href: '/list-transactions',
                    icon: renderIcon(ReorderFourSharp),
                }
            ]
        },
        {
            label: "Setting Pesan WA",
            key: 'whatsapp_reminder',
            icon: renderIcon(LogoWhatsapp),
            href: "/whatsapp-message"
        },
        {
            label: "Test Setting Pesan WA",
            key: 'whatsapp_reminder_test',
            icon: renderIcon(LogoWhatsapp),
            href: "/test-send-message",
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
                    href: '/purchase-order',
                },
                {
                    label: 'List PO',
                    key: 'list-po',
                    icon: renderIcon(DocumentsOutline),
                    href: '/purchase-orders',
                }
            ]
        },
        {
            label: 'Penerimaan Barang',
            key: 'receiving-item',
            icon: SettingsOutline,
            children: [
                {
                    label: 'Buat SSO',
                    key: 'create-sso',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/sub-sales-order',
                },
                {
                    label: 'List SSO',
                    key: 'list-sso',
                    icon: renderIcon(DocumentsOutline),
                    href: '/sub-sales-orders',
                }
            ]
        },
    ],
    WAREHOUSE: [
        {
            label: "Barang Masuk",
            icon: CubeOutline,
            key: 'barang-masuk',
            href: '/incoming-item'
        },
        {
            label: "Stok Gudang Bekasi",
            icon: FileTrayFullOutline,
            key: 'stock-goods',
            href: '/stock-goods'
        },
        {
            label: 'Booking Barang',
            icon: CartOutline,
            key: 'booking-items',
            href: '/booking-requests',
        },
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: DocumentTextOutline,
            children: [
                {
                    label: "Buat Surat Jalan",
                    key: 'travel-document',
                    icon: renderIcon(DocumentOutline),
                    href: '/travel-document',
                },
                {
                    label: "List Surat Jalan",
                    key: 'list-travel-document',
                    icon: renderIcon(DocumentsOutline),
                    href: '/list-travel-document',
                },
            ]
        },
        {
            label: "Gudang DNP",
            icon: FileTrayStackedOutline,
            key: 'dnp-storehouse',
            children: [
                {
                    label: 'Stok',
                    key: 'dnp-stock',
                    icon: renderIcon(FileTrayFullOutline),
                    href: '/dnp-stock-goods'
                },
                {
                    label: "Barang Expired",
                    key: 'dnp-expired_goods',
                    icon: renderIcon(Close),
                    href: '/dnp-expired-stocks',
                }
            ]
        },
        {
            label: "Gudang DKU",
            icon: FileTrayStackedOutline,
            key: 'dku-storehouse',
            children: [
                {
                    label: 'Stok',
                    key: 'dku-stock',
                    icon: renderIcon(FileTrayFullOutline),
                    href: '/dku-stock-goods'
                },
                {
                    label: "Barang Expired",
                    key: 'dku-expired_goods',
                    icon: renderIcon(Close),
                    href: '/dku-expired-stocks',
                }
            ]
        },
        {
            label: "Barang Rusak",
            icon: PushOutline,
            children: [
                {
                    label: "Retur Barang Rusak",
                    icon: renderIcon(RepeatSharp),
                    key: 'return-broken-goods',
                    href: '/return-broken-goods',
                },
                {
                    label: "Pemusnahan Barang",
                    icon: renderIcon(SkullOutline),
                    key: 'destruction-goods',
                    href: '/destruction-broken-goods',
                }
            ],
        },
    ],
    SALES: [
        {
            label: "Penjualan",
            key: 'sales',
            icon: CartSharp,
            children: [
                {
                    label: 'Buat CO',
                    icon: renderIcon(DocumentOutline),
                    key: 'create-co',
                    href: '/create-co',
                },
                {
                    label: 'List CO',
                    icon: renderIcon(DocumentsOutline),
                    key: 'list-co',
                    href: '/list-co',
                }
            ]
        },
    ],
    MARKETING: [
        //marketing
        
        {
            label: "Marketing Reports",
            key: "marketing-reports",
            icon: BarChartOutline
        },
        {
            label: "Sales Target",
            key: "sales-target",
            icon: PieChartOutline,
        },
        //Aging Sales
        { key: 'role-label', label: "AGING SALES", disabled: true },
        {
            key: 'divider-1',
            type: 'divider',
            props: {
                style: {
                    paddingTop: '2px',
                    borderRadius: '10px',
                    backgroundColor: '#cccccc',
                }
            }
        },
        {
            label: "Transaksi",
            key: "transaction-sales",
            icon: ReceiptOutline,
        },
        //SALES
        { key: 'role-label', label: "SALES", disabled: true },
        {
            key: 'divider-1',
            type: 'divider',
            props: {
                style: {
                    paddingTop: '2px',
                    borderRadius: '10px',
                    backgroundColor: '#cccccc',
                }
            }
        },
        {
            label: "Penjualan",
            key: 'sales',
            icon: CartSharp,
            children: [
                {
                    label: 'Buat CO',
                    icon: renderIcon(DocumentOutline),
                    key: 'create-co',
                    href: '/create-co',
                },
                {
                    label: 'List CO',
                    icon: renderIcon(DocumentsOutline),
                    key: 'list-co',
                    href: '/list-co',
                }
            ]
        },
        {
            label: "Booking Barang",
            key: 'booking',
            icon: renderIcon(BookmarkOutline),
        },
        //PROCUREMENT
        { key: 'role-label', label: "PROCUREMENT", disabled: true },
        {
            key: 'divider-1',
            type: 'divider',
            props: {
                style: {
                    paddingTop: '2px',
                    borderRadius: '10px',
                    backgroundColor: '#cccccc',
                }
            }
        },
        {
            label: 'Pembelian',
            key: 'purchasing',
            icon: BagCheckOutline,
            children: [
                {
                    label: 'Buat PO',
                    key: 'create-po',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/purchase-order',
                },
                {
                    label: 'List PO',
                    key: 'list-po',
                    icon: renderIcon(DocumentsOutline),
                    href: '/purchase-orders',
                }
            ]
        },
        {
            label: 'Penerimaan Barang',
            key: 'receiving-item',
            icon: SettingsOutline,
            children: [
                {
                    label: 'Buat SSO',
                    key: 'create-sso',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/sub-sales-order',
                },
                {
                    label: 'List SSO',
                    key: 'list-sso',
                    icon: renderIcon(DocumentsOutline),
                    href: '/sub-sales-orders',
                }
            ]
        },
        //FINANCE
        { key: 'role-label', label: "FINANCE", disabled: true },
        {
            key: 'divider-1',
            type: 'divider',
            props: {
                style: {
                    paddingTop: '2px',
                    borderRadius: '10px',
                    backgroundColor: '#cccccc',
                }
            }
        },
        {
            label: 'Klaim',
            key: 'claim',
            icon: DocumentOutline,
            children: [
                {
                    label: 'Klaim Promo',
                    key: 'claim-promo',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/claim-promo',
                },
                {
                    label: 'List Klaim Promo',
                    key: 'claim-promo-list',
                    icon: renderIcon(DocumentsOutline),
                    href: '/claim-promo-list',
                }
            ]
        },
        //WAREHOUSE
        { key: 'role-label', label: "WAREHOUSE", disabled: true },
        {
            key: 'divider-1',
            type: 'divider',
            props: {
                style: {
                    paddingTop: '2px',
                    borderRadius: '10px',
                    backgroundColor: '#cccccc',
                }
            }
        },
        {
            label: "Barang Masuk",
            icon: CubeOutline,
            key: 'barang-masuk',
            href: '/incoming-item'
        },
        {
            label: "Stok Gudang Bekasi",
            icon: FileTrayFullOutline,
            key: 'stock-goods',
            href: '/stock-goods'
        },
        {
            label: 'Booking Barang',
            icon: CartOutline,
            key: 'booking-items',
            href: '/booking-requests',
        },
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: DocumentTextOutline,
            children: [
                {
                    label: "Buat Surat Jalan",
                    key: 'travel-document',
                    icon: renderIcon(DocumentOutline),
                    href: '/travel-document',
                },
                {
                    label: "List Surat Jalan",
                    key: 'list-travel-document',
                    icon: renderIcon(DocumentsOutline),
                    href: '/list-travel-document',
                },
            ]
        },
        {
            label: "Gudang DNP",
            icon: FileTrayStackedOutline,
            key: 'dnp-storehouse',
            children: [
                {
                    label: 'Stok',
                    key: 'dnp-stock',
                    icon: renderIcon(FileTrayFullOutline),
                    href: '/dnp-stock-goods'
                },
                {
                    label: "Barang Expired",
                    key: 'dnp-expired_goods',
                    icon: renderIcon(Close),
                    href: '/dnp-expired-stocks',
                }
            ]
        },
        {
            label: "Gudang DKU",
            icon: FileTrayStackedOutline,
            key: 'dku-storehouse',
            children: [
                {
                    label: 'Stok',
                    key: 'dku-stock',
                    icon: renderIcon(FileTrayFullOutline),
                    href: '/dku-stock-goods'
                },
                {
                    label: "Barang Expired",
                    key: 'dku-expired_goods',
                    icon: renderIcon(Close),
                    href: '/dku-expired-stocks',
                }
            ]
        },
        {
            label: "Barang Rusak",
            icon: PushOutline,
            children: [
                {
                    label: "Retur Barang Rusak",
                    icon: renderIcon(RepeatSharp),
                    key: 'return-broken-goods',
                    href: '/return-broken-goods',
                },
                {
                    label: "Pemusnahan Barang",
                    icon: renderIcon(SkullOutline),
                    key: 'destruction-goods',
                    href: '/destruction-broken-goods',
                }
            ],
        },
    ]
};