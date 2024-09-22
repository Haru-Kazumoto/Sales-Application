<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Dashboard Aging Finance" />
        <div class="d-flex flex-column gap-5">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: green;">
                        <div class="card-body">
                            <div class="card-title">TOTAL TRANSAKSI</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">200</span>
                                <n-button type="primary">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: orange;">
                        <div class="card-body">
                            <div class="card-title">TRANSAKSI INSTALMENT</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">10</span>
                                <n-button type="warning">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">TAGIHAN BELUM BAYAR</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">200</span>
                                <n-button type="error">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">TAGIHAN JATUH TEMPO</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">200</span>
                                <n-button type="error">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 ">
                <div class="col-12 col-lg-8 ">
                    <span class="fs-4">Daftar Transaksi</span>
                </div>
                <div class="col-12 col-lg-2 d-flex gap-3 ">
                    <n-select size="large" placeholder="Status" />
                </div>
                <div class="col-12 col-lg-2 d-flex gap-3 ">
                    <n-input placeholder="Cari Nama Customer" />
                </div>
                <div class="card shadow" style="border: none;">
                    <div class="card-body ">
                        <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false"
                            size="small" pagination-behavior-on-filter="first" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';

interface RowData {
    invoice_number: string;
    salesman: string;
    customer_name: string;
    status: string;
    term_of_payment: string;
    due_date: string;
    transaction_age: number;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'NO INVOICE',
            key: 'invoice_number',
            width: 150,
            render(rowData) {
                return rowData.invoice_number;  // Menampilkan SKU
            },
        },
        {
            title: 'SALESMAN',
            key: 'salesman',
            width: 100,
            render(rowData) {
                return rowData.salesman;  // Menampilkan nama item
            },
        },
        {
            title: 'NAMA CUTOMER',
            key: 'customer_name',
            width: 100,
            render(rowData) {
                return rowData.customer_name;  // Menampilkan nama supplier
            },
        },
        {
            title: 'TERM OF PAYMENT',
            key: 'term_of_payment',
            width: 100,
            render(rowData) {
                return rowData.term_of_payment;
            }
        },
        {
            title: 'Status',
            key: 'item_status',
            width: 100,
            render(rowData) {
                // Tentukan warna dan tipe tag berdasarkan item_status pembayaran
                let type: any;

                switch (rowData.status) {
                    case 'UNPAID':
                        type = 'error';
                        break;
                    case 'INSTALMENT':
                        type = 'warning';
                        break;
                    case 'PAID':
                        type = 'success';
                        break;
                    default:
                        type = '';
                }

                return h(
                    NTag,
                    {
                        style: {
                            marginRight: '6px',
                        },
                        type,
                        bordered: false
                    },
                    { default: () => rowData.status }
                );
            },
        },
        {
            title: 'DUE DATE',
            key: 'due_date',
            width: 100,
            render(rowData) {
                return rowData.due_date;
            }
        },
        {
            title: "UMUR TRANSAKSI",
            key: 'transaction_age',
            width: 100,
            render(rowData) {
                return `+${rowData.transaction_age} Hari`;
            }
        }
    ];
}

export default defineComponent({
    setup() {
        // Data dummy untuk tabel
        const data: RowData[] = [
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},
            {invoice_number: '004/CO-DKU/VI/24', salesman: 'DIDIN', customer_name: 'KHASANAH SARI', status: 'PAID', term_of_payment: '14 HARI', due_date: '04-04-2006', transaction_age: 15},

        ];

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
            pageCount: Math.ceil(data.length / 10),
            itemCount: data.length,
        });

        // Columns for DataTable
        const columns = createColumns();

        return {
            data,
            pagination,
            columns,
        };
    },
    components: {
        TitlePage,
        CountCard
    }
});
</script>

<style scoped>
.row-overdue {
    color: red !important;
}
</style>
