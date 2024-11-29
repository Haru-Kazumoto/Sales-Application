<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Aging Dashboard" />
        <div class="d-flex flex-column gap-3">
            <div class="d-flex gap-3 ">
                <n-select placeholder="Status Faktur" size="large" class="w-25" :options="statusInvoice"></n-select>
                <n-button type="primary" size="large" @click="handleExportInvoice">Export faktur hari ini</n-button>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                        Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }} </span>
                            <n-data-table :bordered="false" :columns="columns" :data="($page.props.invoices as any).data" :pagination="pagination"
                                @update:checked-row-keys="handleCheck" size="small" :row-key="rowKey" />
                            <n-button type="primary" class="ms-auto my-3" style="width: 15rem;" @click="handleSendReminder">
                                Send Reminder
                            </n-button>
                </div>
            </div>
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
import { useForm, usePage } from '@inertiajs/vue3';
import { Flash } from '../../../types/model';

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
        const page = usePage();
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);

        const form = useForm({
            aging_id: [] as any[]
        });

        function handleSendReminder() {
            if (checkedRowKeysRef.value.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak ada data yang dipilih!',
                    text: 'Minimal 1 data harus dipilih',
                });
            } else {
                Swal.fire({
                    title: 'Kirim pesan pengingat?',
                    text: 'Ini akan mengirimkan sebuah pesan pengingat ke customer dan salesman dengan format yang sudah di buat',
                    icon: 'question',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.post(route('aging-finance.send.message'), {
                            onSuccess: (page) => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pesan pengingat berhasil di kirim!',
                                    text: (page.props.flash as Flash).success
                                });
                            }
                        });
                    }
                });
            }
        }

        function handleExportInvoice() {
            const url = route('aging-finance.invoice.export');
            
            window.location.href = url;
        }

        const statusInvoice = [
            {label: "PAID", value: "PAID"},
            {label: "UNPAID", value: "UNPAID"},
            {label: "OVERDUE", value: "OVERDUE"},
            {label: "FULLY PAID", value: "FULLY PAID"},
            {label: "INSTALMENT", value: "INSTALMENT"},
        ]

        return {
            columns: createColumns(),
            handleExportInvoice,
            handleSendReminder,
            checkedRowKeys: checkedRowKeysRef,
            statusInvoice,
            pagination: {
                pageSize: 10
            },
            handleCheck(rowKeys: DataTableRowKey[]) {
                checkedRowKeysRef.value = rowKeys;
                
                // Update form.aging_id dengan id dari baris yang dipilih
                form.aging_id = rowKeys;
            },
            rowKey: (row) => row.id,
        }
    },
    components: {
        TitlePage
    }
})
</script>