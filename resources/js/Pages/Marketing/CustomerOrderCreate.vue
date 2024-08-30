<template>
    <div class="d-flex flex-column gap-4">
        <span class="fs-3">CUSTOMER ORDER</span>
        <div class="card shadow-sm" style="border: none;">
            <div class="card-body d-flex flex-row">
                <form @submit.prevent="" class="d-flex flex-wrap w-100 gap-3">
                    <!-- PELANGGAN -->
                    <div style="flex: 1 1 23%;">
                        <label for="pelanggan">Pelanggan</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 23%;">
                        <label for="label">Label</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 23%;">
                        <label for="pelanggan">No CO</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 23%;">
                        <label for="pelanggan">Sales</label>
                        <n-select :options="options" />
                    </div>
                    <!-- Baris Kedua -->
                    <div style="flex: 1 1 31%;">
                        <label for="pelanggan">Tanggal pembuatan CO</label>
                        <n-date-picker v-model:value="timestamp" type="date" />
                    </div>
                    <div style="flex: 1 1 31%;">
                        <label for="pelanggan">Termin</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 31%;">
                        <label for="pelanggan">Tanggal jatuh tempo</label>
                        <n-date-picker v-model:value="timestamp" type="date" />
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm" style="border: none">
            <div class="card-body d-flex flex-column gap-3">
                <span class="fw-medium fs-5">Detail Produk</span>
                <form @submit.prevent="" class="d-flex flex-wrap w-100 gap-3">
                    <!-- Baris Pertama -->
                    <div style="flex: 1 1 32%;">
                        <label for="pelanggan">Pelanggan</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 32%;">
                        <label for="label">Label</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 32%;">
                        <label for="pelanggan">No CO</label>
                        <n-select :options="options" />
                    </div>
                    <!-- Baris Kedua -->
                    <div style="flex: 1 1 32%;">
                        <label for="pelanggan">Sales</label>
                        <n-select :options="options" />
                    </div>
                    <div style="flex: 1 1 32%;">
                        <label for="pelanggan">Tanggal pembuatan CO</label>
                        <n-date-picker v-model:value="timestamp" type="date" />
                    </div>
                    <div style="flex: 1 1 32%;">
                        <label for="pelanggan">Termin</label>
                        <n-select :options="options" />
                    </div>
                </form>
                <n-button attr-type="submit" type="primary" class="ms-auto">Tambah produk</n-button>
            </div>
        </div>

        <div class="card shadow-sm" style="border: none;">
            <div class="card-body">
                <span class="fs-5 fw-medium">Daftar Produk</span>
                <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false" size="small"
                    pagination-behavior-on-filter="first" />
            </div>
        </div>

        <div class="card shadow-sm" style="border: none;">
            <div class="card-body">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>Rp.100.000</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Discount</span>
                    <span>Rp.50.000</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>Rp.150.000</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top">
                    <span>Total harga</span>
                    <span>Rp 1.150.000</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-top fw-semibold text-body-tertiary">
                    <span>Term of payment</span>
                    <span>14 Hari</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-semibold text-body-tertiary">
                    <span>Due date</span>
                    <span>17 Agustus 2024</span>
                </div>
            </div>
        </div>
        <n-button attr-type="submit" type="primary" class="mb-5">Submit</n-button>
    </div>
</template>

<script lang="ts">
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { defineComponent, ref, h } from 'vue';
import { ProductList } from '../../types/dto';
import Swal from 'sweetalert2';

const data: ProductList[] = [
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 },
    { product_name: "Sania Premium Margarine", amount: 10, unit: "Karton", price: "Rp 200.000", discount: 1000, net: 1000, total: 1000 }
]

export default defineComponent({
    setup() {
        const notification = useNotification();

        function createColumns(): DataTableColumns<ProductList> {
            return [
                {
                    title: 'No',
                    key: 'no',
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: 'Product',
                    key: 'product_name'
                },
                {
                    title: 'Jumlah',
                    key: 'amount'
                },
                {
                    title: 'Satuan',
                    key: 'unit'
                },
                {
                    title: 'Harga',
                    key: 'price'
                },
                {
                    title: 'Discount',
                    key: 'discount'
                },
                {
                    title: 'Net',
                    key: 'net'
                },
                {
                    title: 'total',
                    key: 'total'
                },
                {
                    title: 'Action',
                    key: 'actions',
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: 'error',
                                size: 'small',
                                onClick: () => {
                                    Swal.fire({
                                        icon: 'question',
                                        text: `Delete ${row.product_name}?`,
                                        showCancelButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            notification.success({
                                                title: `${row.product_name} has deleted!`,
                                                closable: true,
                                                keepAliveOnHover: false,
                                                duration: 2000,
                                            });
                                        }
                                    })
                                }
                            },
                            { default: () => 'Delete' }
                        )
                    }
                }
            ]
        }

        return {
            timestamp: ref(1183135260000),
            data,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            options: [
                {
                    label: 'Everybody\'s Got Something to Hide Except Me and My Monkey',
                    value: 'song0',
                    disabled: true
                },
                {
                    label: 'Drive My Car',
                    value: 'song1'
                },
                {
                    label: 'Norwegian Wood',
                    value: 'song2'
                },
            ]
        }
    }
});
</script>

<style scoped></style>