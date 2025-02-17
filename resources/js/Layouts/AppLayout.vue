<template>
  <n-config-provider :theme-overrides="themeConfig">
    <n-notification-provider>
      <n-modal-provider>
        <n-message-provider>
          <n-layout style="height: 100vh">
            <n-layout-header style="height: 64px;" bordered>
              <Header />
            </n-layout-header>
            <n-layout has-sider position="absolute" style="top: 64px;">
              <n-layout-sider :native-scrollbar="false" bordered collapse-mode="width" :width="300"
                :collapsed="collapsed" @collapse="collapsed = true" @expand="collapsed = false" :collapsed-width="5"
                show-trigger>
                <n-menu :collapsed="collapsed" :collapsed-width="64" :collapsed-icon-size="22" :options="menuOptions"
                  :render-label="renderMenuLabel" :value="activeMenu" />
              </n-layout-sider>
              <n-layout :native-scrollbar="false">
                <div class="container-fluid flex-grow-1 min-vh-100 z-n1" style="background-color: #EEF8F5;">
                  <slot />
                </div>
              </n-layout>
            </n-layout>
          </n-layout>
        </n-message-provider>
      </n-modal-provider>
    </n-notification-provider>
  </n-config-provider>
</template>

<script lang="ts">
import Sidebar from '../Components/Sidebar.vue';
import Header from '../Components/Header.vue';
import Footer from '../Components/Footer.vue';
import { watch, defineComponent, computed, h, ref, provide, onMounted, onUnmounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { NIcon } from 'naive-ui';
import type { MenuOption, MenuProps } from 'naive-ui';
import { AppsOutline, CaretDownOutline } from '@vicons/ionicons5';
import { renderIcon, roleMenus } from '../Utils/role-menu.utils';
import { themeConfig } from '../Utils/theme-config.utils';

export default defineComponent({
  name: "AppLayout",
  components: {
    Sidebar,
    Header,
    Footer
  },
  setup() {
    const page = usePage();
    const role = (page.props.auth as any).user.division.division_name;
    const collapsed = ref(false);

    // Function to toggle the sidebar collapse state
    function toggleCollapse() {
      collapsed.value = !collapsed.value;
    }

    function saveMenuState(key) {
      localStorage.setItem('activeMenu', key);
    }

    function loadMenuState() {
      return localStorage.getItem('activeMenu') || 'dashboard';
    }

    // Provide state and toggle function to child components
    provide('collapsed', collapsed);
    provide('toggleCollapse', toggleCollapse);

    function handleMenuClick(key: string) {
      // Arahkan ke route yang sesuai menggunakan Inertia.js
      const selectedItem = menuOptions.value.find(item => item.key === key);
      if (selectedItem && selectedItem.href) {
        // Menggunakan Inertia untuk navigasi
        router.visit(selectedItem.href);
      }
    }

    // Fungsi untuk mendeteksi ukuran layar
    function handleResize() {
      const isSmallScreen = window.matchMedia("(max-width: 768px)").matches;
      if (isSmallScreen) {
        collapsed.value = true; // Sider tertutup di layar kecil
      }
    }

    // Pasang listener saat komponen dimuat
    onMounted(() => {
      handleResize(); // Set initial state
      window.addEventListener("resize", handleResize);

      router.on("navigate", handleResize);
    });

    // Hapus listener saat komponen dilepas
    onUnmounted(() => {
      window.removeEventListener("resize", handleResize);
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

    const activeMenu = ref(localStorage.getItem('activeMenu') || 'dashboard');

    // Fungsi rekursif untuk mencari menu aktif
    const findActiveOption = (menuList, path) => {
      for (const menu of menuList) {
        if (menu.href === path) {
          return menu;
        }

        if (menu.children) {
          const childActiveOption = findActiveOption(menu.children, path);
          if (childActiveOption) {
            return childActiveOption;
          }
        }
      }
      return null;
    };

    // Watch untuk mengupdate activeMenu ketika route berubah
    watch(
      () => page.url,
      (newUrl) => {
        const allMenuOptions = menuOptions.value;
        const activeOption = findActiveOption(allMenuOptions, newUrl);

        if (activeOption) {
          activeMenu.value = activeOption.key;
          saveMenuState(activeOption.key);
        }
      },
      { immediate: true } // Jalankan segera setelah komponen dimount
    );

    return {
      collapsed,
      toggleCollapse,
      menuOptions,
      renderMenuLabel(option) {
        // Pastikan href tidak undefined
        if (!option.href) {
          return option.label; // Atau alternatif lain jika href tidak ada
        }

        return h(Link, { href: option.href }, { default: () => option.label });
      },
      expandIcon() {
        return h(NIcon, null, { default: () => h(CaretDownOutline) });
      },
      handleMenuClick,
      activeMenu,
      themeConfig
    };
  }
});
</script>
