<template>
    <div class="d-flex flex-column gap-4">

        <div class="card shadow-sm overflow-hidden border-0 mt-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-center text-white text-md-start text-center p-md-2"
                style="background: rgb(0,165,79); background: linear-gradient(114deg, rgba(0,165,79,1) 0%, rgba(16,195,101,1) 71%, rgba(24,210,54,1) 100%);">
                <n-image src="images/marketing.png" preview-disabled class="img-fluid mb-md-0" width="300"
                    height="auto" />
                <div class="d-flex flex-column gap-2">
                    <h1 class="fs-2 fs-md-1">Halo, {{ $page.props.auth.user.fullname }}! </h1>
                    <span class="fs-5 fs-md-4">
                        Mari kita mulai hari ini dengan tekad dan semangat yang baru! Semua tujuan menanti untuk
                        dicapai.
                    </span>
                </div>
            </div>
        </div>

        <div class="row d-flex align-items-center ">
            <div class="col-12 col-lg-6">
                <span class="fs-2 fw-semibold">Ringkasan Penjualan</span>
            </div>
        </div>

        <div class="row g-3 d-flex flex-nowrap overflow-auto" id="horizontal-scroll">
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="card-title">TARGET PENJUALAN BULAN INI</div>
                        <div class="card-content">
                            <span class="fs-4 fw-medium">
                                {{ $page.props.target && $page.props.target.monthly_target ?
                                    formatRupiah($page.props.target.monthly_target) : formatRupiah(0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="card-title">TOTAL PENJUALAN SALES</div>
                        <div class="card-content ">
                            <span class="fs-4 fw-medium">{{ formatRupiah($page.props.totalSalesMargin) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="card-title">TOTAL PENJUALAN</div>
                        <div class="card-content">
                            <span class="fs-4 fw-medium">{{ formatRupiah($page.props.total_with_sales) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="card-title">KEKURANGAN TARGET</div>
                        <div class="card-content">
                            <span class="fs-4 fw-medium">{{ formatRupiah($page.props.shortfall) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row g-2 ms-2 my-2">
                            <div class="col-12 col-lg-6 d-flex flex-column">
                                <span class="fs-3 fw-medium">TARGET PENJUALAN TAHUN INI</span>
                                <span>Target summary 2025</span>
                            </div>
                            <div class="col-12 col-lg-6 d-flex flex-column align-items-lg-end">
                                <span class="fs-3 fw-medium">{{ formatRupiah($page.props.auth.user.annual_target)
                                    }}</span>
                                <span>Kurang <span class="text-red">{{ formatRupiah($page.props.annual_shortfall)
                                        }}</span> dari target 2025</span>
                            </div>
                        </div>
                        <n-divider></n-divider>
                        <ChartSales :target="$page.props.targets" :total_target="total_targets" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex">
                    <span class="fw-medium">PERFORMA SALESMAN</span>
                </div>
                <n-data-table :bordered="false" :columns="salesmanPerformColumn" />
            </div>
        </div>

        <!-- <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-5">
                        <span class="fw-medium">TAGIHAN BERJALAN</span>
                    </div>
                    <div class="col-5 col-lg-3 align-items-lg-end">
                        <n-select />
                    </div>
                    <div class="col-7 col-lg-4 align-items-lg-end">
                        <n-input placeholder="Cari Nama Customer" />
                    </div>
                </div>
                <n-data-table :bordered="false" :columns="runningBillColumn" />
            </div>
        </div> -->
    </div>
</template>

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

<script lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, defineComponent, h } from 'vue';
import TitlePage from "../../Components/TitlePage.vue";
import ChartSales from '../../Components/ChartSales.vue';
import { DataTableColumns, NBadge } from 'naive-ui';
import { formatRupiah } from "../../Utils/options-input.utils";

interface RowDataSalesmanPerform {
    salesman: string;
    sales_target: number;
    total_sales: number;
    lack_sales_target: number; //kekurangan target penjualan
}

interface RowDataRunningBill {
    invoice_number: string;
    salesman: string;
    customer_name: string;
    status: string;
    term_of_payment: string;
    due_date: string;
    transaction_age: string;
}

function createSalesmanPerformColumn(): DataTableColumns<RowDataSalesmanPerform> {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: "Nama Salesman",
            key: 'salesman',
            width: 150,
            render(row) {
                return row.salesman;
            }
        },
        {
            title: "Target Penjualan",
            key: 'sales_target',
            width: 150,
            render(row) {
                return formatRupiah(row.sales_target);
            }
        },
        {
            title: "Total Penjualan",
            key: 'total_sales',
            width: 150,
            render(row) {
                return formatRupiah(row.total_sales);
            }
        },
        {
            title: "Kekurangan Target Penjualan",
            key: 'lack_sales_target',
            width: 200,
            render(row) {
                return formatRupiah(row.lack_sales_target);
            }
        }
    ];
}

function createRunningBillColumn(): DataTableColumns<RowDataRunningBill> {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: "NO INVOICE",
            key: 'invoice_number',
            width: 150,
            render(row) {
                return row.invoice_number;
            }
        },
        {
            title: "SALESMAN",
            key: 'salesman',
            width: 150,
            render(row) {
                return row.salesman;
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: 'customer_name',
            width: 150,
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "STATUS",
            key: "status",
            width: 100,
            render(row) {
                let type: any;

                switch (row.status) {
                    case "PAID":
                        type = 'success';
                    case "UNPAID":
                        type = 'error';
                    case "INSTALMENT":
                        type = 'warning';
                    default:
                        type = 'success';
                }

                return h(
                    NBadge,
                    {
                        type
                    },
                    { default: () => row.status }
                )
            }
        },
        {
            title: "TERM OF PAYMENT",
            key: "term_of_payment",
            width: 100,
            render(row) {
                return row.term_of_payment;
            }
        },
        {
            title: "DUE DATE",
            key: 'due_date',
            width: 150,
            render(row) {
                return row.due_date;
            }
        },
        {
            title: "UMUR TRANSAKSI",
            key: 'transaction_age',
            width: 100,
            render(row) {
                return row.transaction_age;
            }
        }
    ];
}

export default defineComponent({
    setup() {
        const page = usePage();

        const total_targets = computed(() => {
            return page.props.target_margin.map((data) => data.amount_sales);
        });

        return {
            salesmanPerformColumn: createSalesmanPerformColumn(),
            runningBillColumn: createRunningBillColumn(),
            formatRupiah,
            total_targets
        }
    },
    components: {
        Head,
        Link,
        TitlePage,
        ChartSales
    }
})
</script>

<style scoped></style>