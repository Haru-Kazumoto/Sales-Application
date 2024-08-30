<template>
    <n-notification-provider>
        <n-modal-provider>
            <n-message-provider>
                <n-layout>
                    <n-layout-header bordered>
                        <Header />
                    </n-layout-header>

                    <n-layout has-sider class="flex-grow-1">
                        <Sidebar />

                        <n-layout-content class="d-flex flex-column">
                            <div class="px-4 py-2 flex-grow-1 min-vh-100" style="background-color: #EEF8F5;">
                                <slot />
                            </div>
                        </n-layout-content>
                    </n-layout>
                </n-layout>
            </n-message-provider>
        </n-modal-provider>
    </n-notification-provider>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Sidebar from '../Components/Sidebar.vue';
import Header from '../Components/Header.vue';

export default defineComponent({
    name: "AppLayout",
    components: {
        Sidebar,
        Header
    }
})
</script>