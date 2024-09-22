<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Admin Dashboard" />
        <div class="d-flex flex-column gap-4">
            <!-- Sales Cards Row -->
            <div class="row ">
                <div class="col-10 col-md-4">
                    <SalesCountCard />
                </div>
                <div class="col-10 col-md-4">
                    <SalesCountCard />
                </div>
                <div class="col-10 col-md-4">
                    <SalesCountCard />
                </div>
            </div>

            <!-- Chart and Sales Target Row -->
            <div class="row g-4 " style="height: 28rem;">
                <!-- Left Column: Date Picker and Chart -->
                <div class="col-12 col-lg-8 d-flex flex-column gap-4">
                    <div class="card shadow flex-grow-1" style="border: none;">
                        <div class="card-body d-flex flex-column h-100" >
                            <span class="fs-5 fw-medium">Chart penjualan</span>
                            <ChartSales class="h-100 w-100"/>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sales Target Card -->
                <div class="col-12 col-lg-4 d-flex flex-column flex-grow-1">
                    <div class="card shadow flex-grow-1 h-100" style="border: none; ">
                        <div class="card-body d-flex flex-column h-100" >
                            <span class="fs-5 fw-medium">Target Penjualan</span>
                            <ChartSalesTarget class="h-100 w-100" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table and Sales Target Row -->
            <div class="row mb-3">
                <!-- Left Column: Table -->
                <div class="col-12 col-lg-5">
                    <div class="card shadow" style="height: 25vh; border: none;">
                        <div class="card-body d-flex flex-column gap-2">
                            <span class="fs-5 fw-medium">New Customer</span>
                            <n-data-table />
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sales Target Card -->
                <div class="col-12 col-lg-7">
                    <div class="card shadow flex-grow-1" style="border: none; height: 100%;">
                        <div class="card-body d-flex flex-column" style="height: 100%;">
                            <span class="fs-5 fw-medium">Sales Target</span>
                            <n-data-table />
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
import { usePage } from '@inertiajs/vue3';

export default defineComponent({
    setup() {
        const page = usePage();

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