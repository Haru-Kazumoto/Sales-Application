<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Dashboard Marketing" />
        <div class="row d-flex align-items-center ">
            <div class="col-12 col-lg-6">
                <span class="fs-5">Ringkasan Penjualan</span>
            </div>
            <div class="col-12 col-lg-6">
                <n-date-picker class="ms-lg-auto w-50" size="medium" />
            </div>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 ">
                    <div class="card-body">
                        <div class="card-title">
                            TARGET PENJUALAN
                        </div>
                        <div class="card-content mb-3">
                            <span class="fs-3 fw-medium">Rp 1.000.000.000</span>
                        </div>
                        <span>BULAN SEPTEMBER 2024</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 ">
                    <div class="card-body">
                        <div class="card-title">
                            POTENSI PENJUALAN
                        </div>
                        <div class="card-content mb-3">
                            <span class="fs-3 fw-medium">Rp 80.000.000</span>
                        </div>
                        <span>BULAN SEPTEMBER 2024</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 ">
                    <div class="card-body">
                        <div class="card-title">
                            KONVERSI PEMBAYARAN
                        </div>
                        <div class="card-content mb-3">
                            <span class="fs-3 fw-medium">Rp 60.000.000</span>
                        </div>
                        <span>BULAN SEPTEMBER 2024</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 ">
                    <div class="card-body">
                        <div class="card-title">
                            KEKURANGAN TARGET
                        </div>
                        <div class="card-content mb-3">
                            <span class="fs-3 fw-medium">Rp 40.000.000</span>
                        </div>
                        <span>BULAN SEPTEMBER 2024</span>
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
                                <span class="fs-3 fw-medium">TARGET PENJUALAN</span>
                                <span>Target summary April 2024</span>
                            </div>
                            <div class="col-12 col-lg-6 d-flex flex-column align-items-lg-end">
                                <span class="fs-3 fw-medium">Rp 1.000.000.000</span>
                                <span>Kurang <span class="text-red">Rp 100.000.000</span> dari target tahunan
                                    2024</span>
                            </div>
                        </div>
                        <n-divider></n-divider>
                        <ChartSales />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex">
                    <span class="fw-medium">PERFORMA SALESMAN</span>
                </div>
                <n-data-table :bordered="false" :columns="salesmanPerformColumn" />
            </div>
        </div>

        <div class="card shadow-sm border-0">
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
        </div>
    </div>
</template>

<script lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { defineComponent, h } from 'vue';
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

        return {
            salesmanPerformColumn: createSalesmanPerformColumn(),
            runningBillColumn: createRunningBillColumn(),
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