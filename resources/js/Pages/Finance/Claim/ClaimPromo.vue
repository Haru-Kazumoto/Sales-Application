<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Klaim Promo" />
        <div class="d-flex flex-column">
            <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }} </span>
                    <n-data-table :columns="columns" :data="data" :pagination="pagination" :row-key="rowKey"
                        @update:checked-row-keys="handleCheck" />
                    <n-button type="primary" class="ms-auto my-3" style="width: 15rem;" @click="handleSendReminder">Create</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { NButton, type DataTableColumns, type DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';
import {router} from "@inertiajs/vue3";
import { formatRupiah } from '../../../Utils/options-input.utils';

interface RowData {
    key: number;
    faktur_date: number;
    faktur_number: number;
    customer_name: string;
    item_name: string;
    package: string;
    quantity: number;
    price: number; // exc ppn
    total: number;
    discount_local: number;
    total_discount_local: number;
    discount_ws: number;
    total_discount_ws: number;
    discount_ed: number;
    total_discout_ed: number;
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
            key: 'discount_local',
            width: 150,
            render(row) {
                return `${row.discount_local}%`;
            }
        },
        {
            title: "TOTAL DISKON LOKAL",
            key: 'total_discount_local',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_local);
            }
        },
        {
            title: "DISKON WS",
            key: 'discount_ws',
            width: 150,
            render(row) {
                return `${row.discount_ws}%`;
            }
        },
        {
            title: "TOTAL DISKON WS",
            key: 'total_discount_ws',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_ws);
            }
        },
        {
            title: "DISKON ED",
            key: 'discount_ed',
            width: 150,
            render(row) {
                return `${row.discount_ed}%`;
            }
        },
        {
            title: "TOTAL DISKON ED",
            key: 'total_discout_ed',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discout_ed);
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
            title: 'Action',
            key: 'actions',
            render(row) {
                return h('div', { class: 'd-flex gap-2' }, [
                    h(
                        NButton,
                        {
                            type: 'info',
                            size: 'small',
                            onClick: () => {
                                alert(`${row.key} is selected!`);
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
        faktur_number: 1001,
        customer_name: "PT. Elektronik",
        item_name: "Laptop",
        package: "Box",
        quantity: 2,
        price: 10000000,
        total: 20000000,
        discount_local: 100000,
        total_discount_local: 200000,
        discount_ws: 50000,
        total_discount_ws: 100000,
        discount_ed: 30000,
        total_discout_ed: 60000,
        total_all: 19680000,
    },
    {
        key: 2,
        faktur_date: 2,
        faktur_number: 1002,
        customer_name: "CV. Teknologi",
        item_name: "Mouse",
        package: "Plastic",
        quantity: 10,
        price: 50000,
        total: 500000,
        discount_local: 5000,
        total_discount_local: 10000,
        discount_ws: 2000,
        total_discount_ws: 4000,
        discount_ed: 1000,
        total_discout_ed: 2000,
        total_all: 484000,
    },
    {
        key: 3,
        faktur_date: 4,
        faktur_number: 1003,
        customer_name: "PT. Komputer",
        item_name: "Keyboard",
        package: "Box",
        quantity: 3,
        price: 300000,
        total: 900000,
        discount_local: 5000,
        total_discount_local: 15000,
        discount_ws: 3000,
        total_discount_ws: 9000,
        discount_ed: 2000,
        total_discout_ed: 6000,
        total_all: 870000,
    },
    {
        key: 4,
        faktur_date: 19,
        faktur_number: 1004,
        customer_name: "UD. Perangkat",
        item_name: "Monitor",
        package: "Box",
        quantity: 1,
        price: 1500000,
        total: 1500000,
        discount_local: 10000,
        total_discount_local: 10000,
        discount_ws: 5000,
        total_discount_ws: 5000,
        discount_ed: 3000,
        total_discout_ed: 3000,
        total_all: 1472000,
    },
    {
        key: 5,
        faktur_date: 20,
        faktur_number: 1005,
        customer_name: "CV. Hardware",
        item_name: "Printer",
        package: "Box",
        quantity: 1,
        price: 2000000,
        total: 2000000,
        discount_local: 15000,
        total_discount_local: 15000,
        discount_ws: 7000,
        total_discount_ws: 7000,
        discount_ed: 5000,
        total_discout_ed: 5000,
        total_all: 1963000,
    }
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
                router.visit(route('finance.form-claim-promo'), {method: 'get'});
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