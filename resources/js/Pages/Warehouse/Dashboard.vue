<template>
    <div class="d-flex flex-column">
        <div class="d-flex flex-column gap-4">
            <div class="d-flex flex-row align-items-center">
                <TitlePage :title="$page.props.auth.user.role.role_name" />
                <n-date-picker v-model:value="timestamp" type="date" class="ms-auto" />
            </div>
            <div class="d-flex flex-row align-items-center justify-content-between">
                <SalesCountCard />
                <SalesCountCard />
                <SalesCountCard />
            </div>
            <div class="d-flex flex-row gap-4" style="height: 28rem;">
                <div class="d-flex flex-column gap-4" style="width: 50rem;">
                    <n-date-picker v-model:value="timestamp" type="date" class="ms-auto" />
                    <div class="card shadow flex-grow-1" style="border: none;">
                        <div class="card-body d-flex" style="height: 100%;">
                            <ChartSales style="height: 100%; width: 100%;" />
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-grow-1">
                    <div class="card shadow flex-grow-1" style="border: none;">
                        <div class="card-body d-flex flex-column" style="height: 100%;">
                            <span class="fs-5 fw-medium">Sales Target</span>
                            <ChartSalesTarget style="height: 100%; width: 100%;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import SalesCountCard from '../../Components/SalesCountCard.vue';
import ChartSales from '../../Components/ChartSales.vue';
import ChartSalesTarget from '../../Components/ChartSalesTarget.vue';
import TitlePage from '../../Components/TitlePage.vue';

export default defineComponent({
    setup() {
        return {
            timestamp: ref(1183135260000)
        }
    },
    components: {
        SalesCountCard,
        ChartSales,
        ChartSalesTarget,
        TitlePage
    }
});
</script>

<style scoped></style>