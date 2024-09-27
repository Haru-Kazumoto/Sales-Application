<template>
    <div class="d-flex flex-column gap-4">
        <!-- Judul Halaman -->
        <TitlePage title="Barang Masuk Gudang" />

        <!-- Form Barang Masuk -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <form class="d-flex flex-column">
                    <!-- Grid untuk Input Form -->
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-4 mb-3">
                            <label for="no-po">No PO</label>
                            <n-input v-model="formData.noPo" id="no-po" label="No PO" size="large"
                                placeholder="PO/00027" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Tanggal PO</label>
                            <n-date-picker v-model="formData.pemasok" label="Pemasok" size="large" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Alokasi</label>
                            <n-input v-model="formData.tanggalMasukGudang" label="Tanggal Masuk Gudang" size="large" />
                        </div>

                        <!-- Kolom Tengah -->
                        <div class="col-md-4 mb-3">
                            <label for="">Pemasok</label>
                            <n-input v-model="formData.tanggalPO" size="large" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Gudang Pengirim</label>
                            <n-input v-model="formData.gudangPengirim" label="Gudang Pengirim" :options="gudangOptions"
                                size="large" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Tanggal Kirim Barang</label>
                            <n-date-picker v-model="formData.tanggalKirim" label="Tanggal Kirim Barang" type="date"
                                size="large" />
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-4 mb-3">
                            <label for="">Tanggal Masuk Gudang</label>
                            <n-date-picker v-model="formData.alokasi" label="Alokasi" :options="alokasiOptions"
                                type="date" size="large" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Transportasi</label>
                            <n-input v-model="formData.transportasi" label="Transportasi" size="large"
                                placeholder="TRUCK" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Pengirim</label>
                            <n-input v-model="formData.pengirim" label="Pengirim" size="large" placeholder="ABC" />
                        </div>
                    </div>
                    <n-button class="ms-auto" type="primary">Proses Barang</n-button>
                </form>
            </div>
        </div>

        <!-- Tabel Barang -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :data="data" :pagination="pagination" size="small"/>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex justify-content-end mb-4">
            <n-button type="primary" size="large" @click="submitForm">
                Submit Stock Barang
            </n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue';
import { NButton, NInput, NDatePicker, NSelect, NDataTable, DataTableColumns } from 'naive-ui';
import TitlePage from '../../Components/TitlePage.vue';

interface RowData {
    good_codes: string;
    good_name: string;
    request_quantity: number;
    incoming_quantity: number;
    difference: number;
    expiry_date: string;
    information: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: "KODE BARANG",
            key: "good_codes",
            width: 150,
            render(row) {
                return row.good_codes;
            }
        },
        {
            title: "NAMA BARANG",
            key: "good_name",
            width: 150,
            render(row) {
                return row.good_name;
            }
        },
        {
            title: "QTY PEMESANAN",
            key: 'request_quantity',
            width: 150,
            render(row) {
                return row.request_quantity;
            }
        },
        {
            title: "QTY BARANG DATANG",
            key: 'incoming_quantity',
            width: 150,
            render(row) {
                return row.incoming_quantity;
            }
        },
        {
            title: "SELISIH",
            key: 'difference',
            width: 100,
            render(row) {
                return row.difference;
            }
        },
        {
            title: "TANGGAL KADALUARSA",
            key: 'expiry_date',
            width: 150,
            render(row) {
                return row.expiry_date;
            }
        },
        {
            title: "KETERANGAN",
            key: 'information',
            width: 150,
            render(row) {
                return row.information;
            }
        },
        {
            title: "ACTION",
            key: 'action',
            width: 150,
            render(row) {
                return h(
                    NButton,
                    {
                        type: 'info',
                        size: 'small',
                        onClick: () => {
                            alert(row.good_name);
                        }
                    },
                    {default: () => 'Detail'}
                );
            }
        }
    ]
}

const data: RowData[] = [
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
    {good_codes: '123456789', good_name: 'SANIA PREMIUM', request_quantity: 10, incoming_quantity: 10, difference: 0, expiry_date: "04/04/06", information: '-'},
]

export default defineComponent({
    setup() {
        // Data untuk form
        const formData = ref({
            noPo: '',
            pemasok: '',
            tanggalMasukGudang: null,
            tanggalPO: null,
            gudangPengirim: null,
            tanggalKirim: null,
            alokasi: null,
            transportasi: '',
            pengirim: ''
        });

        // Pilihan dropdown
        const gudangOptions = [
            { label: 'PABRIK SRIBOGA', value: 'pabrik_sriboja' },
            { label: 'GUDANG A', value: 'gudang_a' }
        ];

        const alokasiOptions = [
            { label: 'DNP', value: 'dnp' },
            { label: 'Lainnya', value: 'lainnya' }
        ];

        const pagination = {
            page: 1,
            pageSize: 10,
            itemCount: 4 // total data count
        };

        // Fungsi submit
        const submitForm = () => {
            console.log('Form submitted', formData.value);
        };

        return {
            formData,
            gudangOptions,
            alokasiOptions,
            data,
            columns: createColumns(),
            pagination,
            submitForm
        };
    },
    components: {
        TitlePage
    }
});
</script>