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
    travel_document_number: string;
    delivery_type: string;
    number_plate: string;
    driver_name: string;
    warehouse: string;
    customer_name: string;
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
            title: "NOMOR SURAT JALAN",
            key: 'travel_document_number',
            width: 150,
            render(row) {
                return row.travel_document_number;
            }
        },
        {
            title: "PENGIRIMAN",
            key: "delivery_type",
            width: 150,
            render(row) {
                return row.delivery_type;
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
            title: "GUDANG",
            key: 'warehouse',
            width: 100,
            render(row) {
                return row.warehouse;
            }
        },
        {
            title: "NAMA DRIVER",
            key: 'driver_name',
            width: 150,
            render(row) {
                return row.driver_name;
            }
        },
        {
            title: "NOMOR POLISI",
            key: 'number_plate',
            width: 150,
            render(row) {
                return row.number_plate;
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
                    {default: () => 'Detail'}
                );
            }
        }
    ];
}

const data: RowData[] = [
    {travel_document_number: "DNP/BKS/SJ/12605", customer_name: "ELUD BAKERY", delivery_type: "ARMADA OPERASIONAL", number_plate: "B 1234 KMY", driver_name: "YANTO", warehouse: "DEPO BEKASI"},
    {travel_document_number: "DNP/BKS/SJ/12605", customer_name: "ELUD BAKERY", delivery_type: "ARMADA OPERASIONAL", number_plate: "B 1234 KMY", driver_name: "YANTO", warehouse: "DEPO BEKASI"},
    {travel_document_number: "DNP/BKS/SJ/12605", customer_name: "ELUD BAKERY", delivery_type: "ARMADA OPERASIONAL", number_plate: "B 1234 KMY", driver_name: "YANTO", warehouse: "DEPO BEKASI"},
    {travel_document_number: "DNP/BKS/SJ/12605", customer_name: "ELUD BAKERY", delivery_type: "ARMADA OPERASIONAL", number_plate: "B 1234 KMY", driver_name: "YANTO", warehouse: "DEPO BEKASI"},
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