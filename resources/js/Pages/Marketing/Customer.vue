<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Customer" />
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-5">
                <form @submit.prevent="" class="d-flex flex-wrap gap-3">
                    <!-- Baris Pertama -->
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field1">Nama pelanggan</label>
                        <n-input id="field1" v-model:value="field1" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field2">Alamat</label>
                        <n-input id="field2" v-model:value="field2" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field3">Nomor telepon</label>
                        <n-input id="field3" v-model:value="field3" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field4">Segmen</label>
                        <n-input id="field4" v-model:value="field4" />
                    </div>
                    <!-- Baris Kedua -->
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field5">PIC</label>
                        <n-input id="field5" v-model:value="field5" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field6">Email</label>
                        <n-input id="field6" v-model:value="field6" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field7">Maksimum Order</label>
                        <n-input id="field7" v-model:value="field7" />
                    </div>
                    <div class="flex-grow-1" style="flex-basis: 23%;">
                        <label for="field8">Term of payment</label>
                        <n-input id="field8" v-model:value="field8" />
                    </div>
                </form>
                <n-button attr-type="submit" type="primary" class="ms-auto">Tambah pelanggan</n-button>
            </div>
        </div>

        <div class="d-flex flex-column gap-2">
            <span class="fs-5">Customer list</span>
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
import { CustomerList } from '../../types/dto';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';

const data: CustomerList[] = [
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
    {client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING"},
];

export default defineComponent({
    setup() {
        const notification = useNotification();

        function createColumns(): DataTableColumns<CustomerList> {
            return [
                {
                    title: 'No',
                    key: 'no',
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: 'Kode pelanggan',
                    key: 'client_code'
                },
                {
                    title: 'Nama Pelanggan',
                    key: 'customer_name'
                },
                {
                    title: 'Segmen',
                    key: 'segmen'
                },
                {
                    title: 'Alamat',
                    key: 'address'
                },
                {
                    title: 'Nomor telepon',
                    key: 'phone_number'
                },
                {
                    title: 'PIC',
                    key: 'pic'
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
                                            title: `Edit ${row.customer_name}`,
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
                                            text: `Delete ${row.customer_name}?`,
                                            showCancelButton: true,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                notification.success({
                                                    title: `${row.customer_name} has been deleted!`,
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