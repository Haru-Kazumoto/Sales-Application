<template>

    <Head title="List PO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Of PO" />
        <div class="d-flex gap-2 align-items-center">
            <n-input size="large" class="w-25" placeholder="Cari Nomor PO" v-model:value="filterQuery"
                @input="handleSearch" />
            <n-select :options="dateOptions" class="w-25" size="large" placeholder="Filter data per waktu"
                v-model:value="dateRange" @update:value="handleSearch" />
        </div>
        <div class="card shadow mb-5" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.transactions as any).data" />
                <div class="d-flex mt-3">
                    <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                        :page-count="pagination.last_page" :page-size="pagination.per_page"
                        @update:page="handlePageChange"
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


        const page = usePage();

        const filterField = ref('document_code');
        const filterQuery = ref('');
        const dateRange = ref(null);

        const pagination = reactive({
            current_page: (page.props.transactions as any).current_page,
            per_page: (page.props.transactions as any).per_page,
            total: (page.props.transactions as any).total,
            last_page: (page.props.transactions as any).last_page,
        });

        function handlePageChange(page: number) {
            // currentPage.value = page;
            router.get(route('procurement.purchase-order-list'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value,
                date_range: dateRange.value, // Kirim nilai dateRange
            }, { preserveState: true, replace: true,onSuccess: () => {pagination.current_page = page} }); // Request data for the selected page
        }

        const handleSearch = () => {
            router.get(route('procurement.purchase-order-list'), {
                page: pagination.current_page,
                filter_field: filterField.value,
                filter_query: filterQuery.value,
                date_range: dateRange.value, // Kirim nilai dateRange
            }, {
                preserveState: true,
                replace: true
            });
        };

        

        function createColumns(): DataTableColumns<Transactions> {
            return [
                {
                    title: '#',
                    key: 'index',
                    width: 60,
                    render(rowData, rowIndex) {
                        return (pagination.current_page - 1) * pagination.per_page + rowIndex + 1;
                    },
                },
                {
                    title: 'No PO',
                    key: 'purchase_order_number',
                    width: 200,
                    render(rowData) {
                        return rowData.document_code;  // Menampilkan nomor faktur
                    },
                },
                {
                    title: 'Pemasok',
                    key: 'supplier',
                    width: 200,
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
                    width: 200,
                    render(rowData) {
                        const senderData = rowData.transaction_details.find((data) => {
                            return data.category === "Sender";
                        })

                        return senderData?.value?.replace("_", ' ');
                    },
                },
                {
                    title: 'Harga',
                    key: 'total_price',
                    width: 200,
                    render(rowData) {
                        return formatRupiah(rowData.total ?? 0);
                    },
                },
                {
                    title: "Nomor Polisi",
                    key: "number_plate",
                    width: 200,
                    render(rowData) {
                        const numberPlate = rowData.transaction_details.find((data) => {
                            return data.category === "Transportation";
                        });
                        let type;
                        let displayValue;

                        switch (numberPlate?.value) {
                            case "-":
                            case "": // String kosong
                            case " ": // String berisi satu spasi
                                type = "error";
                                displayValue = "BELUM ADA";
                                break;
                            default:
                                type = "info";
                                displayValue = numberPlate?.value;
                                break;
                        }

                        return h(
                            NTag,
                            {
                                type
                            },
                            { default: () => displayValue }
                        );
                    }
                },
                {
                    title: 'Action',
                    key: 'action',
                    width: 100,
                    render(row) {
                        return h('div', { class: "d-flex gap-2" }, [
                            h(
                                NButton,
                                {
                                    type: "primary",
                                    onClick: () => {
                                        router.get(route('procurement.purchase-order.detail', row.id));
                                    }
                                },
                                { default: () => 'Detail' }
                            ),
                            h(
                                NButton,
                                {
                                    type: "info",
                                    onClick: () => {
                                        router.get(route('procurement.purchase-order.edit', row.id));
                                    }
                                },
                                { default: () => 'Update' }
                            )
                        ]);
                    }
                }
            ];
        }

        //options
        const dateOptions = [
            { label: "Semua Waktu", value: "all" },
            { label: "Bulan Ini", value: 'this_month' },
            { label: "Minggu Ini", value: 'this_week' },
            { label: "Tahun Ini", value: 'this_year' }
        ]

        return {
            columns: createColumns(),
            handlePageChange,
            handleSearch,
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