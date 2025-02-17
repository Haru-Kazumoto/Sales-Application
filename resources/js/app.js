import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { route, ZiggyVue } from '../../vendor/tightenco/ziggy/dist';
import ApexCharts from 'vue3-apexcharts';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    // create naive ui
    create,
    // component
    NButton,
    NSelect,
    NInput,
    NLayout,
    NLayoutContent,
    NIcon,
    NImage,
    NFlex,
    NGrid,
    NMenu,
    NModal,
    NBadge,
    NTab,
    NDataTable,
    NAvatar,
    NCard,
    NDrawer,
    NDrawerContent,
    NDatePicker,
    NTooltip,
    NTag,
    NPagination,
    NForm,
    NFormItem,
    NLayoutSider,
    NLayoutHeader,
    NTabPane,
    NNotificationProvider,
    NModalProvider,
    NConfigProvider,
    NMessageProvider,
    NDropdown,
    NIconWrapper
} from 'naive-ui';

const naive = create({
    components: [
        NButton,
        NSelect,
        NInput,
        NLayout,
        NLayoutContent,
        NIcon,
        NImage,
        NFlex,
        NGrid,
        NMenu,
        NModal,
        NBadge,
        NTab,
        NDataTable,
        NAvatar,
        NCard,
        NDrawer,
        NDrawerContent,
        NDatePicker,
        NTooltip,
        NTag,
        NPagination,
        NForm,
        NFormItem,
        NLayoutSider,
        NLayoutHeader,
        NTabPane,
        NNotificationProvider,
        NModalProvider,
        NConfigProvider,
        NMessageProvider,
        NDropdown,
        NIconWrapper
    ]
});

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]

        //configure your layout here..
        page.default.layout = page.default.layout || AppLayout

        // app.config.globalProperties.$route = route

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(naive)
            .component('apexchart', ApexCharts)
            .mount(el)
    },
})