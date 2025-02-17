<template>
    <n-button text @click="handleGoPrevious" class="align-self-start mt-auto">
        <template #icon>
            <n-icon :component="IosArrowLtr24Filled" />
        </template>
        KEMBALI
    </n-button>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { router } from '@inertiajs/vue3';
import { IosArrowLtr24Filled } from '@vicons/fluent';

export default defineComponent({
    name: "PreviousButton",
    props: {
        route: {
            type: String,
            required: true,
            default: "#"
        }
    },
    setup (props) {
        function handleGoPrevious() {
            router.visit(route(props.route));
        }

        return {
            handleGoPrevious,
            IosArrowLtr24Filled
        }
    }
})
</script>

<style scoped>

</style>