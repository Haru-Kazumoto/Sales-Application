<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="STOK BARANG GUDANG " />
        <n-tabs type="line" animated>
            <n-tab-pane name="all" tab="Semua Produk">
                <div class="d-flex flex-column gap-2">
                    <div class="row g-3 ">
                        <div class="col-12 col-lg-6 ">
                            <span class="fs-4">Daftar Semua Produk</span>
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
                            <n-data-table :columns="columns" :data="($page.props.products as any).data"
                                :bordered="false" size="small" pagination-behavior-on-filter="first" />
                        </div>
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="by-batch" tab="Produk dengan batch code">
                <div class="d-flex flex-column gap-2">
                    <div class="row g-3 ">
                        <div class="col-12 col-lg-6 ">
                            <span class="fs-4">Daftar Produk Dengan Batch</span>
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
                            <n-data-table :columns="batchColumns" :data="($page.props.products_batch as any).data"
                                :bordered="false" size="small" pagination-behavior-on-filter="first" />
                        </div>
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="gap-products" tab="Selisih produk">
                <div class="d-flex flex-column gap-2">
                    <div class="row g-3 ">
                        <div class="col-12 col-lg-8">
                            <span class="fs-4">Daftar Selisih Produk</span>
                        </div>
                        <div class="col-12 col-lg-4 d-flex gap-3 ">
                            <n-input placeholder="Cari Nama Produk" size="large"/>
                        </div>
                    </div>
                    <div class="card shadow" style="border: none;">
                        <div class="card-body">
                            <n-data-table :columns="gapColumns" :data="($page.props.products_gap as any).data"
                                :bordered="false" size="small" pagination-behavior-on-filter="first" />
                        </div>
                    </div>
                </div>
            </n-tab-pane>
        </n-tabs>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { usePage } from '@inertiajs/vue3';

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
                    case 'HABIS':
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
                        strong: true,
                    },
                    { default: () => rowData.status }
                );
            },
        },

    ];
}

function createColumnsBatch() {
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
            width: 150,
        },
        {
            title: "KODE PRODUKSI BARANG",
            key: "batch_code",
            width: 250,
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
            title: 'STATUS',
            key: 'item_status',
            width: 100,
            render(rowData) {
                // Tentukan warna dan tipe tag berdasarkan item_status pembayaran
                let type: any;

                switch (rowData.status) {
                    case 'HABIS':
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
                        strong: true,
                    },
                    { default: () => rowData.status }
                );
            },
        },

    ];
}

function createColumnGapProducts() {
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
            title: "NAMA BARANG",
            key: "name",
            width: 250,
            render(row){
                return row.product?.name;
            }
        },
        {
            title: "KODE BARANG",
            key: "code",
            width: 250,
            render(row){
                return row.product.code;
            }
        },
        {
            title: "KEMASAN",
            key: "unit",
            width: 150,
            render(row) {
                return row.product.unit;
            }
        },
        {
            title: "SELISIH",
            key: "gap",
            width: 150,
            render(row) {
                return row.quantity;
            }
        },
        {
            title: "DESKRIPSI SELISIH",
            key: "description",
            width: 250,
            render(row) {
                return row.description;
            }
        },
        {
            title: "NOMOR SSO",
            key: "sso_number",
            width: 200,
        },
        {
            title: "NOMOR PO",
            key: "po_number",
            width: 200,
        }
    ]
}

export default defineComponent({
    setup() {
        const page = usePage();

        // console.log(page.props.products_gap);

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
        });

        return {
            pagination,
            columns: createColumns(),
            batchColumns: createColumnsBatch(),
            gapColumns: createColumnGapProducts(),
        };
    },
    components: {
        TitlePage,
        CountCard
    }
});
</script>

<style scoped></style>