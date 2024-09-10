import { AppsOutline, CartOutline,PersonOutline,GiftOutline, WalletOutline,NotificationsOutline, PeopleOutline, ReceiptOutline,SettingsOutline, BarChartOutline, BagCheckOutline, DocumentsOutline, DocumentTextOutline, CaretDownOutline } from '@vicons/ionicons5';
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
            key: 'profile',
            icon: BagCheckOutline,
            children: [
                {
                    label: 'Buat PO',
                    key: 'create-po',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/procurement/create-po',
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
            key: 'item-receives',
            icon: SettingsOutline,
            children: [
                {
                    label: 'Buat SO',
                    key: 'create-so',
                    icon: renderIcon(DocumentTextOutline),
                    href: '/procurement/create-so',
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
                    label: 'Tagihan',
                    key: 'invoice',
                    icon: renderIcon(WalletOutline),
                    href: '/procurement/invoices'
                }
            ]
        },
    ]
};