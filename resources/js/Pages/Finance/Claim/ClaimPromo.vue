<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Klaim Promo" />
        <div class="d-flex flex-column">
            <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }} </span>
                    <n-data-table :columns="columns" :data="data" :pagination="pagination" :row-key="rowKey"
                        @update:checked-row-keys="handleCheck" size="small" />
                    <n-button type="primary" class="ms-auto my-3" @click="handleSendReminder">Create</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { NButton, type DataTableColumns, type DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';
import { router } from "@inertiajs/vue3";
import { formatRupiah } from '../../../Utils/options-input.utils';

interface RowData {
    key: number;
    faktur_date: number;
    faktur_number: string;
    customer_name: string;
    item_name: string;
    package: string;
    quantity: number;
    price: number; // exc ppn
    total: number;
    discount_1: number;
    total_discount_1: number;
    discount_2: number;
    total_discount_2: number;
    discount_3: number;
    total_discout_3: number;
    total_all: number;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            type: 'selection',
        },
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1; // Untuk memberikan nomor urut baris
            },
        },
        {
            title: "TGL FAKTUR",
            key: 'faktur_date',
            width: 100,
            render(row) {
                return row.faktur_date;
            }
        },
        {
            title: "NO. FAKTUR",
            key: 'faktur_number',
            width: 160,
            render(row) {
                return row.faktur_number;
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: 'customer_name',
            width: 200,
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "NAMA ITEM",
            key: 'item_name',
            width: 250,
            render(row) {
                return row.item_name;
            }
        },
        {
            title: "SATUAN",
            key: 'package',
            width: 100,
            render(row) {
                return row.package;
            }
        },
        {
            title: "QTY",
            key: 'quantity',
            width: 100,
            render(row) {
                return row.quantity;
            }
        },
        {
            title: "HARGA",
            key: 'price',
            width: 200,
            render(row) {
                return formatRupiah(row.price);
            }
        },
        {
            title: "TOTAL",
            key: 'total',
            width: 200,
            render(row) {
                return formatRupiah(row.total);
            }
        },
        {
            title: "DISKON LOKAL",
            key: 'discount_1',
            width: 150,
            render(row) {
                return `${row.discount_1}%`;
            }
        },
        {
            title: "TOTAL DISKON LOKAL",
            key: 'total_discount_1',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_1);
            }
        },
        {
            title: "DISKON WS",
            key: 'discount_2',
            width: 150,
            render(row) {
                return `${row.discount_2}%`;
            }
        },
        {
            title: "TOTAL DISKON 2",
            key: 'total_discount_2',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_2);
            }
        },
        {
            title: "DISKON ED",
            key: 'discount_3',
            width: 150,
            render(row) {
                return `${row.discount_3}%`;
            }
        },
        {
            title: "TOTAL DISKON 3",
            key: 'total_discout_3',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discout_3);
            }
        },
        {
            title: "TOTAL AKHIR",
            key: 'total_all',
            width: 200,
            render(row) {
                return formatRupiah(row.total_all);
            }
        },
        {
            title: 'ACTION',
            key: 'actions',
            render(row) {
                return h('div', { class: 'd-flex gap-2' }, [
                    h(
                        NButton,
                        {
                            type: 'info',
                            size: 'small',
                            onClick: () => {
                                router.visit(route('finance.claim-promo.detail'), {method: 'get'});
                            }
                        },
                        { default: () => 'Detail' }
                    )
                ]);
            }
        }
    ];
}

const data: RowData[] = [
    {
        key: 1,
        faktur_date: 1,
        faktur_number: "INV-2408-0001",
        customer_name: "PUTERA GRAHA S,PT",
        item_name: "SANIA PREMIUM MARGARINE",
        package: "KARTON",
        quantity: 2,
        price: 10000000,
        total: 20000000,
        discount_1: 11,
        total_discount_1: 200000,
        discount_2: 2,
        total_discount_2: 100000,
        discount_3: 6,
        total_discout_3: 60000,
        total_all: 19680000,
    },
    {
        key: 2,
        faktur_date: 1,
        faktur_number: "INV-2408-0001",
        customer_name: "PUTERA GRAHA S,PT",
        item_name: "SANIA PREMIUM MARGARINE",
        package: "KARTON",
        quantity: 2,
        price: 10000000,
        total: 20000000,
        discount_1: 11,
        total_discount_1: 200000,
        discount_2: 2,
        total_discount_2: 100000,
        discount_3: 1,
        total_discout_3: 60000,
        total_all: 19680000,
    },
];



export default defineComponent({
    setup() {
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);

        function handleSendReminder() {
            if (checkedRowKeysRef.value.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pilih minimal 1 data!',
                });
            } else {
                router.visit(route('finance.form-claim-promo'), { method: 'get' });
            }

        }

        return {
            data,
            columns: createColumns(),
            handleSendReminder,
            checkedRowKeys: checkedRowKeysRef,
            pagination: {
                pageSize: 10
            },
            rowKey: (row: RowData) => row.key,
            handleCheck(rowKeys: DataTableRowKey[]) {
                checkedRowKeysRef.value = rowKeys
            }
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>