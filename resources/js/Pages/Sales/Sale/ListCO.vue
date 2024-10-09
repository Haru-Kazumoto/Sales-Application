<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List CO" />

        <div class="row g-2">
            <div class="col-5 col-lg-2">
                <n-select size="large" placeholder="" />
            </div>
            <div class="col-6 col-lg-4">
                <n-input size="large" placeholder="Cari apa yaaa" />
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.customer_orders as any).data"
                    size="small" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton } from 'naive-ui';
import { Transactions } from '../../../types/model.ts';
import { router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const page = usePage();

        function createColumns(): DataTableColumns<Transactions> {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 30,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "TANGGAL CO",
                    key: "customer_order_date",
                    width: 150,
                    render(rowData) {
                        const co_date = rowData.transaction_details.find((data) => {
                            return data.category === "CO Date";
                        });

                        return co_date?.value ? dayjs(co_date?.value).format('dddd, D MMMM YYYY') : '';
                    },
                },
                {
                    title: "NOMOR CO",
                    key: "document_code",
                    width: 100,
                },
                {
                    title: "CUSTOMER",
                    key: "customer_name",
                    width: 150,
                    render(rowData) {
                        const customer_name = rowData.transaction_details.find((data) => {
                            return data.category === "Customer";
                        });

                        return customer_name?.value;
                    },
                },
                {
                    title: "LEGALITAS",
                    key: "legality",
                    width: 100,
                    render(rowData) {
                        const legality = rowData.transaction_details.find((data) => {
                            return data.category === "Legality";
                        })

                        return legality?.value;
                    },
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 50,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "primary",
                                size: 'small',
                                onClick: () => {
                                    router.get(route('sales.detail-co', row.id));
                                }
                            },
                            { default: () => "Detail" }
                        )
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>