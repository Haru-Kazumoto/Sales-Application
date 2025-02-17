<template>
    <div class="card float-card">
        <div class="card-body">
            <div class="d-flex align-items-center gap-4">
                <n-icon-wrapper :size="40" :border-radius="10" color="#FF6500">
                    <n-icon :size="20" :component="icon" color="white" />
                </n-icon-wrapper>

                <!-- title -->
                <div class="d-flex flex-column">
                    <span class="fs-4 fw-bold">{{ title }}</span>
                    <span>{{ subTitle }}</span>
                </div>

                <div class="ms-auto" v-if="router">
                    <Link :href="route(router)">
                        <n-button type="primary" >PROSES</n-button>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/vue3';

export default defineComponent({
    props: {
        icon: {
            required: true
        },
        title: {
            type: String,
            required: true
        },
        subTitle: {
            type: String,
            required: false
        },
        router: {
            type: String,
            required: false
        }
    },
    components: {
        Link
    }
})
</script>

<style scoped>
.float-card {
    border-color: rgb(101, 182, 94);
    transition: transform 0.3s ease-in-out, border 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.float-card:hover {
    transform: scale(1.00);
    border: 1px solid #28a745;
    box-shadow: 0 1px 10px rgba(40, 167, 69, 0.5);
}
</style>