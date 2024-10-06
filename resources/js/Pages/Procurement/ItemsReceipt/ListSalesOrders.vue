<template>
    <Head title="List SSO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Of SSO" />
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
                <n-data-table :columns="columns" :bordered="false" :data="$page.props.transactions" />
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
import { Transactions } from '../../../types/model';

export default defineComponent({
    setup() {

        function createColumns(): DataTableColumns<Transactions> {
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
                        return rowData.document_code;  // Menampilkan nomor faktur
                    },
                },
                {
                    title: 'Pemasok',
                    key: 'supplier',
                    render(rowData) {
                        const supplierData = rowData.transaction_details.find((data) => {
                            return data.category === "Supplier"
                        })

                        return supplierData?.value;  // Menampilkan nama salesman
                    },
                },
                {
                    title: 'Pengirim',
                    key: 'sender',
                    render(rowData) {
                        const senderData = rowData.transaction_details.find((data) => {
                            return data.category === "Sender";
                        })
                        
                        return senderData?.value;
                    },
                },
                {
                    title: 'Gudang',
                    key: 'storehouse',
                    render(rowData) {
                        const storehouseData = rowData.transaction_details.find((data) => {
                            return data.category === "Storehouse";
                        })

                        return storehouseData?.value;
                    },
                },
                {
                    title: 'Harga',
                    key: 'total_price',
                    render(rowData) {
                        return formatRupiah(rowData.total ?? 0);
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