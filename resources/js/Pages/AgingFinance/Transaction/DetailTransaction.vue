<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Detail Transaksi" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('aging-finance.list-transaction'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <!-- ROW 1 -->
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>

                    <!-- ROW 2 -->
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>

                    <!-- ROW 3 -->
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCTS TABLE -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" />
            </div>
        </div>

        <div class="card shadow-sm border-0 ">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total Harga</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="row g-2">
                    <div class="col-7 col-lg-8 d-flex flex-column text-body-tertiary">
                        <span>Harap ditransfer ke : </span>
                        <span>PT. DANITAMA NIAGAPRIMA</span>
                        <span>NPWP: 01.345.766.7-064.000</span>
                        <span>BANK CENTRAL ASIA CAB HASANUDIN JAKARTA A/C NO. 523.0300.200</span>
                        <span>BANK CENTRAL ASIA CAB JATIASIH JAKARTA A/C NO. 675.5010.255</span>
                    </div>
                    <div class="col-5 col-lg-4">
                        <div class="d-flex justify-content-between">
                            <span>Total Harga</span>
                            <span class="fw-bold">Rp 200.000</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Jatuh Tempo</span>
                            <span class="fw-bold">12 September 2024</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row g-2">
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Pembayaran</label>
                        <n-select />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Tanggal Pembayaran</label>
                        <n-date-picker />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Nominal Pembayaran</label>
                        <n-input />
                    </div>
                </div>
                <div class="d-flex ms-auto gap-4">
                    <n-button type="error" >Batal</n-button>
                    <n-button type="primary" >Bayar</n-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns } from 'naive-ui';
import {router} from "@inertiajs/vue3";
import {ArrowBack} from "@vicons/ionicons5";

interface RowData {
    item_code: string;
    item_name: string;
    quantity: number;
    package: string;
    price: number;
    total: number;
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
            key: 'item_code',
            width: 100,
            render(row) {
                return row.item_code;
            }
        },
        {
            title: "NAMA BARANG",
            key: 'item_name',
            width: 200,
            render(row) {
                return row.item_name;
            }
        },
        {
            title: "SATUAN",
            key: 'package',
            width: 100,
            render(row) {
                return row.package;
            }
        },
        {
            title: "HARGA",
            key: 'price',
            width: 100,
            render(row) {
                return row.price;
            }
        },
        {
            title: "TOTAL",
            key: "total",
            width: 100,
            render(row) {
                return row.total;
            }
        }
    ]
}

export default defineComponent({
    setup() {


        return {
            columns: createColumns(),
            ArrowBack,
            router,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>