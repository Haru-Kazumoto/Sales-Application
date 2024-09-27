<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="List Invoice DNP" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="data"/>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton } from 'naive-ui';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';

interface RowData {
    travel_letter_number: string;
    customer_order_number: string;
    salesman: string;
    customer_name: string;
    legality: string;
    total: number;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(row, index)  {
                return index + 1;
            }
        },
        {
            title: "NOMOR SURAT JALAN",
            key: 'travel_letter_number',
            width: 150,
            render(row) {
                return row.travel_letter_number
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: "customer_name",
            width: 150,
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "SALESMAN",
            key: "salesman",
            width: 100,
            render(row) {
                return row.salesman;
            }
        },
        {
            title: "LEGALITAS",
            key: "legality",
            width: 100,
            render(row) {
                return row.legality;
            }
        },
        {
            title: "TOTAL",
            key: "total",
            width: 100,
            render(row) {
                return formatRupiah(row.total);
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
                            router.visit(route('aging-finance.create-invoice-dnp'), {method: 'get'});
                        }
                    },
                    {
                        default: () => 'Buat Faktur'
                    }
                )
            }
        }
    ]
}

const data: RowData[] = [
    {travel_letter_number: "DNP/BKS/SJ/12395", customer_order_number: "004/CO-DNP/VI/24", salesman: "IJUL", customer_name: "SARI ROTI", legality: "CV", total: 600000},
    {travel_letter_number: "DNP/BKS/SJ/12395", customer_order_number: "004/CO-DNP/VI/24", salesman: "IJUL", customer_name: "SARI ROTI", legality: "CV", total: 600000},
    {travel_letter_number: "DNP/BKS/SJ/12395", customer_order_number: "004/CO-DNP/VI/24", salesman: "IJUL", customer_name: "SARI ROTI", legality: "CV", total: 600000},
    {travel_letter_number: "DNP/BKS/SJ/12395", customer_order_number: "004/CO-DNP/VI/24", salesman: "IJUL", customer_name: "SARI ROTI", legality: "CV", total: 600000},
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
