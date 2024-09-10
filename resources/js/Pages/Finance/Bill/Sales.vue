<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Sales Dashboard" />
        <div class="d-flex flex-row gap-2">
            <n-date-picker size="large" type="date" style="width: 10rem;" />
            <n-date-picker size="large" type="date" style="width: 10rem;" />
            <n-select size="large" style="width: 10rem;" placeholder="Customer" />
            <n-select size="large" style="width: 10rem;" placeholder="Salesman" />
        </div>
        <div class="d-flex flex-column">
            <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }}
            </span>
            <n-data-table :columns="columns" :data="data" :pagination="pagination" :row-key="rowKey"
                        @update:checked-row-keys="handleCheck" />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import type { DataTableColumns, DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';

interface RowData {
    key: number;
    poco_number_and_date: string;
    salesman: string;
    customer: string;
    payment_status: string;
    term_of_payment: string;
    due_date: string;
    over_due_day: number;
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
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'POCO Number & Date',
            key: 'poco_number_and_date',
            render(rowData) {
                return rowData.poco_number_and_date;  // Menampilkan POCO number dan tanggal
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
            title: 'Customer',
            key: 'customer',
            render(rowData) {
                return rowData.customer;  // Menampilkan nama customer
            },
        },
        {
            title: 'Payment Status',
            key: 'payment_status',
            render(rowData) {
                return rowData.payment_status;  // Menampilkan status pembayaran
            },
        },
        {
            title: 'Term of Payment',
            key: 'term_of_payment',
            render(rowData) {
                return rowData.term_of_payment;  // Menampilkan term of payment
            },
        },
        {
            title: 'Due Date',
            key: 'due_date',
            render(rowData) {
                return rowData.due_date;  // Menampilkan due date
            },
        },
        {
            title: 'Over Due Day',
            key: 'over_due_day',
            render(rowData) {
                return rowData.over_due_day;  // Menampilkan jumlah hari keterlambatan
            },
        }
    ];
}

const data: RowData[] = Array.from({ length: 10 }).map((_, index) => ({
    key: index + 1,
    poco_number_and_date: `POCO-2024-${index + 1} | 2024-09-${String(index + 1).padStart(2, '0')}`,
    salesman: `Salesman ${index + 1}`,
    customer: `Customer ${index + 1}`,
    payment_status: index % 2 === 0 ? 'Paid' : 'Pending',
    term_of_payment: index % 2 === 0 ? '30 Days' : '60 Days',
    due_date: `2024-10-${String(index + 1).padStart(2, '0')}`,
    over_due_day: index % 2 === 0 ? 0 : index + 2, // Over due days hanya ada untuk yang pending
}));


export default defineComponent({
    setup() {
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);

        function handleSendReminder() {
            if(checkedRowKeysRef.value.length < 1) {
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
                    if (result.isConfirmed){
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