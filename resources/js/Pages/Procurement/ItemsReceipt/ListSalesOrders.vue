<template>

    <Head title="List SSO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Of SSO" />
        <div class="d-flex gap-2 align-items-center">
            <n-input size="large" class="w-25" placeholder="Cari Nomor SSO" v-model:value="filterQuery"
                @input="handleSearch" />
            <n-select :options="dateOptions" class="w-25" size="large" placeholder="Filter data per waktu"
                v-model:value="dateRange" @update:value="handleSearch" />
        </div>
        <div class="card shadow mb-5" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="$page.props.transactions.data" />
                <div class="d-flex mt-3">
                    <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                        :page-count="pagination.last_page" :page-size="pagination.per_page"
                        :item-count="pagination.total" @update:page="handlePageChange"
                        @update:page-count="pagination.last_page = ($page.props.transactions as any).last_page" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, reactive, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
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
                    title: 'No SSO',
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

                        return storehouseData?.value?.replace("_",' ');
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

        const page = usePage();

        const filterField = ref('document_code');
        const filterQuery = ref('');
        const dateRange = ref(null);

        function handlePageChange(page: number) {
            // currentPage.value = page;
            router.get(route('procurement.sales-order-list'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value,
                date_range: dateRange.value, // Kirim nilai dateRange
            }, { preserveState: true, replace: true }); // Request data for the selected page
        }

        const handleSearch = () => {
            router.get(route('procurement.sales-order-list'), {
                page: pagination.current_page,
                filter_field: filterField.value,
                filter_query: filterQuery.value,
                date_range: dateRange.value, // Kirim nilai dateRange
            }, {
                preserveState: true,
                replace: true
            });
        };

        const pagination = reactive({
            current_page: (page.props.transactions as any).current_page,
            per_page: (page.props.transactions as any).per_page,
            total: (page.props.transactions as any).total,
            last_page: (page.props.transactions as any).last_page,
        });

        //options
        const dateOptions = [
            { label: "Semua waktu", value: 'all' },
            { label: "Bulan Ini", value: 'this_month' },
            { label: "Minggu Ini", value: 'this_week' },
            { label: "Tahun Ini", value: 'this_year' }
        ]

        return {
            columns: createColumns(),
            handleSearch,
            handlePageChange,
            dateOptions,
            filterField,
            filterQuery,
            dateRange,
            pagination,
            Refresh
        }
    },
    components: {
        TitlePage,
        Head
    }
})
</script>