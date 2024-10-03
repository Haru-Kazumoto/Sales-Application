<template>
    <n-notification-provider>
        <n-modal-provider>
            <n-message-provider>
                <n-layout style="height: 100vh">
                    <n-layout-header style="height: 64px;" bordered>
                        <Header />
                    </n-layout-header>
                    <n-layout position="absolute" style="top: 64px;" has-sider>
                        <n-layout-sider :native-scrollbar="false" bordered collapse-mode="width"
                            :collapsed-width="computedCollapsedWidth" :width="270" :collapsed="collapsed"
                            @collapse="collapsed = true" @expand="collapsed = false" show-trigger class="z-3">
                            <n-menu :collapsed="collapsed" :collapsed-width="computedCollapsedWidth"
                                :collapsed-icon-size="22" :options="menuOptions" :render-label="renderMenuLabel"
                                :expand-icon="expandIcon" default-value="dashboard" />
                        </n-layout-sider>
                        <n-layout :native-scrollbar="false">
                            <div class="container-fluid flex-grow-1 min-vh-100 z-n1" style="background-color: #EEF8F5;">
                                <slot />
                            </div>
                            <n-layout-footer position="absolute" bordered>
                                <Footer />
                            </n-layout-footer>
                        </n-layout>
                    </n-layout>

                </n-layout>
            </n-message-provider>
        </n-modal-provider>
    </n-notification-provider>
</template>

<script lang="ts">
import Sidebar from '../Components/Sidebar.vue';
import Header from '../Components/Header.vue';
import Footer from '../Components/Footer.vue';
import { defineComponent, inject, computed, h, ref, provide, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { NIcon } from 'naive-ui';
import type { MenuOption } from 'naive-ui'
import { AppsOutline, CartOutline, PersonOutline, CaretDownOutline, PeopleOutline } from '@vicons/ionicons5';
import { renderIcon, roleMenus } from '../Utils/role-menu.utils';

export default defineComponent({
    name: "AppLayout",
    components: {
        Sidebar,
        Header,
        Footer
    },
    setup() {
        const collapsed = ref(false);
        const page = usePage();
        const role = (page.props.auth as any).user.division.division_name;
        const windowWidth = ref(window.innerWidth);

        // Function to toggle the sidebar collapse state
        function toggleCollapse() {
            collapsed.value = !collapsed.value;
        }

        // Provide state and toggle function to child components
        provide('collapsed', collapsed);
        provide('toggleCollapse', toggleCollapse);

        // const collapsed = inject('collapsed');

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
            toggleCollapse,
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