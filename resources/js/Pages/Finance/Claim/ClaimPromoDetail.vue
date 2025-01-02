<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Detail Klaim Promo" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('finance.list-claim-promo'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NOMOR CLAIM</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">BULAN CLAIM</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NAMA DISTRIBUTOR</label>
                        <n-select size="large" />
                    </div>
                    <div class="col-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">AREA</label>
                        <n-select size="large" />
                    </div>
                    <div class="col-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">PROGRAM</label>
                        <n-select size="large" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow border-0 ">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Grand Total</span>
                    <span>Rp 200.000</span>
                </div>
            </div>
        </div>
        <div class="card shadow border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="data" :pagination="pagination"
                    :row-key="rowKey" size="small" />
            </div>
        </div>
        <div class="card shadow border-0">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row g-3">
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Status Pembayaran</label>
                        <n-select size="large" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Tanggal Pembayaran</label>
                        <n-date-picker size="large" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Nominal Pembayaran</label>
                        <n-input size="large" />
                    </div>
                </div>
                <n-button class="ms-auto" type="primary">Submit</n-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { NButton, type DataTableColumns } from 'naive-ui'
import { ArrowBack } from "@vicons/ionicons5";
import { formatRupiah } from '../../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';

interface RowData {
    key: number;
    faktur_date: number;
    faktur_number: string;
    customer_name: string;
    item_name: string;
    package: string;
    quantity: number;
    price: number; // exc ppn
    total: number;
    discount_1: number;
    total_discount_1: number;
    discount_2: number;
    total_discount_2: number;
    discount_3: number;
    total_discout_3: number;
    total_all: number;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1; // Untuk memberikan nomor urut baris
            },
        },
        {
            title: "TGL FAKTUR",
            key: 'faktur_date',
            width: 100,
            render(row) {
                return row.faktur_date;
            }
        },
        {
            title: "NO. FAKTUR",
            key: 'faktur_number',
            width: 160,
            render(row) {
                return row.faktur_number;
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: 'customer_name',
            width: 200,
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "NAMA ITEM",
            key: 'item_name',
            width: 250,
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
            title: "QTY",
            key: 'quantity',
            width: 100,
            render(row) {
                return row.quantity;
            }
        },
        {
            title: "HARGA",
            key: 'price',
            width: 200,
            render(row) {
                return formatRupiah(row.price);
            }
        },
        {
            title: "TOTAL",
            key: 'total',
            width: 200,
            render(row) {
                return formatRupiah(row.total);
            }
        },
        {
            title: "DISKON LOKAL",
            key: 'discount_1',
            width: 150,
            render(row) {
                return `${row.discount_1}%`;
            }
        },
        {
            title: "TOTAL DISKON LOKAL",
            key: 'total_discount_1',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_1);
            }
        },
        {
            title: "DISKON WS",
            key: 'discount_2',
            width: 150,
            render(row) {
                return `${row.discount_2}%`;
            }
        },
        {
            title: "TOTAL DISKON 2",
            key: 'total_discount_2',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_2);
            }
        },
        {
            title: "DISKON ED",
            key: 'discount_3',
            width: 150,
            render(row) {
                return `${row.discount_3}%`;
            }
        },
        {
            title: "TOTAL DISKON 3",
            key: 'total_discout_3',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discout_3);
            }
        },
        {
            title: "TOTAL AKHIR",
            key: 'total_all',
            width: 200,
            render(row) {
                return formatRupiah(row.total_all);
            }
        },
    ];
}

const data: RowData[] = [
    {
        key: 1,
        faktur_date: 1,
        faktur_number: "INV-2408-0001",
        customer_name: "PUTERA GRAHA S,PT",
        item_name: "SANIA PREMIUM MARGARINE",
        package: "KARTON",
        quantity: 2,
        price: 10000000,
        total: 20000000,
        discount_1: 11,
        total_discount_1: 200000,
        discount_2: 2,
        total_discount_2: 100000,
        discount_3: 6,
        total_discout_3: 60000,
        total_all: 19680000,
    },
    {
        key: 2,
        faktur_date: 1,
        faktur_number: "INV-2408-0001",
        customer_name: "PUTERA GRAHA S,PT",
        item_name: "SANIA PREMIUM MARGARINE",
        package: "KARTON",
        quantity: 2,
        price: 10000000,
        total: 20000000,
        discount_1: 11,
        total_discount_1: 200000,
        discount_2: 2,
        total_discount_2: 100000,
        discount_3: 1,
        total_discout_3: 60000,
        total_all: 19680000,
    },
];

export default defineComponent({
    setup() {


        return {
            data,
            columns: createColumns(),
            pagination: {
                pageSize: 10
            },
            rowKey: (row: RowData) => row.key,
            ArrowBack,
            router
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>