<template>
    <n-layout-sider bordered collapse-mode="width" :collapsed-width="64" :width="270" :collapsed="collapsed"
        @collapse="collapsed = true" @expand="collapsed = false">
        <n-menu :collapsed="collapsed" :collapsed-width="64" :collapsed-icon-size="22" :options="menuOptions"
            :render-label="renderMenuLabel" :expand-icon="expandIcon" default-value="dashboard" />
    </n-layout-sider>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { NIcon, MenuOption } from 'naive-ui';
import { AppsOutline, CartOutline,PersonOutline,GiftOutline, WalletOutline,NotificationsOutline, PeopleOutline, ReceiptOutline,SettingsOutline, BarChartOutline, BagCheckOutline, DocumentsOutline, DocumentTextOutline, CaretDownOutline } from '@vicons/ionicons5';

// Utility function to render icons safely (without wrapping twice)
function renderIcon(icon) {
    return () => h(NIcon, null, { default: () => h(icon) });
}

// Example menu based on role
const roleMenus = {
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
        {
            label: 'Klaim Promo',
            key: 'claim-promo',
            icon: GiftOutline,
            href: '/finance/claim-promo'
        }
    ]
};

export default defineComponent({
    name: "Sidebar",
    props: {
        // User role prop received from Laravel Inertia
        userRole: {
            type: String,
            required: true
        }
    },
    setup(props) {
        const collapsed = ref(false);
        const role = "FINANCE";

        // Static menu options (Dashboard, UMUM, Settings)
        const staticMenuOptions: MenuOption[] = [
            {
                label: 'Dashboard',
                key: 'dashboard',
                icon: renderIcon(AppsOutline),
                href: '/dashboard'
            },
            {
                label: 'UMUM',
                key: 'common',
                disabled: true
            },
            {
                key: 'divider-2',
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
                label: 'Products',
                key: 'products',
                icon: renderIcon(CartOutline),
                href: 'finance/products'
            },
            {
                label: 'Customers',
                key: 'customers',
                icon: renderIcon(PersonOutline),
                hred: 'finance/customers'
            }
        ];

        // Dynamically generate menu options based on user role
        const menuOptions = computed(() => {
            const roleMenu = roleMenus[role] || []; // Get role menu
            return [
                ...staticMenuOptions.slice(0, 1), // Add "Dashboard"
                { key: 'role-label', label: role, disabled: true }, // Label showing user role
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
                }, // Divider after role label
                ...roleMenu.map(item => ({
                    ...item,
                    icon: renderIcon(item.icon) // Render icon for each item
                })), // Role-specific menus
                ...staticMenuOptions.slice(1) // Add the rest (UMUM, Settings)
            ];
        });

        return {
            collapsed,
            menuOptions,
            renderMenuLabel(option) {
                return h(Link, { href: option.href }, () => option.label);
            },
            expandIcon() {
                return h(NIcon, null, { default: () => h(CaretDownOutline) });
            }
        };
    }
});
</script>