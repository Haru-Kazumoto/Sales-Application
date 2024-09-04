<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Product" />
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-5">
                <form @submit.prevent="" class="d-flex flex-wrap gap-3">
                    <!-- Baris Pertama -->
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field1">Field 1</label>
                        <n-input id="field1" v-model:value="field1" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field2">Field 2</label>
                        <n-input id="field2" v-model:value="field2" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field3">Field 3</label>
                        <n-input id="field3" v-model:value="field3" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field4">Field 4</label>
                        <n-input id="field4" v-model:value="field4" />
                    </div>
                    <!-- Baris Kedua -->
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field5">Field 5</label>
                        <n-input id="field5" v-model:value="field5" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field6">Field 6</label>
                        <n-input id="field6" v-model:value="field6" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field7">Field 7</label>
                        <n-input id="field7" v-model:value="field7" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field8">Field 8</label>
                        <n-input id="field8" v-model:value="field8" />
                    </div>
                </form>
                <n-button attr-type="submit" type="primary" class="ms-auto">Tambah produk</n-button>
            </div>
        </div>

        <div class="d-flex flex-column gap-2">
            <span class="fs-5">Product list</span>
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="products" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { ProductList } from '../../types/dto';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';

const data: ProductList[] = [
    { sku: "123456789", product_name: "MONTANA", package: "25 KG", user_price: "Rp 100.000", retail_price: "Rp 100.000", restaurant_price: "Rp 100.000", stock: 25 },
    { sku: "123456789", product_name: "MONTANA", package: "25 KG", user_price: "Rp 100.000", retail_price: "Rp 100.000", restaurant_price: "Rp 100.000", stock: 25 },
    { sku: "123456789", product_name: "MONTANA", package: "25 KG", user_price: "Rp 100.000", retail_price: "Rp 100.000", restaurant_price: "Rp 100.000", stock: 25 },
    { sku: "123456789", product_name: "MONTANA", package: "25 KG", user_price: "Rp 100.000", retail_price: "Rp 100.000", restaurant_price: "Rp 100.000", stock: 25 },
    { sku: "123456789", product_name: "MONTANA", package: "25 KG", user_price: "Rp 100.000", retail_price: "Rp 100.000", restaurant_price: "Rp 100.000", stock: 25 },
    { sku: "123456789", product_name: "MONTANA", package: "25 KG", user_price: "Rp 100.000", retail_price: "Rp 100.000", restaurant_price: "Rp 100.000", stock: 25 },
];

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
                    title: 'SKU',
                    key: 'sku'
                },
                {
                    title: 'Nama Produk',
                    key: 'product_name'
                },
                {
                    title: 'Satuan',
                    key: 'package'
                },
                {
                    title: 'Harga user',
                    key: 'user_price'
                },
                {
                    title: 'Harga retail',
                    key: 'retail_price'
                },
                {
                    title: 'Harga Hotel/Restoran',
                    key: 'restaurant_price'
                },
                {
                    title: 'Stok',
                    key: 'stock'
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
                                        // Logika untuk edit produk
                                        notification.success({
                                            title: `Edit ${row.product_name}`,
                                            description: 'Edit action clicked',
                                            closable: true,
                                            keepAliveOnHover: false,
                                            duration: 2000,
                                        });
                                    }
                                },
                                { default: () => 'Edit' }
                            ),
                            h(
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
                                                    title: `${row.product_name} has been deleted!`,
                                                    closable: true,
                                                    keepAliveOnHover: false,
                                                    duration: 2000,
                                                });
                                            }
                                        });
                                    }
                                },
                                { default: () => 'Delete' }
                            )
                        ]);
                    }
                }
            ]
        }

        function handleSubmit() {
            Swal.fire({
                icon: 'success',
                title: 'Success submitting'
            });
        }

        return {
            handleSubmit,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            products: data
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>