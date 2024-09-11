<template>
    <div class="d-flex flex-column gap-4">
        <!-- Judul Halaman -->
        <TitlePage title="Barang Masuk Gudang" />

        <!-- Form Barang Masuk -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <form>
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
                </form>
            </div>
        </div>

        <!-- Tabel Barang -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :data="tableData" :pagination="pagination" />
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex justify-content-end">
            <n-button type="primary" size="large" @click="submitForm">
                Submit Stock Barang
            </n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { NButton, NInput, NDatePicker, NSelect, NDataTable } from 'naive-ui';
import TitlePage from '../../Components/TitlePage.vue';

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

        // Data dan kolom tabel
        const tableData = ref([
            {
                no: 1,
                kodeBarang: '12345678',
                namaBarang: 'SANIA PREMIUM MARGARINE',
                qtyPemesanan: 10,
                qtyBarangDatang: 10,
                selisih: 0,
                expiryDate: '01/01/25',
                keterangan: '-'
            },
            {
                no: 2,
                kodeBarang: '12345678',
                namaBarang: 'SANIA PREMIUM MARGARINE',
                qtyPemesanan: 10,
                qtyBarangDatang: 10,
                selisih: 0,
                expiryDate: '01/01/25',
                keterangan: '-'
            },


            {
                no: 3,
                kodeBarang: '12345678',
                namaBarang: 'SANIA PREMIUM MARGARINE',
                qtyPemesanan: 10,
                qtyBarangDatang: 10,
                selisih: 0,
                expiryDate: '01/01/25',
                keterangan: '-'
            },
            // ...data lainnya
        ]);

        const columns = [
            { title: 'No', key: 'no' },
            { title: 'Kode Barang', key: 'kodeBarang' },
            { title: 'Nama Barang', key: 'namaBarang' },
            { title: 'Qty Pemesanan', key: 'qtyPemesanan' },
            { title: 'Qty Barang Datang', key: 'qtyBarangDatang' },
            { title: 'Selisih', key: 'selisih' },
            { title: 'Expiry Date', key: 'expiryDate' },
            { title: 'Keterangan', key: 'keterangan' }
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
            tableData,
            columns,
            pagination,
            submitForm
        };
    },
    components: {
        TitlePage
    }
});
</script>

<style scoped>
.card {
    margin-bottom: 1rem;
}
</style>