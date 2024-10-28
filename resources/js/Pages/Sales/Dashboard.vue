<template>
    <div class="d-flex flex-column gap-4">
        <div class="card shadow-sm overflow-hidden border-0 mt-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-center text-white text-md-start text-center p-md-2"
                style="background: rgb(0,165,79); background: linear-gradient(114deg, rgba(0,165,79,1) 0%, rgba(16,195,101,1) 71%, rgba(24,210,54,1) 100%);">
                <n-image src="images/sales-img.png" preview-disabled class="img-fluid mb-md-0" width="250"
                    height="auto" />
                <div class="d-flex flex-column gap-2">
                    <h1 class="fs-2 fs-md-1">Selamat datang, {{ $page.props.auth.user.fullname }}!</h1>
                    <span class="fs-5 fs-md-4">Welcome to the dashboard,<br>You're all set to make things happen today.
                        Let's go!</span>
                </div>
            </div>
        </div>
        <!-- Sales Cards Row -->
        <div class="container-fluid">
            <div class="row g-3 d-flex flex-nowrap overflow-auto" id="horizontal-scroll">
                <div class="col-12 col-lg-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#dfd4fa">
                                    <n-icon :size="18" :component="BarChart" color="#8c5cff" />
                                </n-icon-wrapper>
                                <span class="fw-semibold">TARGET PENJUALAN</span>
                            </div>
                            <div class="card-content mb-3">
                                <span class="fs-3 fw-medium">Rp 1.000.000.000</span>
                            </div>
                            <span>BULAN SEPTEMBER 2024</span>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#faf0d4">
                                    <n-icon :size="25" :component="AnalyticsSharp" color="#ffbe5c" />
                                </n-icon-wrapper>
                                <span class="fw-semibold">TOTAL PENJUALAN</span>
                            </div>
                            <div class="card-content mb-3">
                                <span class="fs-3 fw-medium">Rp 1.000.000.000</span>
                            </div>
                            <span>BULAN SEPTEMBER 2024</span>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="card-title d-flex gap-3 align-items-center">
                                <n-icon-wrapper :size="40" :border-radius="10" color="#fad4d4">
                                    <n-icon :size="18" :component="Book" color="#ff855c" />
                                </n-icon-wrapper>
                                <span class="fw-semibold">KEKURANGAN TARGET PENJUALAN</span>
                            </div>
                            <div class="card-content mb-3">
                                <span class="fs-3 fw-medium">Rp 1.000.000.000</span>
                            </div>
                            <span>BULAN SEPTEMBER 2024</span>
                        </div>

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
                    <n-tab-pane name="Penjualan" tab="Penjualan">
                        <div class="card shadow h-100" style="border: none;">
                            <div class="card-body d-flex flex-column gap-3">
                                <n-data-table :bordered="false" :columns="columnsSales" size="small" />
                            </div>
                        </div>
                    </n-tab-pane>
                    <n-tab-pane name="Aging" tab="Aging">
                        <div class="card shadow h-100" style="border: none;">
                            <div class="card-body d-flex flex-column gap-3">
                                <n-data-table :bordered="false" :columns="columnsAging" size="small" />
                            </div>
                        </div>
                    </n-tab-pane>
                </n-tabs>
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
import { DataTableColumns } from 'naive-ui';
import { OpenOutline, BarChart, AnalyticsSharp, Book } from '@vicons/ionicons5';

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
            OpenOutline,
            BarChart,
            AnalyticsSharp,
            Book
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
#horizontal-scroll {
    overflow-x: auto;
    /* Enable horizontal scrolling */
    scroll-snap-type: x mandatory;
    /* Enable scroll snapping */
    -webkit-overflow-scrolling: touch;
    /* Smooth scrolling for touch devices */
}

#horizontal-scroll .col-12 {
    min-width: 250px;
    /* Minimum width for each card to allow horizontal scroll */
    scroll-snap-align: start;
    /* Ensure card snaps into position when scrolling */
}

/* Hide scrollbar for Chrome, Safari, and Edge */
#horizontal-scroll::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge, and Firefox */
#horizontal-scroll {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}

@media (max-width: 576px) {
    #horizontal-scroll {
        display: flex;
        /* Make it a flexbox container */
        flex-wrap: nowrap;
        /* Prevent wrapping so cards scroll horizontally */
    }

    .col-12 {
        flex: 0 0 auto;
        /* Ensure cards don't shrink or grow */
    }
}
</style>