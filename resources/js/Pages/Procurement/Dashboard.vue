<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Dashboard Procurement" />
        <div class="d-flex flex-row justify-content-between">
            <CountCard type="reguler" title="Total Transaksi" link_page_name="dashboard.procurement" count="100" />
            <CountCard type="reguler" title="Transaksi Instalment" link_page_name="dashboard.procurement" count="100" />
            <CountCard type="warning" title="Tagihan Belum Bayar" link_page_name="dashboard.procurement" count="100" />
            <CountCard type="danger" title="Tagihan Jatuh Tempo" link_page_name="dashboard.procurement" count="100" />
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false" size="small"
                    pagination-behavior-on-filter="first" :row-props="rowProps" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { DataTableColumns, NTag } from 'naive-ui';

interface RowData {
    key: number;
    invoice_number: string;
    salesman: string;
    customer: string;
    payment_status: string;
    term_of_payment: string;
    due_date: string;
    transaction_age: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: '#',
            key: 'index',
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'NO Invoice',
            key: 'invoice_number',
            render(rowData) {
                return rowData.invoice_number;  // Menampilkan nomor faktur
            },
        },
        {
            title: 'Salesman',
            key: 'salesman',
            render(rowData) {
                return rowData.salesman;  // Menampilkan nama salesman
            },
        },
        {
            title: 'Pelanggan',
            key: 'customer',
            render(rowData) {
                return rowData.customer;  // Menampilkan nama pelanggan
            },
        },
        {
            title: 'Payment Status',
            key: 'payment_status',
            render(rowData) {
                // Tentukan warna dan tipe tag berdasarkan status pembayaran
                let type: any;

                switch (rowData.payment_status) {
                    case 'OVERDUE':
                        type = 'error';
                        break;
                    case 'UNPAID':
                        type = 'warning';
                        break;
                    case 'PAID':
                        type = 'success';
                        break;
                    case 'INSTALMENT':
                        type = '';
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
                    { default: () => rowData.payment_status }
                );
            },
        },

        {
            title: 'Term Of Payment',
            key: 'term_of_payment',
            render(rowData) {
                return rowData.term_of_payment;  // Menampilkan jangka waktu pembayaran
            },
        },
        {
            title: 'Due Date',
            key: 'due_date',
            render(rowData) {
                return rowData.due_date;  // Menampilkan tanggal jatuh tempo
            },
        },
        {
            title: 'Umur Transaksi',
            key: 'transaction_age',
            render(rowData) {
                return rowData.transaction_age;  // Menampilkan umur transaksi
            },
        },
    ];
}

export default defineComponent({
    setup() {
        // Data dummy untuk tabel
        const data: RowData[] = [
            { key: 1, invoice_number: 'INV001', salesman: 'John Doe', customer: 'Customer A', payment_status: 'PAID', term_of_payment: '30 days', due_date: '2024-10-01', transaction_age: '10 days' },
            { key: 2, invoice_number: 'INV002', salesman: 'Jane Smith', customer: 'Customer B', payment_status: 'UNPAID', term_of_payment: '45 days', due_date: '2024-09-20', transaction_age: '15 days' },
            { key: 3, invoice_number: 'INV003', salesman: 'Alice Johnson', customer: 'Customer C', payment_status: 'PAID', term_of_payment: '30 days', due_date: '2024-10-10', transaction_age: '5 days' },
            { key: 4, invoice_number: 'INV004', salesman: 'Bob Brown', customer: 'Customer D', payment_status: 'OVERDUE', term_of_payment: '60 days', due_date: '2024-09-30', transaction_age: '12 days' },
            { key: 5, invoice_number: 'INV005', salesman: 'Charlie Davis', customer: 'Customer E', payment_status: 'PAID', term_of_payment: '30 days', due_date: '2024-10-05', transaction_age: '8 days' },
            { key: 6, invoice_number: 'INV006', salesman: 'Dana Lee', customer: 'Customer F', payment_status: 'UNPAID', term_of_payment: '45 days', due_date: '2024-09-25', transaction_age: '14 days' },
            { key: 7, invoice_number: 'INV007', salesman: 'Evan Wright', customer: 'Customer G', payment_status: 'PAID', term_of_payment: '30 days', due_date: '2024-10-12', transaction_age: '3 days' },
            { key: 8, invoice_number: 'INV008', salesman: 'Grace Harris', customer: 'Customer H', payment_status: 'OVERDUE', term_of_payment: '60 days', due_date: '2024-10-01', transaction_age: '10 days' },
            { key: 9, invoice_number: 'INV009', salesman: 'Henry Adams', customer: 'Customer I', payment_status: 'UNPAID', term_of_payment: '45 days', due_date: '2024-09-22', transaction_age: '16 days' },
            { key: 10, invoice_number: 'INV010', salesman: 'Ivy Clark', customer: 'Customer J', payment_status: 'INSTALMENT', term_of_payment: '30 days', due_date: '2024-10-08', transaction_age: '7 days' },
        ];

        const rowProps = (row: RowData) => {
            return {
                class: row.payment_status === 'OVERDUE' ? 'text-red' : '',
            };
        };

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
            rowProps
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
