import {
    BoxCheckmark20Regular,
    BoxMultiple20Regular,
    DocumentAdd20Regular,
    DocumentAdd28Regular,
    DocumentBulletListMultiple20Regular,
    DocumentTableTruck24Regular,
    HomePerson24Regular,
    PeopleList24Regular,
    PeopleProhibited20Regular,
    PeopleSettings24Regular,
    PeopleSwap16Regular,
    PeopleTeam28Regular,
    PersonAccounts24Regular,
    ReceiptCube24Regular,
    Textbox16Regular,
    VehicleTruckProfile20Regular,
    PeopleAudience24Regular,
    DocumentTextToolbox24Regular,
    DocumentBulletListMultiple24Regular,
    TrayItemRemove24Regular,
    Map24Regular,
    BoxArrowUp20Regular,
    VehicleTruckBag20Regular
} from '@vicons/fluent';
import {
    CartOutline,
    FileTrayFullOutline,
    PeopleOutline,
    ReceiptOutline,
    SettingsOutline,
    BarChartOutline,
    BagCheckOutline,
    DocumentsOutline,
    DocumentTextOutline,
    PersonAddOutline,
    DocumentOutline,
    LogoWhatsapp,
    Close,
} from '@vicons/ionicons5';
import { WarehouseOutlined } from '@vicons/material';
import { iconDark, NIcon } from 'naive-ui';
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
                    icon: renderIcon(PeopleSettings24Regular),
                    href: '/role-management',
                },
                {
                    label: 'Divisi',
                    key: 'division',
                    icon: renderIcon(PeopleSwap16Regular),
                    href: '/division-management'
                },
                {
                    label: 'Akses Menu',
                    key: 'menu-access',
                    icon: renderIcon(PeopleProhibited20Regular),
                    href: '/menu-access-management'
                }
            ]
        },
        {
            label: "Customer",
            key: 'customer',
            icon: PeopleTeam28Regular,
            href: '/customer-management',
        },
        {
            label: "Supplier",
            key: 'supplier',
            icon: HomePerson24Regular,
            href: '/supplier-management',
        },
        // {
        //     label: "Satuan",
        //     key: 'units',
        //     icon: Box20Regular,
        //     href: '/unit-management',
        // },
        {
            label: "Products",
            key: 'products',
            icon: CartOutline,
            href: '/product-management',
        },
        {
            label: "Transports",
            key: 'transports',
            icon: VehicleTruckProfile20Regular,
            href: '/transport-management',
        },
        {
            label: "Komponen Harga",
            key: 'price-component',
            icon: DocumentTextOutline,
            children: [
                {
                    label: "Harga Wilayah Pengiriman",
                    key: "shipping-price",
                    icon: renderIcon(VehicleTruckBag20Regular),
                    href: '/region-delivery'
                },
                {
                    label: "Unsur Harga Tetap",
                    key: "fixed-price",
                    icon: renderIcon(DocumentTextOutline),
                    href: '/global-element-prices'
                },
                {
                    label: "Harga Dimensi Produk",
                    key: "overhead-price",
                    icon: renderIcon(DocumentTextOutline),
                    href: '/dimentions'
                }
            ]
        },
        {
            label: "Driver",
            key: "drivers",
            icon: PersonAccounts24Regular,
            href: "/driver-management"
        },
        // {
        //     label: "Gudang",
        //     key: "warehouses",
        //     icon: WarehouseOutlined,
        //     href: "/storehouse-management"
        // },
        {
            label: "Program Promo Jual",
            key: "promo-program",
            icon: Textbox16Regular,
            href: "/promo-program",
        },
        {
            label: "Program Alokasi SFM",
            key: "promo-program-purchase",
            icon: DocumentTextToolbox24Regular,
            href: "/trade-promo",
        },
        {
            label: "Customer sales",
            key: "customer-sales",
            icon: PeopleList24Regular,
            href: "/customer-sales"
        },
        {
            label: "Wilayah Customer",
            key: "customer-region",
            icon: Map24Regular,
            href: "/customer-region",
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
        {
            label: "List Faktur",
            key: "list-invoice",
            href: '/list-invoices',
            icon: renderIcon(ReceiptCube24Regular),
        },
        {
            label: 'Approval CO',
            key: 'process-customer-order',
            href: "/process-co",
            icon: renderIcon(DocumentBulletListMultiple24Regular),
        }
    ],
    INVOICEIST: [
        {
            label: "Faktur DNP",
            key: "invoice-dnp",
            href: '/invoice-dnp',
            icon: renderIcon(ReceiptOutline),
        },
        {
            label: "Faktur DKU",
            key: "invoice-dku",
            href: '/invoice-dku',
            icon: renderIcon(ReceiptOutline),
        },
        {
            label: "List Faktur",
            key: "list-invoice",
            href: '/list-invoices',
            icon: renderIcon(ReceiptCube24Regular),
        },
    ],
    CASHIER: [
        {
            label: "List Faktur",
            key: "list-invoice",
            href: '/list-invoices',
            icon: renderIcon(ReceiptCube24Regular),
        },
    ],
    AGING_FINANCE: [
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
                    label: 'Approval CO',
                    key: 'process-customer-order',
                    href: "/process-co",
                    icon: renderIcon(DocumentBulletListMultiple24Regular),
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
            label: "Customer",
            key: 'customer',
            icon: PeopleTeam28Regular,
            href: '/customer-management',
        },
        {
            label: "Customer sales",
            key: "customer-sales",
            icon: PeopleList24Regular,
            href: "/customer-sales"
        },
        // {
        //     label: "Test Setting Pesan WA",
        //     key: 'whatsapp_reminder_test',
        //     icon: renderIcon(LogoWhatsapp),
        //     href: "/test-send-message",
        // }
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
                    icon: renderIcon(DocumentAdd28Regular),
                    href: '/purchase-order',
                },
                {
                    label: 'List PO',
                    key: 'list-po',
                    icon: renderIcon(DocumentsOutline),
                    href: '/purchase-orders',
                },
                {
                    label: "Set Nomor Polisi",
                    key: "number-plate-set",
                    icon: renderIcon(DocumentTableTruck24Regular),
                    href: '/po-set-number-plate'
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
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: DocumentTextOutline,
            children: [
                {
                    label: "Buat Surat Jalan",
                    key: 'travel-document',
                    icon: renderIcon(DocumentAdd20Regular),
                    href: '/do-travel-document',
                },
                {
                    label: "List Surat Jalan",
                    key: 'list-travel-document',
                    icon: renderIcon(DocumentBulletListMultiple20Regular),
                    href: '/index-do-travel-document',
                },
            ]
        },
        {
            label: "Program Alokasi SFM",
            key: "promo-program-purchase",
            icon: DocumentTextToolbox24Regular,
            href: "/trade-promo",
        },
        
        {
            label: "Transports",
            key: 'transports',
            icon: VehicleTruckProfile20Regular,
            href: '/transport-management',
        },
        // {
        //     label: "Pengiriman Barang Bertahap",
        //     key: 'gradual-delivery',
        //     icon: BoxArrowUp20Regular,
        //     href: "/gradual-delivery",
        // },
        {
            label: "Stok Gudang Bekasi",
            icon: BoxMultiple20Regular,
            key: 'stock-goods',
            href: '/stock-goods'
        },
    ],
    WAREHOUSE: [
        {
            label: "Barang Masuk",
            icon: BoxCheckmark20Regular,
            key: 'barang-masuk',
            href: '/incoming-item'
        },
        {
            label: "Stok Gudang Bekasi",
            icon: BoxMultiple20Regular,
            key: 'stock-goods',
            href: '/stock-goods'
        },
        {
            label: "Barang Pesanan",
            icon: TrayItemRemove24Regular,
            key: "pesanan",
            href: "/items-out",
        },
        // {
        //     label: "Pengiriman Bertahap",
        //     icon: VehicleTruckCube24Regular,
        //     key: "gradual-delivery",
        //     href: "/gradual-delivery",
        // },
        // {
        //     label: 'Booking Barang',
        //     icon: BoxEdit20Regular  ,
        //     key: 'booking-items',
        //     href: '/booking-requests',
        // },
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: DocumentTextOutline,
            children: [
                {
                    label: "Buat Surat Jalan",
                    key: 'travel-document',
                    icon: renderIcon(DocumentAdd20Regular),
                    href: '/travel-document',
                },
                {
                    label: "List Surat Jalan",
                    key: 'list-travel-document',
                    icon: renderIcon(DocumentBulletListMultiple20Regular),
                    href: '/index-travel-document',
                },
            ]
        },
        {
            label: "Gudang DNP",
            icon: WarehouseOutlined,
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
            icon: WarehouseOutlined,
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
        // {
        //     label: "Barang Rusak",
        //     icon: BoxDismiss24Regular,
        //     children: [
        //         {
        //             label: "Retur Barang Rusak",
        //             icon: renderIcon(RepeatSharp),
        //             key: 'return-broken-goods',
        //             href: '/return-broken-goods',
        //         },
        //         {
        //             label: "Pemusnahan Barang",
        //             icon: renderIcon(TrashOutline),
        //             key: 'destruction-goods',
        //             href: '/destruction-broken-goods',
        //         }
        //     ],
        // },\

    ],
    SALES: [
        {
            label: 'Buat Customer Order DNP',
            icon: renderIcon(DocumentOutline),
            key: 'create-co',
            href: '/create-co-dnp',
        },
        {
            label: 'Buat Customer Order DKU',
            icon: renderIcon(DocumentOutline),
            key: 'create-co-dku',
            href: '/create-co-dku'
        },
        {
            label: 'Daftar Customer Order',
            icon: renderIcon(DocumentsOutline),
            key: 'list-co',
            href: '/list-co',
        }
        // {
        //     label: "Booking Barang",
        //     key: "booking-item",
        //     icon: BoxEdit20Regular,
        //     // href: "/booking-item",
        //     children: [
        //         {
        //             label: "Booking Barang DNP",
        //             key: "booking-item-dnp",
        //             icon: renderIcon(Box24Regular),
        //             href: "/booking-item/dnp",
        //         },
        //         {
        //             label: "Booking Barang DKU",
        //             key: "booking-item-dku",
        //             icon: renderIcon(Box24Regular),
        //             href: "/booking-item/dku",
        //         },
        //     ]
        // }
    ],
    MARKETING: [
        //marketing
        {
            label: "Marketing Reports",
            key: "marketing-reports",
            icon: BarChartOutline,
            href: '/marketing-reports',
        },
        {
            label: "Target Salesman",
            key: "salesman-target",
            icon: PeopleAudience24Regular,
            href: "/salesman-target",
        },
        {
            label: "Draf CO",
            key: "draf-co",
            icon: DocumentBulletListMultiple20Regular,
            href: "/draf-co",
        },
        {
            label: "Customer Order Office",
            key: "co-office",
            icon: DocumentTextOutline,
            href: '/create-co-office',
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
                // {
                //     label: 'Approval CO',
                //     key: 'process-customer-order',
                //     href: "/process-co",
                //     icon: renderIcon(DocumentBulletListMultiple24Regular),
                // }
            ]
        },
        {
            label: "Setting Pesan WA",
            key: 'whatsapp_reminder',
            icon: renderIcon(LogoWhatsapp),
            href: "/whatsapp-message"
        },
        //FAKTURIS
        { key: 'role-label', label: "FAKTURIS", disabled: true },
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
            label: "Faktur DNP",
            key: "invoice-dnp",
            href: '/invoice-dnp',
            icon: renderIcon(ReceiptOutline),
        },
        {
            label: "Faktur DKU",
            key: "invoice-dku",
            href: '/invoice-dku',
            icon: renderIcon(ReceiptOutline),
        },
        {
            label: "List Faktur",
            key: "list-invoice",
            href: '/list-invoices',
            icon: renderIcon(ReceiptCube24Regular),
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
            label: 'Buat Customer Order DNP',
            icon: renderIcon(DocumentOutline),
            key: 'create-co',
            href: '/create-co-dnp',
        },
        {
            label: 'Buat Customer Order DKU',
            icon: renderIcon(DocumentOutline),
            key: 'create-co-dku',
            href: '/create-co-dku'
        },
        {
            label: 'Daftar Customer Order',
            icon: renderIcon(DocumentsOutline),
            key: 'list-co',
            href: '/list-co',
        },
        // {
        //     label: "Booking Barang",
        //     key: 'booking',
        //     icon: renderIcon(BookmarkOutline),
        // },
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
        {
            label: "List Faktur",
            key: "list-invoice",
            href: '/list-invoices',
            icon: renderIcon(ReceiptCube24Regular),
        },
        // {
        //     label: 'Approval CO',
        //     key: 'process-customer-order',
        //     href: "/process-co",
        //     icon: renderIcon(DocumentBulletListMultiple24Regular),
        // },
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
            icon: BoxCheckmark20Regular,
            key: 'barang-masuk',
            href: '/incoming-item'
        },
        {
            label: "Stok Gudang Bekasi",
            icon: BoxMultiple20Regular,
            key: 'stock-goods',
            href: '/stock-goods'
        },
        {
            label: "Barang Pesanan",
            icon: TrayItemRemove24Regular,
            key: "pesanan",
            href: "/items-out",
        },
        // {
        //     label: "Pengiriman Bertahap",
        //     icon: VehicleTruckCube24Regular,
        //     key: "gradual-delivery",
        //     href: "/gradual-delivery",
        // },
        // {
        //     label: 'Booking Barang',
        //     icon: BoxEdit20Regular  ,
        //     key: 'booking-items',
        //     href: '/booking-requests',
        // },
        {
            label: "Surat Jalan",
            key: 'travel-document',
            icon: DocumentTextOutline,
            children: [
                {
                    label: "Buat Surat Jalan",
                    key: 'travel-document',
                    icon: renderIcon(DocumentAdd20Regular),
                    href: '/travel-document',
                },
                {
                    label: "List Surat Jalan",
                    key: 'list-travel-document',
                    icon: renderIcon(DocumentBulletListMultiple20Regular),
                    href: '/index-travel-document',
                },
            ]
        },
        {
            label: "Gudang DNP",
            icon: WarehouseOutlined,
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
            icon: WarehouseOutlined,
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
    ]
};