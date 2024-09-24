<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Sales Order" />
        <div class="d-flex flex-column gap-3">
            <div class="card shadow overflow-hidden" style="border: none">
                <div class="card-body">
                    <div class="row g-2">
                        <!-- Baris Pertama -->
                        <div class="col-6 col-lg-3">
                            <label for="field1">No PO</label>
                            <n-input id="field1" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field2">No Bukti</label>
                            <n-input id="field2" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field3">No SO</label>
                            <n-input id="field3" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field4">Tanggal PO</label>
                            <n-date-picker id="field4" />
                        </div>
                        <!-- Baris Kedua -->
                        <div class="col-6 col-lg-3">
                            <label for="field5">Alokasi</label>
                            <n-input id="field5" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field6">Pemasok</label>
                            <n-input id="field6" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field7">Gudang</label>
                            <n-input id="field7" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field8">Tanggal Kirim</label>
                            <n-date-picker id="field8" />
                        </div>
                        <!-- Baris Ketiga -->
                        <div class="col-6 col-lg-3">
                            <label for="field5">Transportasi</label>
                            <n-input id="field5" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field6">Pengirim</label>
                            <n-input id="field6" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field7">Jenis Pengiriman</label>
                            <n-input id="field7" />
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="field8">Karyawan</label>
                            <n-input id="field8" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table here -->
            <div class="card shadow overflow-hidden" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>

            <!-- Total data here -->
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2">
                        <span>Sub Total</span>
                        <span>Rp.100.000</span>
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
                        <div class="d-flex flex-column pe-3">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="Basic Textarea" style="width: 30rem;" />
                        </div>
                        <div class="d-flex flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold">14 HARI</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold">17 Agustus 2024</span>
                            </div>
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
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import TitlePage from '../../../Components/TitlePage.vue';
import { useForm } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';

interface RowData {
    product_code: '',
    product_name: '',
    amount: 0,
    package: '',
    product_price: 0,
    total_price: 0,
    ppn: 0,
}

export default defineComponent({
    setup() {
        const notification = useNotification();

        function createColumns(): DataTableColumns<RowData> {
            return [
                {
                    title: '#',
                    key: 'index',
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: 'KODE PRODUK',
                    key: 'product_code',
                    width: 200,
                    render(row) {
                        return row.product_code;
                    }
                },
                {
                    title: 'NAMA PRODUK',
                    key: 'product_name',
                    width: 200,
                    render(row) {
                        return row.product_name;
                    }
                },
                {
                    title: 'JUMLAH',
                    key: 'amount',
                    width: 200,
                    render(row) {
                        return row.amount;
                    }
                },
                {
                    title: 'SATUAN',
                    key: 'package',
                    width: 200,
                    render(row) {
                        return row.package;
                    }
                },
                {
                    title: 'HARGA',
                    key: 'product_price',
                    width: 200,
                    render(row) {
                        // Format product_price as Rupiah for display
                        return formatRupiah((row.product_price ?? 0));
                    }
                },
                {
                    title: 'PPN',
                    key: 'ppn',
                    width: 200,
                    render(row) {
                        // Format ppn as Rupiah for display
                        return formatRupiah((row.ppn ?? 0));
                    }
                },
                {
                    title: 'TOTAL HARGA',
                    key: 'total_price',
                    width: 200,
                    render(row) {
                        // Format total_price as Rupiah for display
                        return formatRupiah((row.total_price ?? 0));
                    }
                },
                {
                    title: 'ACTION',
                    key: 'actions',
                    width: 100,
                    render(row, index) {
                        return h('div', { class: 'd-flex gap-2' }, [
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
                                                // removeProduct(index);

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
                                { default: () => 'Hapus' }
                            )
                        ]);
                    }
                }
            ];
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
        }
    },
    components: {
        TitlePage
    }
})
</script>