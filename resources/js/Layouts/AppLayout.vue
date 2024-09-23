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

                        <n-layout-content class="d-flex flex-column" style="z-index: 10;">
                            <div class="container-fluid flex-grow-1 min-vh-100" style="background-color: #EEF8F5;">
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
import { defineComponent, ref, provide } from 'vue'
import Sidebar from '../Components/Sidebar.vue';
import Header from '../Components/Header.vue';

export default defineComponent({
    name: "AppLayout",
    components: {
        Sidebar,
        Header
    },
    setup() {
        const collapsed = ref(true);

        // Function to toggle the sidebar collapse state
        function toggleCollapse() {
            collapsed.value = !collapsed.value;
        }

        // Provide state and toggle function to child components
        provide('collapsed', collapsed);
        provide('toggleCollapse', toggleCollapse);

        return {
            collapsed,
            toggleCollapse
        };
    }
});
</script>