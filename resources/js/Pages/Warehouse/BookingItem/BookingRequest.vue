<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="BOOKING REQUEST" />
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns"
                    :data="($page.props.booking_request_order as any).data" size="small" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

function createColumns() {
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
            width: 250,
            render(row) {
                const date = row.transaction_details.find(data => data.category === "Booking Date")?.value;
                return dayjs(date).format('dddd, D MMMM YYYY HH:mm');
            }
        },
        {
            title: "NOMOR BOOKING",
            key: 'document_code',
            width: 200,
        },
        {
            title: "BARANG GUDANG",
            key: "warehouse",
            width: 150,
            render(row) {
                const warehouse = row.transaction_details.find(data => data.category === "Warehouse")?.value;

                return h(
                    NTag,
                    {
                        type: warehouse !== "DNP" ? 'info' : 'success',
                        strong: true,
                        bordered: true,
                    },
                    { default: () => warehouse}
                )
            }
        },
        {
            title: "SALESMAN",
            key: "salesman",
            width: 200,
            render(row) {
                return row.transaction_details.find(data => data.category === "Salesman")?.value;
            }
        },
        {
            title: "STATUS",
            key: "status",
            width: 200,
            render(row) {
                const status = row.transaction_details.find(data => data.category === "Check")?.value;

                let type;

                switch (status) {
                    case "PENDING":
                        type = "warning";
                        break;
                    case "APPROVED":
                        type = "success";
                        break;
                    case "REJECTED":
                        type = "error";
                        break;
                    case "RELEASE_CO":
                        type = "info";
                        break;
                    default:
                        type = "success";
                        break;
                }

                return h(
                    NTag, {
                    type,
                    bordered: true,
                    strong: true,
                },
                    { default: () => status }
                )
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
                            router.visit(route('warehouse.detail-booking-request', row.id));
                        },
                        size: 'small',
                    },
                    { default: () => 'Detail' }
                )
            }
        }
    ]
}

export default defineComponent({
    setup() {
        const page = usePage();
        const booking_request = page.props.booking_request_order as any[];
        console.log(booking_request);

        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>