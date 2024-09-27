<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-1">
            <TitlePage title="Form Klaim" />
            <n-button text class="justify-content-start" @click="router.visit(route('finance.claim-promo'))">
                <template #icon>
                    <n-icon :component="ArrowBackOutline" />
                </template>
                Kembali
            </n-button>
        </div>
        <div class="card shadow" style="border: none">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="claim promo number">Nomor Klaim Promo</label>
                        <n-input size="large" id="claim promo number" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="month">Bulan</label>
                        <n-input size="large" id="month" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="distributor name">Nama Distributor</label>
                        <n-input size="large" id="distributor name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="distributor name">Area</label>
                        <n-input size="large" id="distributor name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="distributor name">Program</label>
                        <n-input size="large" id="distributor name" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow " style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :data="data" :columns="columns" size="small" />
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex">
                    <span class="fw-bold">SUB TOTAL</span>
                    <span class="ms-auto">Rp 180.000</span>
                </div>
                <div style="width: 100%; border: 1px solid grey;" />
                <div class="d-flex">
                    <span class="fw-bold">GRAND TOTAL</span>
                    <span class="ms-auto">Rp 880.000</span>
                </div>
            </div>
        </div>
        <n-button type="primary" class="mb-4 ms-auto w-25">Submit</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { DataTableColumns, NButton } from 'naive-ui';
import { ArrowBackOutline } from "@vicons/ionicons5";
import {router} from '@inertiajs/vue3';

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
        {
            title: 'ACTION',
            key: 'actions',
            render(row) {
                return h('div', { class: 'd-flex gap-2' }, [
                    h(
                        NButton,
                        {
                            type: 'error',
                            size: 'small',
                            onClick: () => {
                                alert(`${row.key} is selected!`);
                            }
                        },
                        { default: () => 'Delete' }
                    )
                ]);
            }
        }
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
            ArrowBackOutline,
            router          
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>