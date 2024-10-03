<template>
    <n-notification-provider>
        <n-layout style="height: 100vh">
            <n-layout-header style="height: 64px;" bordered>
                <Header />
            </n-layout-header>
            <n-layout position="absolute" style="top: 64px; " has-sider>
                <n-layout-sider content-style="padding: 24px;" :native-scrollbar="false" bordered>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                    <n-h2>Handian Bridge</n-h2>
                </n-layout-sider>
                <n-layout content-style="padding: 24px;" :native-scrollbar="false">
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                    <n-h2>Pingshan Road</n-h2>
                </n-layout>
            </n-layout>
        </n-layout>
    </n-notification-provider>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Header from '../Components/Header.vue';

export default defineComponent({
    setup(){

    },
    layout: null,
    components: {
        Header
    }
});
</script>