<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Surat Jalan" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="data" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns, NButton } from 'naive-ui';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';

interface RowData {
    co_date: string;
    co_number: string;
    salesman: string;
    customer_name: string;
    term: string;
    due_date: string;
    total_transaction: number;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: "#",
            key: "index",
            width: 50,
            render(row,index){
                return index + 1;
            }
        },
        {
            title: "TANGGAL CO",
            key: 'co_date',
            width: 150,
            render(row) {
                return row.co_date;
            }
        },
        {
            title: "NOMOR CO",
            key: "co_number",
            width: 150,
            render(row) {
                return row.co_number;
            }
        },
        {
            title: "Salesman",
            key: "salesman",
            width: 150,
            render(row) {
                return row.salesman;
            }
        },
        {
            title: "NAMA CUSTOMER",
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
            width: 150,
            render(row) {
                return row.due_date;
            }
        },
        {
            title: "TOTAL TRANSAKSI",
            key: 'total_transaction',
            width: 150,
            render(row) {
                return formatRupiah(row.total_transaction);
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
                        type: 'primary',
                        size: 'small',
                        onClick: () => {
                            router.visit(route('warehouse.create-travel-document'), {method: 'get'});
                        }
                    },
                    {default: () => 'Buat Surat'}
                );
            }
        }
    ];
}

const data: RowData[] = [
    {co_date: "04/04/2024", co_number: "004/CO-DNP/VI/24", salesman: "JUJU", customer_name: "SARI ROTI", term: "10 HARI", due_date: "01-01-2024", total_transaction: 500000},
    {co_date: "04/04/2024", co_number: "004/CO-DNP/VI/24", salesman: "JUJU", customer_name: "SARI ROTI", term: "10 HARI", due_date: "01-01-2024", total_transaction: 500000},
    {co_date: "04/04/2024", co_number: "004/CO-DNP/VI/24", salesman: "JUJU", customer_name: "SARI ROTI", term: "10 HARI", due_date: "01-01-2024", total_transaction: 500000},
    {co_date: "04/04/2024", co_number: "004/CO-DNP/VI/24", salesman: "JUJU", customer_name: "SARI ROTI", term: "10 HARI", due_date: "01-01-2024", total_transaction: 500000},
]

export default defineComponent({
    setup () {
        

        return {
            columns: createColumns(),
            data
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>

</style>