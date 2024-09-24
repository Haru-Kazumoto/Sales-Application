<template>
    <div class="card shadow custom-gradient overflow-hidden h-100" 
         :class="gradientClass" 
         style="border: none;">
        <div class="card-body d-flex flex-column gap-3" style="z-index: 10;">
            <span class="fs-5 fw-medium">{{ title }}</span>
            <span class="fw-semibold fs-3">{{ count }}</span>
            <n-button :type="type" @click="router.visit(route(viewName), {method: 'get'})">Lihat Detail</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';
import {router} from "@inertiajs/vue3";

export default defineComponent({
    name: "SalesCountCard",
    props: {
        title: {
            type: String,
            required: true,
        },
        count: {
            type: Number,
            required: true,
        },
        type: {
            type: String,
            required: true,
            validator: (value: string) => {
                return ['primary', 'warning'].includes(value);
            },
            default: 'primary'
        },
        viewName: {
            type: String,
            required: true,
        }
    },
    setup(props) {
        // Computed property for dynamic gradient class
        const gradientClass = computed(() => {
            return props.type === 'warning' ? 'warning-gradient' : 'default-gradient';
        });

        return {
            gradientClass,
            router
        }
    }
})
</script>

<style scoped>
.custom-gradient {
    position: relative;
}

.default-gradient::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 13rem;
    height: 11rem;
    background: linear-gradient(-20deg, rgba(5, 233, 89, 0.538), rgb(255, 255, 255)); /* Default gradient */
    border-top-left-radius: 100rem;
    z-index: 1;
    pointer-events: none;
}

.warning-gradient::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 13rem;
    height: 11rem;
    background: linear-gradient(-20deg, rgba(255, 193, 7, 0.7), rgb(255, 255, 255)); /* Yellow gradient */
    border-top-left-radius: 100rem;
    z-index: 1;
    pointer-events: none;
}
</style>
