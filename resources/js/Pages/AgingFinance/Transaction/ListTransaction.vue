<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Transaksi" />
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
            <div class="col-6 col-lg-2">
                <n-select size="large" style="width: 10rem;" placeholder="Payment Status" />
            </div>
        </div>
        <div class="card shadow border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="data" :row-class-name="rowClassName"/>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { formatRupiah } from '../../../Utils/options-input.utils';
import {router} from "@inertiajs/vue3";
import { OpenOutline } from '@vicons/ionicons5';

interface RowData {
    invoice_number: string;
    salesman: string;
    customer_name: string;
    term: string;
    due_date: string;
    total_bill: number;
    rest_bill: number;
    payment_status: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: "NO INVOICE",
            key: 'invoice_number',
            width: 200,
            render(row)  {
                return row.invoice_number;
            }
        },
        {
            title: "SALESMAN",
            key: 'salesman',
            width: 150,
            render(row) {
                return row.salesman;
            }
        },
        {
            title: "CUSTOMER",
            key: 'customer_name',
            width: 150,
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "TERMIN",
            key: 'term',
            width: 100,
            render(row) {
                return row.term;
            }
        },
        {
            title: "DUE DATE",
            key: 'due_date',
            width: 100,
            render(row) {
                return row.due_date;
            }
        },
        {
            title: "TOTAL TAGIHAN",
            key: 'total_bill',
            width: 150,
            render(row) {
                return formatRupiah(row.total_bill);
            }
        },
        {
            title: "SISA TAGIHAN",
            key: 'rest_bill',
            width: 150,
            render(row) {
                return formatRupiah(row.rest_bill);
            }
        },
        {
            title: "STATUS",
            key: 'payment_status',
            width: 150,
            render(row) {
                let type: string | any;

                switch(row.payment_status) {
                    case 'PAID':
                        type = 'success';
                        break;
                    case 'UNPAID':
                        type = 'error';
                        break;
                    case 'INSTALMENT':
                        type = 'warning';
                        break;
                    default: 
                        type = '';
                        break;
                }

                return h(
                    NTag,
                    {
                        type,
                    },
                    {default: () => row.payment_status}
                )
            }
        },
        {
            title: "ACTION",
            key: 'action',
            width: 100,
            render(row) {
                return h(
                    NButton,
                    {
                        type: 'info',
                        size: 'small',
                        renderIcon: () => h(OpenOutline),
                        onClick: () => {
                            router.visit(route('aging-finance.detail-transaction.pay'), {method: 'get'});
                        }
                    },
                )
            }
        }
    ];
}

const data: RowData[] = [
    {invoice_number: "004/CO-DKU/VI/24", salesman: "APRIYANTO", customer_name: "KHASANAH SARI", term: "14 HARI", due_date: "01/02/24", total_bill: 500000000, rest_bill: 100000000, payment_status: "PAID"},
    {invoice_number: "004/CO-DKU/VI/24", salesman: "APRIYANTO", customer_name: "KHASANAH SARI", term: "14 HARI", due_date: "01/02/24", total_bill: 500000000, rest_bill: 100000000, payment_status: "UNPAID"},
    {invoice_number: "004/CO-DKU/VI/24", salesman: "APRIYANTO", customer_name: "KHASANAH SARI", term: "14 HARI", due_date: "01/02/24", total_bill: 500000000, rest_bill: 100000000, payment_status: "UNPAID"},
    {invoice_number: "004/CO-DKU/VI/24", salesman: "APRIYANTO", customer_name: "KHASANAH SARI", term: "14 HARI", due_date: "01/02/24", total_bill: 500000000, rest_bill: 100000000, payment_status: "INSTALMENT"},
    {invoice_number: "004/CO-DKU/VI/24", salesman: "APRIYANTO", customer_name: "KHASANAH SARI", term: "14 HARI", due_date: "01/02/24", total_bill: 500000000, rest_bill: 100000000, payment_status: "INSTALMENT"},
]

export default defineComponent({
    setup() {
        // Fungsi untuk menentukan kelas CSS berdasarkan kondisi payment_status
        function rowClassName(row: RowData) {
            return row.payment_status === 'UNPAID' ? 'unpaid-data' : '';
        }

        return {
            columns: createColumns(),
            data,
            rowClassName,
            OpenOutline
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>
:deep(.unpaid-data td){
    color: red !important;
}
</style>