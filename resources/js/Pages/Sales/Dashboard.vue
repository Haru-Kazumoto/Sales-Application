<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Admin Dashboard" />
        <div class="d-flex flex-column gap-4">
            <!-- Sales Cards Row -->
            <div class="row ">
                <div class="col-10 col-md-4">
                    <div class="card shadow  overflow-hidden h-100" style="border: none;">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="card-title">
                                <span class="fw-semibold">TARGET PENJUALAN</span>
                            </div>
                            <div class="card-content ">
                                <span class="fw-semibold fs-3">Rp3.000.000</span>
                            </div>
                            <span>BULAN JULI 2024</span>
                        </div>
                    </div>
                </div>
                <div class="col-10 col-md-4">
                    <div class="card shadow  overflow-hidden h-100" style="border: none;">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="card-title">
                                <span class="fw-semibold">TARGET PENJUALAN</span>
                            </div>
                            <div class="card-content ">
                                <span class="fw-semibold fs-3">Rp3.000.000</span>
                            </div>
                            <span>BULAN JULI 2024</span>
                        </div>
                    </div>
                </div>
                <div class="col-10 col-md-4">
                    <div class="card shadow  overflow-hidden h-100" style="border: none;">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="card-title">
                                <span class="fw-semibold">TARGET PENJUALAN</span>
                            </div>
                            <div class="card-content ">
                                <span class="fw-semibold fs-3">Rp3.000.000</span>
                            </div>
                            <span>BULAN JULI 2024</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart and Sales Target Row -->
            <div class="row g-4 " style="height: 28rem;">
                <!-- Left Column: Date Picker and Chart -->
                <div class="col-12 col-lg-8 d-flex flex-column gap-4">
                    <div class="card flex-grow-1" style="border: none;">
                        <div class="card-body d-flex flex-column h-100">
                            <ChartSales class="h-100 w-100" />
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sales Target Card -->
                <div class="col-12 col-lg-4 d-flex flex-column flex-grow-">
                    <n-tabs type="segment" animated class="h-100">
                        <n-tab-pane name="Penjualan" tab="Penjualan" >
                            <div class="card shadow h-100" style="border: none;">
                                <div class="card-body d-flex flex-column gap-3">
                                    <n-data-table :bordered="false" :columns="columnsSales" size="small" />
                                </div>
                            </div>
                        </n-tab-pane>
                        <n-tab-pane name="Aging" tab="Aging" >
                            <div class="card shadow h-100" style="border: none;">
                                <div class="card-body d-flex flex-column gap-3">
                                    <n-data-table :bordered="false" :columns="columnsAging" size="small" />
                                </div>
                            </div>
                        </n-tab-pane>
                    </n-tabs>
                </div>
            </div>

            <!-- Table and Sales Target Row -->
            <!-- <div class="row mb-3">
                <div class="col-12 col-lg-5">
                    <div class="card shadow" style="height: 25vh; border: none;">
                        <div class="card-body d-flex flex-column gap-2">
                            <span class="fs-5 fw-medium">New Customer</span>
                            <n-data-table />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="card shadow flex-grow-1" style="border: none; height: 100%;">
                        <div class="card-body d-flex flex-column" style="height: 100%;">
                            <span class="fs-5 fw-medium">Sales Target</span>
                            <n-data-table />
                        </div>
                    </div>
                </div>
            </div> -->
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
import { DataTableColumns } from 'naive-ui';

interface RowDataSales {
    customer_name: string;
    total: number;
}

interface RowDataAging {
    customer_name: string;
    due_date: string;
}

function createColumnsSales(): DataTableColumns<RowDataSales> {
    return [
        {
            title: '#',
            key: 'index',
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: 'NAMA CUSTOMER',
            key: 'customer_name',
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "TOTAL",
            key: 'total',
            render(row) {
                return row.total;
            }
        }
    ]
}

function createColumnsAging(): DataTableColumns<RowDataAging> {
    return [
        {
            title: '#',
            key: 'index',
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: 'NAMA CUSTOMER',
            key: 'customer_name',
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "JATUH TEMPO",
            key: 'due_date',
            render(row) {
                return row.due_date;
            }
        }
    ]
}

export default defineComponent({
    setup() {
        const page = usePage();

        return {
            timestamp: ref(1183135260000),
            columnsSales: createColumnsSales(),
            columnsAging: createColumnsAging(),
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
    background: linear-gradient(-20deg, rgba(5, 233, 89, 0.538), rgb(255, 255, 255));
    /* Default gradient */
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
    background: linear-gradient(-20deg, rgba(255, 193, 7, 0.7), rgb(255, 255, 255));
    /* Yellow gradient */
    border-top-left-radius: 100rem;
    z-index: 1;
    pointer-events: none;
}
</style>