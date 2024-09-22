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
                                <span class="fs-3 fw-bold">200</span>
                                <span>PRODUK</span>
                                <n-button type="primary">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: orange;">
                        <div class="card-body">
                            <div class="card-title">STOK HARUS DITAMBAH</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">10</span>
                                <span>PRODUK</span>
                                <n-button type="warning">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">STOK HABIS</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">200</span>
                                <span>PRODUK</span>
                                <n-button type="error">LIHAT DETAIL</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">BARANG EXPIRED</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-3 fw-bold">200</span>
                                <span>PRODUK</span>
                                <n-button type="error">LIHAT DETAIL</n-button>
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
                                <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false"
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
                                <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false"
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
import { DataTableColumns, NButton, NTag } from 'naive-ui';

interface RowData {
    item_name: string;
    package: string;
    stock: number;
    item_status: string;
    located: string;
}

function createColumns(): DataTableColumns<RowData> {
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
            key: 'item_name',
            width: 300,
            render(rowData) {
                return rowData.item_name;  // Menampilkan SKU
            },
        },
        {
            title: 'KEMASAN',
            key: 'package',
            width: 100,
            render(rowData) {
                return rowData.package;  // Menampilkan nama item
            },
        },
        {
            title: 'STOK',
            key: 'stock',
            width: 100,
            render(rowData) {
                return rowData.stock;  // Menampilkan nama supplier
            },
        },
        {
            title: 'ALOKASI',
            key: 'located',
            width: 100,
            render(rowData) {
                return rowData.located;
            }
        },
        {
            title: 'Status',
            key: 'item_status',
            width: 100,
            render(rowData) {
                // Tentukan warna dan tipe tag berdasarkan item_status pembayaran
                let type: any;

                switch (rowData.item_status) {
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
                    { default: () => rowData.item_status }
                );
            },
        },

    ];
}

export default defineComponent({
    setup() {
        // Data dummy untuk tabel
        const data: RowData[] = [
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
            { item_name: "BERUANG EMAS", package: "SAK", stock: 20, item_status: "TERSEDIA", located: "DNP" },
        ];

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
            pageCount: Math.ceil(data.length / 10),
            itemCount: data.length,
        });

        // Columns for DataTable
        const columns = createColumns();

        return {
            data,
            pagination,
            columns,
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
