<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Retur Barang" />
        <div class="d-flex flex-column gap-3">
            <div class="card shadow" style="border: none">
                <div class="card-body d-flex flex-column">
                    <form @submit.prevent="" class="d-flex flex-wrap gap-3">
                        <!-- Baris Pertama -->
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field1">No PO</label>
                            <n-input id="field1" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field2">Tanggal PO</label>
                            <n-date-picker id="field2" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field3">No Retur</label>
                            <n-input id="field3" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field4">Pemasok</label>
                            <n-input id="field4" size="large" />
                        </div>
                        <!-- Baris Kedua -->
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field5">Tanggal Kirim Barang</label>
                            <n-date-picker id="field5" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field6">Transportasi</label>
                            <n-input id="field6" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field7">Tanggal kirim Retur</label>
                            <n-date-picker id="field7" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field8">Transportasi</label>
                            <n-input id="field8" size="large" />
                        </div>
                        <!-- Baris Ketiga -->
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field5">Pengirim</label>
                            <n-input id="field5" size="large" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow" style="border: none;">
                <div class="card-body d-flex flex-column">
                    <form @submit.prevent="" class="d-flex flex-wrap gap-3">
                        <!-- Baris Pertama -->
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field1">Kode Barang</label>
                            <n-input id="field1" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field2">Nama Barang</label>
                            <n-input id="field2" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field3">Jumlah</label>
                            <n-input id="field3" size="large" />
                        </div>
                        <!-- Baris Kedua -->
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field5">Kemasan</label>
                            <n-input id="field5" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field6">Total Harga Barang</label>
                            <n-input id="field6" size="large" />
                        </div>
                        <div class="flex-grow-1" style="flex-basis: 30%;">
                            <label for="field7">Alasan Retur</label>
                            <n-input id="field7" size="large" />
                        </div>
                        <n-button class="d-flex flex-row ms-auto" type="primary" ghost size="large">Tambah Produk
                            Retur</n-button>
                    </form>
                </div>
            </div>

            <!-- Table here -->
            <div class="card shadow" style="border: none; ">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>

            <!-- Total data here -->
            <div class="card shadow-sm border-0">
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
                    <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                        <span>Total harga</span>
                        <span>Rp 1.150.000</span>
                    </div>
                    <div class="d-flex py-2">
                        <div class="d-flex flex-column pe-3 gap-2">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="Catatan" style="width: 30rem;" />
                        </div>
                    </div>
                </div>
            </div>
            <n-button type="primary" class="d-flex flex-column ms-auto w-25 mb-4"
                @click="handleSubmit">SUBMIT</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import { CustomerList } from '../../../types/dto';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import TitlePage from '../../../Components/TitlePage.vue';

const data: CustomerList[] = [
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
    { client_code: "123456789", customer_name: "MONTANA", segmen: "Industri & UMKM", address: "Bekasi", phone_number: "123456789", pic: "DIDING" },
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
                                { default: () => 'Hapus' }
                            )
                        ]);
                    }
                }
            ]
        }

        function handleCreatePO() {
            notification.success({
                title: 'PO created successfully!',
                closable: true,
                keepAliveOnHover: false,
                duration: 2000,
            })
        }

        function handleSubmit() {
            Swal.fire({
                icon: 'success',
                title: 'Success submitting'
            });
        }

        return {
            handleSubmit,
            handleCreatePO,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            data
        }
    },
    components: {
        TitlePage
    }
})
</script>