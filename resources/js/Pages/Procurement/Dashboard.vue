<template>

    <Head title="Dashboard" />
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column my-2 ">
            <span class="fs-3 fs-md-4 fw-semibold">Dashboard Procurement</span>
        </div>
        <!-- Row untuk card info -->
        <div class="">
            <div class="row g-3 d-flex flex-nowrap align-items-stretch overflow-auto" id="horizontal-scroll" >
                <div class="col-12 col-lg-4">
                    <div class="card border-0 position-relative overflow-hidden h-100 shadow-sm">
                        <!-- h-100 agar card mengikuti tinggi 100% -->
                        <div class="card-body d-flex">
                            <div class="d-flex flex-column">
                                <div class="card-title d-flex gap-3 align-items-center">
                                    <n-icon-wrapper :size="40" :border-radius="10" color="#dfd4fa">
                                        <n-icon :size="18" :component="DocumentsOutline" color="#8c5cff" />
                                    </n-icon-wrapper>
                                    <span class="fw-semibold">TOTAL DOKUMEN<br>PO</span>
                                </div>
                                <div class="card-content">
                                    <span class="fs-1 fw-medium">{{ $page.props.total_po }}</span>
                                </div>
                            </div>
                            <n-image src="/images/po-card.png" width="150" class="position-absolute bottom-0 end-0"
                                preview-disabled />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 position-relative overflow-hidden h-100 shadow-sm">
                        <!-- h-100 agar card mengikuti tinggi 100% -->
                        <div class="card-body d-flex">
                            <div class="d-flex flex-column">
                                <div class="card-title d-flex gap-3 align-items-center">
                                    <n-icon-wrapper :size="40" :border-radius="10" color="#faf0d4">
                                        <n-icon :size="25" :component="WarehouseOutlined" color="#ffbe5c" />
                                    </n-icon-wrapper>
                                    <span class="fw-semibold">TOTAL ALOKASI <br>PO DNP</span>
                                </div>
                                <div class="card-content">
                                    <span class="fs-1 fw-medium">{{ $page.props.total_dnp_po }}</span>
                                </div>
                            </div>
                            <n-image src="/images/dnp-goods.png" width="150" class="position-absolute bottom-0 end-0 mb-3"
                                preview-disabled />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 position-relative overflow-hidden h-100 shadow-sm">
                        <!-- h-100 agar card mengikuti tinggi 100% -->
                        <div class="card-body d-flex">
                            <div class="d-flex flex-column">
                                <div class="card-title d-flex gap-3 align-items-center">
                                    <n-icon-wrapper :size="40" :border-radius="10" color="#fad4d4">
                                        <n-icon :size="18" :component="Box16Regular" color="#ff855c" />
                                    </n-icon-wrapper>
                                    <span class="fw-semibold">TOTAL ALOKASI<br>PO  DKU</span>
                                </div>
                                <div class="card-content mb-3">
                                    <span class="fs-1 fw-medium">{{ $page.props.total_dku_po }}</span>
                                </div>
                            </div>
                            <n-image src="/images/dku-goods.png" width="150" class="position-absolute bottom-0 end-0"
                                preview-disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tabel Responsif -->
        <div class="card border-0">
            <div class="card-body">
                <n-data-table :columns="columns" :data="$page.props.purchase_orders.data" :pagination="pagination" :bordered="false" size="small"
                    pagination-behavior-on-filter="first" />
            </div>
        </div>

    </div>


</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { DataTableColumns, NTag, RowProps } from 'naive-ui';
import { router, Head } from '@inertiajs/vue3';
import { formatRupiah } from '../../Utils/options-input.utils';
import { DocumentsOutline } from '@vicons/ionicons5';
import { WarehouseOutlined } from '@vicons/material';
import { Box16Regular } from "@vicons/fluent";
import {Transactions} from '../../types/model.ts';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

function createColumns(): DataTableColumns<Transactions> {
    return [
        {
            title: '#',
            key: 'index',
            width: 70,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'Tanggal PO',
            key: 'purchase_order_date',
            width: 200,
            render(rowData) {
                const data = rowData.transaction_details.find((data) => {return data.category === "PO Date"})?.value; 

                return dayjs(data).format('dddd, D MMMM YYYY');
            },
        },
        {
            title: 'Pemasok',
            key: 'supplier',
            width: 200,
            render(rowData) {
                return rowData.transaction_details.find((data) => {return data.category === "Supplier"})?.value;
            },
        },
        {
            title: 'Alokasi',
            key: 'located',
            width: 200,
            render(rowData) {
                const located = rowData.transaction_details.find((data) => {return data.category === "Allocation"})?.value;
                let type: any;

                switch(located){
                    case 'DNP':
                        type = "success";
                        break;
                    case 'DKU':
                        type = "info";
                        break;
                    case 'DD':
                        type = "warning";
                        break;
                    default:
                        type = 'default';
                }

                return h(
                    NTag,
                    {
                        type,
                        strong: true,
                    },
                    { default: () => located }
                );
            },
        },
        {
            title: 'Delivery',
            key: 'delivery_type',
            width: 200,
            render(rowData) {
                return rowData.transaction_details.find((data) => {return data.category === "Delivery Type"})?.value;  
            },
        },
        {
            title: 'Total Harga',
            key: 'total_price',
            width: 200,
            render(rowData) {
                return formatRupiah(rowData.total ?? 0);  // Menampilkan jangka waktu pembayaran
            },
        },
    ];
}

export default defineComponent({
    setup() {

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
            // pageCount: Math.ceil(data.length / 10),
            // itemCount: data.length,
        });

        function handleRedirect() {
            return router.visit(route('procurement.purchase-order-list'), { method: 'get' });
        }

        return {
            pagination,
            columns: createColumns(),
            handleRedirect,
            DocumentsOutline,
            WarehouseOutlined,
            Box16Regular
        };
    },
    components: {
        TitlePage,
        CountCard,
        Head
    }
});
</script>


<style scoped>
.gradient-effect {
    position: relative;
}

.gradient-effect::after {
    content: '';
    position: absolute;
    top: -30px;
    /* Ubah ke atas */
    right: -30px;
    /* Posisi kanan */
    width: 9rem;
    height: 9rem;
    /* Ukuran gradien */
    background-color: rgba(130, 249, 130, 0.27);
    /* background: linear-gradient(-90deg, rgba(2, 179, 67, 0.703), rgb(255, 255, 255)); */
    /* Warna gradien */
    border-bottom-left-radius: 100rem;
    z-index: 0;
    /* Pastikan gradien muncul di atas */
    pointer-events: none;
    /* Jangan mengganggu interaksi pengguna */
}

#horizontal-scroll {
    overflow-x: auto;
    /* Enable horizontal scrolling */
    scroll-snap-type: x mandatory;
    /* Enable scroll snapping */
    -webkit-overflow-scrolling: touch;
    /* Smooth scrolling for touch devices */
}

#horizontal-scroll .col-12 {
    min-width: 250px;
    /* Minimum width for each card to allow horizontal scroll */
    scroll-snap-align: start;
    /* Ensure card snaps into position when scrolling */
}

/* Hide scrollbar for Chrome, Safari, and Edge */
#horizontal-scroll::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge, and Firefox */
#horizontal-scroll {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}

@media (max-width: 576px) {
    #horizontal-scroll {
        display: flex;
        /* Make it a flexbox container */
        flex-wrap: nowrap;
        /* Prevent wrapping so cards scroll horizontally */
    }

    .col-12 {
        flex: 0 0 auto;
        /* Ensure cards don't shrink or grow */
    }
}
</style>