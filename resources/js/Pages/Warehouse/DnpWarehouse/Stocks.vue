<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="Stok Barang Gudang" />
        <div class="d-flex align-items-center">
            <span class="fs-5">Daftar Produk</span>
            <div class="d-flex ms-auto gap-3 w-50">
                <n-select size="large" class="flex-fill" placeholder="Status"/>
                <n-input class="flex-fill" placeholder="Cari Produk"/>
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex">
                <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false" size="small"
                    pagination-behavior-on-filter="first" />
            </div>
        </div>
    </div>

</template>

<script lang="ts">
import { Link } from '@inertiajs/vue3';
import TitlePage from '../../../Components/TitlePage.vue';
import { defineComponent, reactive, h } from 'vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';

interface RowData {
    sku: string;
    item_name: string;
    supplier: string;
    package: string;
    stock: number;
    status: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: '#',
            key: 'index',
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'SKU',
            key: 'sku',
            render(rowData) {
                return rowData.sku;  // Menampilkan SKU
            },
        },
        {
            title: 'Nama Barang',
            key: 'item_name',
            render(rowData) {
                return rowData.item_name;  // Menampilkan nama item
            },
        },
        {
            title: 'Supplier',
            key: 'supplier',
            render(rowData) {
                return rowData.supplier;  // Menampilkan nama supplier
            },
        },
        {
            title: 'Package',
            key: 'package',
            render(rowData) {
                return rowData.package;  // Menampilkan jenis paket
            },
        },
        {
            title: 'Stock',
            key: 'stock',
            render(rowData) {
                return rowData.stock;  // Menampilkan jumlah stok
            },
        },
        {
            title: 'Status',
            key: 'status',
            render(rowData) {
                // Tentukan warna dan tipe tag berdasarkan status pembayaran
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
        {
            title: 'Action',
            key: 'actions',
            render(row) {
                return h('div', { class: 'd-flex gap-2' }, [
                    h(
                        NButton,
                        {
                            type: 'info',
                            size: 'small',
                            onClick: () => {
                                alert(`${row.item_name} is selected!`);
                            }
                        },
                        { default: () => 'Tambah' }
                    ),
                    h(
                        NButton,
                        {
                            type: 'primary',
                            size: 'small',
                            onClick: () => {
                                alert(`${row.item_name} is selected!`);
                            }
                        },
                        { default: () => 'Detail' }
                    )
                ]);
            }
        }
    ];
}

export default defineComponent({
    setup() {
        // Data dummy untuk tabel
        const data: RowData[] = [
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
            { sku: "123456789", item_name: "MONTANA", supplier: "PT. SRIBOGA RATUBAYA", package: "25 KG", stock: 24, status: "PERLU TAMBAH" },
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
        Link,
    }
});
</script>