<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="List Faktur" />

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="($page.props.invoices as any).data" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup () {
        const page = usePage();
        console.log((page.props.invoices as any).data);

        function createColumns(){
            return [
                {
                    title: "#",
                    key: "index",
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "Nomor Faktur",
                    key: "document_code",
                    width: 150,
                },
                {
                    title: "Salesman",
                    key: "salesman",
                    width: 150,
                    render(row) {
                        return row.transaction_details.find(data => data.category === "Salesman")?.value;
                    }
                },
                {
                    title: "Customer",
                    key: 'customer',
                    width: 150,
                    render(row) {
                        return row.transaction_details.find(data => data.category === "Customer")?.value;
                    }
                },
                {
                    title: "Termin",
                    key: "term_of_payment",
                    width: 100,
                    render(row) {
                        return `${row.term_of_payment} HARI`;
                    }
                },
                {
                    title: "Due Date",
                    key: "due_date",
                    width: 200,
                    render(row) {
                        return dayjs(row.due_date).format('dddd, D MMMMYYYY ');
                    }
                },
                {
                    title: "Total Tagihan",
                    key: "total",
                    width: 250,
                }
            ]
        }

        return {
            columns: createColumns()
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>

</style>