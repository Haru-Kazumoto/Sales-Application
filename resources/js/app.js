import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { route, ZiggyVue } from '../../vendor/tightenco/ziggy/dist';
import ApexCharts from 'vue3-apexcharts';
import AppLayout from '@/Layouts/AppLayout.vue';
import { NConfigProvider } from 'naive-ui';
import { themeConfig } from './Utils/theme-config.utils';

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
            .component('apexchart', ApexCharts)
            .mount(el)
    },
})