<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Dashboard Warehouse" />
        <div class="d-flex flex-column gap-5">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: green;">
                        <div class="card-body">
                            <div class="card-title">STOK TERSEDIA</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">{{ ($page.props.stock_summary as any).available }}</span>
                                <span>PRODUK</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: orange;">
                        <div class="card-body">
                            <div class="card-title">STOK HARUS DITAMBAH</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">{{ ($page.props.stock_summary as any).need_to_add }}</span>
                                <span>PRODUK</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">STOK HABIS</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">{{ ($page.props.stock_summary as any).unavailable }}</span>
                                <span>PRODUK</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">BARANG EXPIRED</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">{{ $page.props.expired_count }}</span>
                                <span>PRODUK</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <n-tabs type="line" animated>
                <n-tab-pane name="Daftar Produk" tab="Produk">
                    <div class="d-flex flex-column gap-2">
                        <div class="row g-3 ">
                            <div class="col-12 col-lg-6 ">
                                <span class="fs-4">Daftar Produk</span>
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-select size="large" placeholder="Status" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                        </div>
                        <div class="card shadow" style="border: none;">
                            <div class="card-body">
                                <n-data-table :columns="columns" :data="($page.props.products as any).data" :pagination="pagination" :bordered="false"
                                    size="small" pagination-behavior-on-filter="first" />
                            </div>
                        </div>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="Daftar Produk Expired" tab="Produk Expired">
                    <div class="d-flex flex-column gap-2">
                        <div class="row g-3 ">
                            <div class="col-12 col-lg-6 ">
                                <span class="fs-4">Daftar Produk Expired</span>
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-select size="large" placeholder="Status" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                        </div>
                        <div class="card shadow" style="border: none;">
                            <div class="card-body">
                                <n-data-table :columns="expiredProductColumns" :data="$page.props.product_expireds" :pagination="pagination" :bordered="false"
                                    size="small" pagination-behavior-on-filter="first" />
                            </div>
                        </div>
                    </div>
                </n-tab-pane>
            </n-tabs>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { NTag } from 'naive-ui';
import dayjs from "dayjs";
import 'dayjs/locale/id';

dayjs.locale("id");

function createColumns() {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'NAMA BARANG',
            key: 'name',
            width: 300,
        },
        {
            title: 'KODE BARANG',
            key: 'code',
            width: 100,
        },
        {
            title: 'KEMASAN',
            key: 'unit',
            width: 100,
        },
        {
            title: 'STOK',
            key: 'last_stock',
            width: 100,
        },
        {
            title: 'ALOKASI',
            key: 'warehouse',
            width: 100,
        },
        {
            title: 'Status',
            key: 'item_status',
            width: 100,
            render(rowData) {
                // Tentukan warna dan tipe tag berdasarkan item_status pembayaran
                let type: any;

                switch (rowData.status) {
                    case 'STOK HABIS':
                        type = 'error';
                        break;
                    case 'PERLU TAMBAH':
                        type = 'warning';
                        break;
                    case 'TERSEDIA':
                        type = 'success';
                        break;
                    default:
                        type = '';
                }

                return h(
                    NTag,
                    {
                        style: {
                            marginRight: '6px',
                        },
                        type,
                        bordered: false
                    },
                    { default: () => rowData.status }
                );
            },
        },

    ];
}

function createColumnsExpiredProducts() {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'NAMA BARANG',
            key: 'name',
            width: 300,
        },
        {
            title: 'JUMLAH BARANG',
            key: 'quantity',
            width: 150,
        },
        {
            title: 'NOMOR SSO',
            key: 'sso_number',
            width: 150,
        },
        {
            title: 'TANGGAL MASUK GUDANG',
            key: 'warehouse_entry_date',
            width: 200,
            render(row) {
                //pakai dayjs
                return dayjs(row.warehouse_entry_date).format('dddd, D MMMMYYYY ');
            }
        },
        {
            title: 'TANGGAL EXPIRED',
            key: 'expiry_date',
            width: 100,
            render(rowData) {
                return h(
                    NTag,
                    {
                        style: {
                            marginRight: '6px',
                        },
                        type: 'error',
                        bordered: false
                    },
                    { default: () => dayjs(rowData.expiry_date).format('dddd, D MMMMYYYY ') }
                );
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
        });

        return {
            pagination,
            columns: createColumns(),
            expiredProductColumns: createColumnsExpiredProducts()
        };
    },
    components: {
        TitlePage,
        CountCard
    }
});
</script>

<style scoped>
.row-overdue {
    color: red !important;
}
</style>
