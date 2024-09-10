<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Klaim Promo" />
        <div class="d-flex flex-row gap-2">
            <n-date-picker size="large" type="date" style="width: 15rem;" />
            <n-date-picker size="large" type="date" style="width: 15rem;" />
            <n-input size="large" style="width: 15rem;" placeholder="No Invoice" />
        </div>
        <div class="d-flex flex-column">
            <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }} </span>
                    <n-data-table :columns="columns" :data="data" :pagination="pagination" :row-key="rowKey"
                        @update:checked-row-keys="handleCheck" />
                    <n-button type="primary" class="ms-auto my-3" style="width: 15rem;" @click="handleSendReminder">Send
                        Reminder</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { NButton, type DataTableColumns, type DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';

interface RowData {
    key: number;
    no_invoice: string;
    item_name: string;
    total: number;
    unit: string;
    supplier: string;
    total_price: number;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            type: 'selection',
        },
        {
            title: '#',
            key: 'index',
            render(rowData, rowIndex) {
                return rowIndex + 1; // Untuk memberikan nomor urut baris
            },
        },
        {
            title: 'No Invoice',
            key: 'no_invoice',
            render(rowData) {
                return rowData.no_invoice; // Menampilkan nomor invoice
            },
        },
        {
            title: 'Item Name',
            key: 'item_name',
            render(rowData) {
                return rowData.item_name; // Menampilkan nama item
            },
        },
        {
            title: 'Total',
            key: 'total',
            render(rowData) {
                return rowData.total; // Menampilkan total item
            },
        },
        {
            title: 'Unit',
            key: 'unit',
            render(rowData) {
                return rowData.unit; // Menampilkan unit
            },
        },
        {
            title: 'Supplier',
            key: 'supplier',
            render(rowData) {
                return rowData.supplier; // Menampilkan nama supplier
            },
        },
        {
            title: 'Total Price',
            key: 'total_price',
            render(rowData) {
                return rowData.total_price; // Menampilkan total harga
            },
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
        no_invoice: 'INV-001',
        item_name: 'Laptop',
        total: 2,
        unit: 'pcs',
        supplier: 'Tech Supplier Inc.',
        total_price: 2000,
    },
    {
        key: 2,
        no_invoice: 'INV-002',
        item_name: 'Mouse',
        total: 5,
        unit: 'pcs',
        supplier: 'Gadget Supply Co.',
        total_price: 100,
    },
    {
        key: 3,
        no_invoice: 'INV-003',
        item_name: 'Keyboard',
        total: 3,
        unit: 'pcs',
        supplier: 'Gadget Supply Co.',
        total_price: 150,
    },
    {
        key: 4,
        no_invoice: 'INV-004',
        item_name: 'Monitor',
        total: 1,
        unit: 'pcs',
        supplier: 'Display World',
        total_price: 500,
    },
    {
        key: 5,
        no_invoice: 'INV-005',
        item_name: 'Printer',
        total: 1,
        unit: 'pcs',
        supplier: 'Office Essentials',
        total_price: 300,
    }
];


export default defineComponent({
    setup() {
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);

        function handleSendReminder() {
            if (checkedRowKeysRef.value.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'No data to send!',
                    text: 'At least check 1',
                });
            } else {
                //Create post logic send reminder here...

                Swal.fire({
                    title: 'Are you sure you want to send reminders?',
                    text: 'This action will send reminder text to customers',
                    icon: 'question',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success send reminder',
                            text: `Reminder has send to ${checkedRowKeysRef.value.length} customer${checkedRowKeysRef.value.length < 2 ? '' : 's'}`
                        });
                    }
                });

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