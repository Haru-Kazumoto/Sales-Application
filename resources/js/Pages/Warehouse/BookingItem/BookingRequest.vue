<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="BOOKING REQUEST" />
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="data" size="small"/>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton } from 'naive-ui';

interface RowData {
    booking_date: string;
    booking_number: string;
    salesman: string;
    customer: string;
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
            title: "TANGGAL BOOKING",
            key: 'booking_date',
            width: 200,
            render(row) {
                return row.booking_date;
            }
        },
        {
            title: "NOMOR BOOKING",
            key: 'booking_number',
            width: 200,
            render(row) {
                return row.booking_number;
            }
        },
        {
            title: "SALESMAN",
            key: "salesman",
            width: 200,
            render(row) {
                return row.salesman;
            }
        },
        {
            title: "CUSTOMER",
            key: "customer",
            width: 200,
            render(row) {
                return row.customer;
            }
        },
        {
            title: "ACTION",
            key: 'action',
            width: 200,
            render(row) {
                return h(
                    NButton,
                    {
                        type: 'info',
                        onClick: () => {
                            alert(row.customer);
                        },
                        size: 'small',
                    },
                    {default: () => 'Detail'}
                )
            }
        }
    ]
}

const data: RowData[] = [
    {booking_date: "04-04-2006", booking_number: "004/CO-DKU/VI/24", salesman: "UDIN", customer: "ELUD BAKERY"},
    {booking_date: "04-04-2006", booking_number: "004/CO-DKU/VI/24", salesman: "UDIN", customer: "ELUD BAKERY"},
    {booking_date: "04-04-2006", booking_number: "004/CO-DKU/VI/24", salesman: "UDIN", customer: "ELUD BAKERY"}
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