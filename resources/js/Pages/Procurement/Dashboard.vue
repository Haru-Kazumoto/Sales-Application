<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Dashboard Procurement" />

        <!-- Row untuk card info -->
        <div class="row g-3">
            <!-- Total PO -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card shadow h-100 overflow-hidden gradient-effect">
                    <div class="card-body d-flex flex-column gap-1">
                        <h5 class="card-title">Total PO</h5>
                        <span class="fs-1 fw-bold">{{ $page.props.countTotalPo }}</span>
                        <n-button type="primary" size="small" @click="handleRedirect">Lihat Detail</n-button>
                    </div>
                </div>
            </div>

            <!-- Total Alokasi DNP -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card shadow h-100 overflow-hidden gradient-effect">
                    <div class="card-body d-flex flex-column gap-1">
                        <h5 class="card-title">Total Alokasi DNP</h5>
                        <span class="fs-1 fw-bold">{{ $page.props.countTotalLocatedDNP }}</span>
                        <n-button type="primary" size="small" @click="handleRedirect">Lihat Detail</n-button>
                    </div>
                </div>
            </div>

            <!-- Total Alokasi DKU -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card shadow h-100 overflow-hidden gradient-effect">
                    <div class="card-body d-flex flex-column gap-1">
                        <h5 class="card-title">Total Alokasi DKU</h5>
                        <span class="fs-1 fw-bold">{{ $page.props.countTotalLocatedDKU }}</span>
                        <n-button type="primary" size="small" @click="handleRedirect">Lihat Detail</n-button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Responsif -->
        <div class="card shadow" style="border: none;">
            <div class="card-body" >
                <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false" size="small"
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
import {router} from '@inertiajs/vue3';
import { formatRupiah } from '../../Utils/options-input.utils';

interface RowData {
    id: number;
    purchase_order_date: string;
    purchase_order_number: string;
    supplier: string;
    located: string;
    delivery_type: string;
    total_price: number;
}

function createColumns(): DataTableColumns<RowData> {
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
                return rowData.purchase_order_date;  // Menampilkan nomor faktur
            },
        },
        {
            title: 'Pemasok',
            key: 'supplier',
            width: 200,
            render(rowData) {
                return rowData.supplier;  // Menampilkan nama salesman
            },
        },
        {
            title: 'Alokasi',
            key: 'located',
            width: 200,
            render(rowData) {
                return rowData.located;  // Menampilkan nama pelanggan
            },
        },
        {
            title: 'Delivery',
            key: 'delivery_type',
            width: 200,
            render(rowData) {
                return rowData.delivery_type
            },
        },
        {
            title: 'Total Harga',
            key: 'total_price',
            width: 200,
            render(rowData) {
                return formatRupiah(rowData.total_price);  // Menampilkan jangka waktu pembayaran
            },
        },
    ];
}

const data: RowData[] = [
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
    {id: 1, purchase_order_date: '04-04-2006', purchase_order_number: '004/CO-DKU/VI/24', supplier: 'PT SRIBOGA', located: 'DNP', delivery_type: 'DEPO BEKASI', total_price: 500000000},
]

export default defineComponent({
    setup() {
        // const rowProps = (row: RowData) => {
        //     return {
        //         class: row.payment_status === 'OVERDUE' ? 'text-red' : '',
        //     };
        // };

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
            // pageCount: Math.ceil(data.length / 10),
            // itemCount: data.length,
        });

        function handleRedirect() {
            return router.visit(route('procurement.purchase-order-list'), {method: 'get'});
        }

        return {
            pagination,
            columns: createColumns(),
            handleRedirect,
            data
            // rowProps
        };
    },
    components: {
        TitlePage,
        CountCard
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
</style>
