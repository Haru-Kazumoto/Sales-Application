<template>
    <Head title="List SSO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List SSO" />
        <div class="d-flex gap-2 align-items-center">
            <n-button type="primary">
                <template #icon>
                    <n-icon :component="Refresh" />
                </template>
            </n-button>
            <n-select :options="dateOptions" class="w-25" size="medium" />
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="$page.props.sub_sales_orders" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { Head, router } from '@inertiajs/vue3';
import { Refresh } from "@vicons/ionicons5";
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { formatRupiah } from '../../../Utils/options-input.utils';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

interface RowData {
    id: number;
    sales_order_number: number;
    order_date: string;
    proof_number: string;
    sender: string;
    storehouse:string;
    total_price: number;
}

export default defineComponent({
    setup() {

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
                    title: 'No SO',
                    key: 'sales_order_number',
                    width: 200,
                    render(rowData) {
                        return rowData.sales_order_number;
                    },
                },
                {
                    title: 'Tanggal PO',
                    key: 'order_date',
                    width: 250,
                    render(rowData) {
                        return dayjs(rowData.order_date).format('dddd, D MMMM YYYY ');  // Menampilkan nama salesman
                    },
                },
                {
                    title: 'Pengirim',
                    key: 'sender',
                    width: 200,
                    render(rowData) {
                        return rowData.sender;  // Menampilkan nama pelanggan
                    },
                },
                {
                    title: 'Gudang',
                    key: 'storehouse',
                    width: 200,
                    render(rowData) {
                        return rowData.storehouse;  // Menampilkan jangka waktu pembayaran
                    },
                },
                {
                    title: 'Harga',
                    key: 'total_price',
                    width: 200,
                    render(rowData) {
                        return formatRupiah(rowData.total_price);  // Menampilkan tanggal jatuh tempo
                    },
                },
                {
                    title: 'Action',
                    key: 'action',
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "primary",
                                onClick: () => {
                                    router.get(route('procurement.sales-order.detail', row.id));
                                }
                            },
                            { default: () => 'Detail' }
                        );
                    }
                }
            ];
        }

        //options
        const dateOptions = [
            { label: "Bulan Ini", value: 'this_month' },
            { label: "Minggu Ini", value: 'this_week' },
            { label: "Tahun Ini", value: 'this_year' }
        ]

        return {
            columns: createColumns(),
            dateOptions,
            Refresh
        }
    },
    components: {
        TitlePage,
        Head
    }
})
</script>