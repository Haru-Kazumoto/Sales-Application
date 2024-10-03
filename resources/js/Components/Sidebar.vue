<template>
    <n-layout-sider bordered collapse-mode="width" :collapsed-width="computedCollapsedWidth" :width="270"
        :collapsed="collapsed" @collapse="collapsed = true" @expand="collapsed = false" show-trigger>
        <n-menu :collapsed="collapsed" :collapsed-width="computedCollapsedWidth" :collapsed-icon-size="22"
            :options="menuOptions" :render-label="renderMenuLabel" 
            :expand-icon="expandIcon" default-value="dashboard" />
    </n-layout-sider>
</template>

<script lang="ts">
import { defineComponent, inject, computed, h, ref, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { NIcon } from 'naive-ui';
import type { MenuOption } from 'naive-ui'
import { AppsOutline, CartOutline, PersonOutline, CaretDownOutline, PeopleOutline } from '@vicons/ionicons5';
import { renderIcon, roleMenus } from '../Utils/role-menu.utils';

export default defineComponent({
    name: "Sidebar",
    setup() {
        const collapsed = inject('collapsed');
        const page = usePage();
        const role = (page.props.auth as any).user.division.division_name;
        const windowWidth = ref(window.innerWidth);

        const computedCollapsedWidth = computed(() => {
            if (windowWidth.value < 576) return 0;
            if (windowWidth.value < 768) return 64;
            return 64;
        });

        const updateWindowWidth = () => {
            windowWidth.value = window.innerWidth;
        };

        onMounted(() => {
            window.addEventListener('resize', updateWindowWidth);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('resize', updateWindowWidth);
        });

        const staticMenuOptions: MenuOption[] = [
            {
                label: 'Dashboard',
                key: 'dashboard',
                icon: renderIcon(AppsOutline),
                href: `/dashboard-${role.toLowerCase()}`
            },

        ];

        const menuOptions = computed(() => {
            const roleMenu = roleMenus[role] || [];
            return [
                ...staticMenuOptions.slice(0, 1),
                { key: 'role-label', label: role, disabled: true, },
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
                ...roleMenu.map(item => ({
                    ...item,
                    icon: renderIcon(item.icon) // Render icon for each item
                })),
                ...staticMenuOptions.slice(1),
            ];
        });

        return {
            collapsed,
            menuOptions,
            computedCollapsedWidth,
            renderMenuLabel(option) {
                return h(Link, { href: option.href }, { default: () => option.label });
            },
            expandIcon() {
                return h(NIcon, null, { default: () => h(CaretDownOutline) });
            }
        };
    }
});
</script>

<style>
.n-menu-item-content {
    display: flex;
    align-items: center ;
}
</style>