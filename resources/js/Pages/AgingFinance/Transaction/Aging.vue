<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Aging Dashboard" />
        <div class="row g-2">
            <div class="col-6 col-lg-2">
                <n-date-picker size="large" type="date" style="width: 10rem;" />
            </div>
            <div class="col-6 col-lg-2">
                <n-date-picker size="large" type="date" style="width: 10rem;" />
            </div>
            <div class="col-6 col-lg-2">
                <n-select size="large" style="width: 10rem;" placeholder="Customer" />
            </div>
            <div class="col-6 col-lg-2">
                <n-select size="large" style="width: 10rem;" placeholder="Salesman" />
            </div>
        </div>
        <div class="d-flex flex-column">
            <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }} </span>
                    <n-data-table :columns="columns" :data="data" :pagination="pagination" :row-key="rowKey"
                        @update:checked-row-keys="handleCheck" size="small" />
                    <n-button type="primary" class="ms-auto my-3" style="width: 15rem;" @click="handleSendReminder">Send
                        Reminder</n-button>
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
    invoice_date: string;
    invoice: string;
    invoice_no: string;
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
            type: 'selection',
        },
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'TANGGAL FAKTUR',
            key: 'invoice_date',
            width: 150,
            render(rowData) {
                return rowData.invoice_date;  // Menampilkan tanggal faktur
            },
        },
        {
            title: 'FAKTUR',
            key: 'invoice',
            width: 100,
            render(rowData) {
                return rowData.invoice;  // Menampilkan faktur
            },
        },
        {
            title: 'NO FAKTUR',
            key: 'invoice_no',
            width: 150,
            render(rowData) {
                return rowData.invoice_no;  // Menampilkan No faktur
            },
        },
        {
            title: 'SALESMAN',
            key: 'salesman',
            width: 100,
            render(rowData) {
                return rowData.salesman;  // Menampilkan nama salesman
            },
        },
        {
            title: 'CUSTOMER',
            key: 'customer',
            width: 100,
            render(rowData) {
                return rowData.customer;  // Menampilkan nama customer
            },
        },
        {
            title: 'PAYMENT STATUS',
            key: 'payment_status',
            width: 100,
            render(rowData) {
                return rowData.payment_status;  // Menampilkan status pembayaran
            },
        },
        {
            title: 'TERM OF PAYMENT',
            key: 'term_of_payment',
            width: 100,
            render(rowData) {
                return rowData.term_of_payment;  // Menampilkan term of payment
            },
        },
        {
            title: 'DUE DATE',
            key: 'due_date',
            width: 100,
            render(rowData) {
                return rowData.due_date;  // Menampilkan due date
            },
        },
        {
            title: 'UMUR TRANSAKSI',
            key: 'transaction_age',
            width: 100,
            render(rowData) {
                return rowData.transaction_age;  // Menampilkan umur transaksi
            },
        }
    ];
}


const data: RowData[] = Array.from({ length: 10 }).map((_, index) => ({
    key: index + 1,
    invoice_date: `2024-09-${String(index + 1).padStart(2, '0')}`,
    invoice: `Faktur-${index + 1}`,
    invoice_no: `INV-2024-${index + 1}`,
    salesman: `Salesman ${index + 1}`,
    customer: `Customer ${index + 1}`,
    payment_status: index % 2 === 0 ? 'Paid' : 'Pending',
    term_of_payment: index % 2 === 0 ? '30 Days' : '60 Days',
    due_date: `2024-10-${String(index + 1).padStart(2, '0')}`,
    transaction_age: `${index + 1} Days`,
}));

export default defineComponent({
    setup() {
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);

        function handleSendReminder() {
            if (checkedRowKeysRef.value.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak ada data yang dipilih!',
                    text: 'Minimal 1 data harus dipilih',
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