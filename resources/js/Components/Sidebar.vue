<template>
    <n-layout-sider bordered collapse-mode="width" :collapsed-width="computedCollapsedWidth" :width="270" :collapsed="collapsed"
        @collapse="collapsed = true" @expand="collapsed = false">
        <n-menu :collapsed="collapsed" :collapsed-width="computedCollapsedWidth" :collapsed-icon-size="22" :options="menuOptions"
            :render-label="renderMenuLabel" :expand-icon="expandIcon" default-value="dashboard" />
    </n-layout-sider>
</template>

<script lang="ts">
import { defineComponent, inject, computed, h, ref, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { NIcon, MenuOption } from 'naive-ui';
import { AppsOutline, CartOutline, PersonOutline, CaretDownOutline } from '@vicons/ionicons5';
import { renderIcon, roleMenus } from '../Utils/role-menu.utils';

export default defineComponent({
    name: "Sidebar",
    setup() {
        const collapsed = inject('collapsed'); // Inject state for collapsed
        const page = usePage();
        const role = (page.props.auth as any).user.division.division_name;
        const windowWidth = ref(window.innerWidth);

        const computedCollapsedWidth = computed(() => {
            if (windowWidth.value < 576) {
                // Jika lebih kecil dari breakpoint sm (576px)
                return 0
            } else if (windowWidth.value < 768) {
                // Jika lebih besar dari sm tapi lebih kecil dari md (768px)
                return 64
            } else {
                // Jika lebih besar dari md
                return 64
            }
        });

        // Update windowWidth saat ukuran layar berubah
        const updateWindowWidth = () => {
            windowWidth.value = window.innerWidth
        }

        onMounted(() => {
            window.addEventListener('resize', updateWindowWidth)
        })

        onBeforeUnmount(() => {
            window.removeEventListener('resize', updateWindowWidth)
        })

        const staticMenuOptions: MenuOption[] = [
            {
                label: 'Dashboard',
                key: 'dashboard',
                icon: renderIcon(AppsOutline),
                href: `/${role.toLowerCase()}/dashboard`
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
                href: 'finance/customers'
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
                ...staticMenuOptions.slice(1), // Add the rest (UMUM, Settings)
            ];
        });

        return {
            collapsed,
            menuOptions,
            computedCollapsedWidth,
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
