<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Surat Jalan" />

        <n-tabs type="line" animated>
            <n-tab-pane name="Surat Jalan DNP" tab="Surat Jalan DNP">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-data-table :bordered="false" size="small" :columns="columns" :data="$page.props.travel_documents_dnp" />
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="Surat Jalan DKU" tab="Surat Jalan DKU">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-data-table :bordered="false" size="small" :columns="columns" :data="$page.props.travel_documents_dku" />
                    </div>
                </div>
            </n-tab-pane>
        </n-tabs>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NBadge, NButton, NTag } from 'naive-ui';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { router, usePage } from '@inertiajs/vue3';
import { Transactions } from '../../../types/model';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia
import Swal from 'sweetalert2';

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
            title: "NOMOR SURAT JALAN",
            key: "document_code",
            width: 200,
            render(row) {
                return h(
                    NTag, {
                        type: 'info'
                    },
                    {default: () => row.document_code}
                )
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
            title: "NAMA EKSPEDISI",
            key: "delivery",
            width: 200,
            render(rowData) {
                return rowData.transaction_details.find((data) => data.category === "Delivery")?.value;
            }
        },
        {
            title: "NO POLISI",
            key: 'number_plate',
            width: 150,
            render(row) {
                return row.transaction_details.find((data) => data.category === "Number Plate")?.value;
            }
        },
        {
            title: "NAMA DRIVER",
            key: "driver",
            width: 150,
            render(row) {
                return row.transaction_details.find((data) => data.category === "Driver")?.value;
            }
        },
        {
            title: "Pengiriman",
            key: "warehouse",
            width: 200,
            render(row) {
                return row.transaction_details.find((data) => data.category === "Shipping Warehouse")?.value;
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
                            router.get(route('procurement.do.show', row.id));
                        }
                    },
                    { default: () => 'Detail' }
                );
            }
        }
    ];
}

export default defineComponent({
    setup() {

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