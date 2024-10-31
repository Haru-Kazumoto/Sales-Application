<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Booking Order DKU" />

        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center gap-3">
                <n-button type="primary" size="medium"
                    @click="router.visit(route('sales.booking-item.create-booking-dku'))">Buat Booking Order</n-button>
                <n-select size="medium" placeholder="Status" style="width: 10rem;" :options="filterStatus" v-model:value="statusBooking"/>
                <n-input class="w-25" placeholder="Nomor Booking" @input="handleSearch"
                    v-model:value="filterQuery" />
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <n-data-table :bordered="false" :columns="columns"
                        :data="($page.props.booking_request_products as any).data" />
                    <div class="d-flex mt-3">
                        <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                            :page-count="pagination.last_page" :page-size="pagination.per_page"
                            @update:page="handlePageChange"
                            @update:page-count="pagination.last_page = $page.props.booking_request_products.last_page" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, watch, reactive } from 'vue';
import TitlePage from "../../../../Components/TitlePage.vue";
import { router, usePage } from "@inertiajs/vue3";
import { NButton, NTag } from 'naive-ui';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        function createColumns() {
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
                    title: "TANGGAL BOOKING",
                    key: "booking_date",
                    width: 250,
                    render(row) {
                        const date = row.transaction.transaction_details.find(data => data.category === "Booking Date")?.value;

                        return dayjs(date).format('dddd, D MMMM YYYY HH:mm');
                    }
                },
                {
                    title: "NOMOR ORDER",
                    key: "document_code",
                    width: 150,
                    render(row) {
                        return row.transaction.document_code;
                    }
                },
                {
                    title: "NAMA PRODUK",
                    key: "product_name",
                    width: 150,
                    render(row) {
                        return row.product.name;
                    }
                },
                {
                    title: "QUANTITY",
                    key: "quantity",
                    width: 150,
                },
                {
                    title: "STATUS",
                    key: "status",
                    width: 150,
                    render(row) {
                        const status = row.status_booking;

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
                    title: "DESKRIPSI",
                    key: "description",
                    width: 200,
                    render(row) {
                        return row.reject_description;
                    }
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 150,
                    render(row, index) {
                        const status = row.status_booking;

                        return h('div', { class: 'd-flex gap-2' }, [
                            // h(
                            //     NButton,
                            //     {
                            //         type: "info",
                            //         size: 'small'
                            //     },
                            //     { default: () => "Detail" }
                            // ),
                            h(
                                NButton,
                                {
                                    type: "info",
                                    size: 'small',
                                    disabled: status !== "APPROVED",
                                },
                                { default: () => "BUAT CO" }
                            ),
                        ]);
                    }
                }
            ]
        }

        const page = usePage();

        // Filter data
        const filterField = ref('transaction.document_code'); // Default filter field
        const filterQuery = ref(''); // Filter input value
        const statusBooking = ref('ALL');

        // Fungsi untuk meng-handle pencarian
        function handleSearch() {
            router.get(route('sales.booking-item.index-booking-dnp'), {
                page: pagination.current_page,
                filter_field: filterField.value,
                filter_query: filterQuery.value,
                // status_booking: statusBooking.value
            }, {
                preserveState: true,
                replace: true
            });
        };

        // Function to handle page change
        function handlePageChange(page: number) {
            // currentPage.value = page;
            router.get(route('sales.booking-item.index-booking-dnp'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value,
                // status_booking: statusBooking.value
            }, { preserveState: true, replace: true }); // Request data for the selected page
        }

        const pagination = reactive({
            current_page: (page.props.booking_request_products as any).current_page,
            per_page: (page.props.booking_request_products as any).per_page,
            total: (page.props.booking_request_products as any).total,
            last_page: (page.props.booking_request_products as any).last_page,
        });

        const filterStatus = [
            { label: "SEMUA STATUS", value: "ALL"},
            { label: "REJECTED", value: "REJECTED"},
            { label: "APPROVED", value: "APPROVED"},
            { label: "PENDING", value: "PENDING"},
        ]

        return {
            columns: createColumns(),
            handleSearch,
            handlePageChange,
            filterQuery,
            pagination,
            statusBooking,
            filterStatus,
            router
        }
    },
    components: {
        TitlePage
    }
})
</script>