<template>
    <Head title="List PO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Of PO" />
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
                <n-data-table :columns="columns" :bordered="false" :data="$page.props.purchase_orders" />
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

interface RowData {
    id: number;
    purchase_order_number: number;
    supplier: string;
    sender: string;
    storehouse: string;
    total_price: number;
    isDocumentHasGenerated: number;
}

export default defineComponent({
    setup() {

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
                    title: 'No PO',
                    key: 'purchase_order_number',
                    render(rowData) {
                        return rowData.purchase_order_number;  // Menampilkan nomor faktur
                    },
                },
                {
                    title: 'Pemasok',
                    key: 'supplier',
                    render(rowData) {
                        return rowData.supplier;  // Menampilkan nama salesman
                    },
                },
                {
                    title: 'Pengirim',
                    key: 'sender',
                    render(rowData) {
                        return rowData.sender;  // Menampilkan nama pelanggan
                    },
                },
                {
                    title: 'Gudang',
                    key: 'storehouse',
                    render(rowData) {
                        return rowData.storehouse;  // Menampilkan jangka waktu pembayaran
                    },
                },
                {
                    title: 'Harga',
                    key: 'total_price',
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
                                    router.get(route('procurement.purchase-order.detail', row.id));
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