<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Buat Surat Jalan" />
        <n-tabs type="line" animated>
            <n-tab-pane name="Surat Jalan DNP" tab="Surat Jalan DNP">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-data-table :bordered="false" size="small" :columns="columns"
                            :data="($page.props.customer_orders_dnp as any).data" />
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="Surat Jalan DKU" tab="Surat Jalan DKU">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-data-table :bordered="false" size="small" :columns="columns"
                            :data="($page.props.customer_orders_dku as any).data" />
                    </div>
                </div>
            </n-tab-pane>
        </n-tabs>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router, usePage } from '@inertiajs/vue3';
import { Transactions } from '../../types/model';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

function createColumns(): DataTableColumns<Transactions> {
    return [
        {
            title: "#",
            key: "index",
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: "TANGGAL CO",
            key: "customer_order_date",
            width: 200,
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
            width: 200,
        },
        {
            title: "GUDANG",
            key: "warehouse",
            width: 150,
            render(row) {
                const warehouse = row.transaction_details.find(data => data.category === "Warehouse")?.value
                
                return h(
                    NTag,
                    {
                        type: warehouse === "DNP" ? 'success' : 'info',
                        strong: true,
                    },
                    { default: () => warehouse}
                );
            }
        },
        {
            title: "SALESMAN",
            key: 'salesman',
            width: 150,
            render(row) {
                return row.transaction_details.find((data) => data.category === "Salesman")?.value;
            }
        },
        {
            title: "CUSTOMER",
            key: "customer_name",
            width: 200,
            render(rowData) {
                const customer_name = rowData.transaction_details.find((data) => {
                    return data.category === "Customer";
                });

                return customer_name?.value;
            },
        },
        {
            title: "TERMIN",
            key: "term_of_payment",
            width: 150,
            render(row) {
                return row.term_of_payment;
            }
        },
        {
            title: "DUE DATE",
            key: "due_date",
            width: 200,
            render(row) {
                return dayjs(row.due_date).format('dddd, D MMMM YYYY');
            }
        },
        {
            title: "TOTAL TRANSAKSI",
            key: "total",
            width: 200,
            render(rowData) {
                return formatRupiah(rowData.total ?? 0);
            },
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
                            router.visit(route('warehouse.create-travel-document', row.id), { method: 'get' });
                        }
                    },
                    { default: () => 'Buat Surat' }
                );
            }
        }
    ];
}

export default defineComponent({
    setup() {

        const page = usePage();

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