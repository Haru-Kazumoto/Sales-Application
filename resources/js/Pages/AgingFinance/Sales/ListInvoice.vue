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
import { router, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia
import { formatRupiah } from '../../../Utils/options-input.utils';
import { h } from 'vue';
import { NButton, NTag } from 'naive-ui';

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
                    width: 200,
                },
                {
                    title: "Salesman",
                    key: "salesman",
                    width: 200,
                    render(row) {
                        return row.transaction_details.find(data => data.category === "Salesman")?.value;
                    }
                },
                {
                    title: "Customer",
                    key: 'customer',
                    width: 200,
                    render(row) {
                        return row.transaction_details.find(data => data.category === "Customer")?.value;
                    }
                },
                {
                    title: "Termin",
                    key: "term_of_payment",
                    width: 200,
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
                    render(row) {
                        return formatRupiah(row.total);
                    }
                },
                {
                    title: "Sisa Tagihan",
                    key: "total_left",
                    width: 250,
                    render(row) {
                        return formatRupiah(row.total_left);
                    }
                },
                {
                    title: "Status",
                    key: "status_payment",
                    width: 200,
                    render(row) {
                        let type;

                        switch(row.status_payment){
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
                            }, { default: () => row.status_payment}
                        )
                    }
                },
                {
                    title: "Aksi",
                    key: "action",
                    width: 100,
                    render(row, index) {
                        return h(
                            NButton,
                            {
                                type: "primary",
                                size: 'medium',
                                onClick: () => {
                                    router.visit(route('aging-finance.detail-invoice', row.id),{method: 'get'});
                                }
                            },
                            { default: () => "Detail" }
                        )
                    }
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