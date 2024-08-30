<template>
    <n-layout-sider bordered collapse-mode="width" :collapsed-width="64" :width="270" :collapsed="collapsed"
        @collapse="collapsed = true" @expand="collapsed = false">
        <n-menu :collapsed="collapsed" :collapsed-width="64" :collapsed-icon-size="22" :options="menuOptions"
            :render-label="renderMenuLabel" :render-icon="renderMenuIcon" :expand-icon="expandIcon"
            default-value="dashboard" />
    </n-layout-sider>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue';
import { NIcon } from 'naive-ui';
import { AppsOutline, CartOutline, PeopleOutline, SettingsOutline, BarChartOutline, ClipboardOutline, CalendarOutline, DocumentOutline, CaretDownOutline } from '@vicons/ionicons5';
import { Link } from '@inertiajs/vue3';

const menuOptions = [
    {
        label: 'Dashboard',
        key: 'dashboard',
        // href: '/admin/dashboard',
        icon: AppsOutline
    },
    {
        label: 'Customer Order',
        key: 'purchase-order',
        icon: DocumentOutline,
    },
    {
        label: 'Products',
        key: 'products',
        icon: CartOutline,
    },
    {
        label: 'Customers',
        key: 'customers',
        icon: PeopleOutline,
    },
    {
        label: 'Sales Reports',
        key: 'sales-reports',
        icon: BarChartOutline,
    },
    {
        label: 'Travel Document',
        key: 'travel-document',
        icon: ClipboardOutline,
    },
    {
        label: 'Transaction',
        key: 'transaction',
        icon: CalendarOutline,
    },
    {
        label: 'Settings',
        key: 'settings',
        icon: SettingsOutline,
    }
]

export default defineComponent({
    name: "Sidebar",
    setup() {
        const collapsed = ref(false);

        return {
            menuOptions,
            collapsed,
            renderMenuLabel(option) {
                return h(Link, { href: option.href }, () => option.label)
            },
            renderMenuIcon(option) {
                return option.icon ? h(NIcon, null, { default: () => h(option.icon) }) : null
            },
            expandIcon() {
                return h(NIcon, null, { default: () => h(CaretDownOutline) })
            }
        }
    }
})
</script>