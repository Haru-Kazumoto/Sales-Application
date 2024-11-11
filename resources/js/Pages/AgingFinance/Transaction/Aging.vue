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
                    <n-data-table :columns="columns" :data="($page.props.invoices as any).data" :pagination="pagination"
                        @update:checked-row-keys="handleCheck" size="small" />
                    <n-button type="primary" class="ms-auto my-3" style="width: 15rem;" @click="handleSendReminder">Send
                        Reminder</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { NTag, type DataTableColumns, type DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian 

function createColumns() {
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
            width: 250,
            render(row) {
                const date = row.transaction_details.find(data => data.category === "Invoice Date")?.value;  // Menampilkan tanggal faktur
                return dayjs(date).format('dddd, D MMMMYYYY ')
            },
        },
        {
            title: 'NO FAKTUR',
            key: 'document_code',
            width: 200,
        },
        {
            title: 'SALESMAN',
            key: 'salesman',
            width: 200,
            render(row) {
                return row.transaction_details.find(data => data.category === "Salesman")?.value;
            },
        },
        {
            title: 'CUSTOMER',
            key: 'customer',
            width: 250,
            render(row) {
                return row.transaction_details.find(data => data.category === "Customer")?.value;
            },
        },
        {
            title: 'TERM OF PAYMENT',
            key: 'term_of_payment',
            width: 200,     
            render(rowData) {
                return rowData.term_of_payment + " HARI";  // Menampilkan term of payment
            },
        },
        {
            title: 'DUE DATE',
            key: 'due_date',
            width: 200,
            render(row) {
                return dayjs(row.due_date).format('dddd, D MMMMYYYY ');  // Menampilkan due date
              },
        },
        {
            title: "Status",
            key: "status_payment",
            width: 200,
            render(row) {
                let type;

                switch (row.status_payment) {
                    case "INSTALMENT":
                        type = "warning";
                        break;
                    case "PAID":
                        type = "success";
                        break;
                    case "UNPAID":
                        type = 'error';
                        break;
                    default:
                        type = '';
                        break;
                }

                return h(
                    NTag,
                    {
                        type,
                        strong: true,
                        size: 'large',
                    }, { default: () => row.status_payment }
                )
            }
        },
    ];
}

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
            columns: createColumns(),
            handleSendReminder,
            checkedRowKeys: checkedRowKeysRef,
            pagination: {
                pageSize: 10
            },
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