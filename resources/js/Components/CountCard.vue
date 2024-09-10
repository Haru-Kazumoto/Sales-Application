<template>
    <div class="d-flex flex-column">
        <div :class="['card', cardBorderClass, 'shadow']" style="width: 16rem;">
            <div class="card-body d-flex flex-column gap-4">
                <h5 class="card-title">{{ title }}</h5>
                <span :class="['fs-1', cardTextClass, 'fw-bold']">{{ count }}</span>
            </div>
            <!-- Menggunakan Inertia Link -->
            <Link
                :href="route(link_page_name)"
                :class="['card-footer', cardBgClass, 'd-flex', 'flex-row', 'justify-content-center', 'align-items-center', 'gap-2']"
                style="border: none; cursor: pointer; text-decoration: none;"
            >
                <span class="text-white fw-semibold">Lihat detail</span>
                <n-icon :component="ArrowForwardCircleOutline" class="fs-5 text-white" />
            </Link>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowForwardCircleOutline } from '@vicons/ionicons5';

export default defineComponent({
    props: {
        type: {
            type: String,
            default: 'reguler',
            required: false,
            validator: (value: string) => ['primary', 'reguler', 'warning', 'danger'].includes(value),
        },
        title: { type: String, required: true },
        link_page_name: { type: String, required: true },
        count: { type: String, required: true }
    },
    components: {
        Link,
    },
    setup(props) {
        // Computed classes for border and background based on 'type' prop
        const cardBorderClass = computed(() => {
            switch (props.type) {
                case 'primary':
                    return 'border-success'; // Green border for primary
                case 'warning':
                    return 'border-warning'; // Yellow border for warning
                case 'danger':
                    return 'border-danger'; // Red border for danger
                default:
                    return 'border-dark'; // Default black border for reguler
            }
        });

        const cardBgClass = computed(() => {
            switch (props.type) {
                case 'primary':
                    return 'bg-success'; // Green background for primary
                case 'warning':
                    return 'bg-warning'; // Yellow background for warning
                case 'danger':
                    return 'bg-danger'; // Red background for danger
                default:
                    return 'bg-dark'; // Default black background for reguler
            }
        });

        const cardTextClass = computed(() => {
            switch (props.type) {
                case 'primary':
                    return 'text-success'; // Green text for primary
                case 'warning':
                    return 'text-warning'; // Yellow text for warning
                case 'danger':
                    return 'text-danger'; // Red text for danger
                default:
                    return 'text-dark'; // Default black text for reguler
            }
        });

        return {
            ArrowForwardCircleOutline,
            cardBorderClass,
            cardBgClass,
            cardTextClass,
        };
    },
});
</script>
