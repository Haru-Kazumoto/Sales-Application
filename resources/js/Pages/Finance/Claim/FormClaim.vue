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
                        <label for="distributor name">Nama Distributor</label>
                        <n-input size="large" id="distributor name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="distributor name">Nama Distributor</label>
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
    faktur_number: number;
    customer_name: string;
    item_name: string;
    package: string;
    quantity: number;
    price: number; // exc ppn
    total: number;
    discount_local: number;
    total_discount_local: number;
    discount_ws: number;
    total_discount_ws: number;
    discount_ed: number;
    total_discout_ed: number;
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
            key: 'discount_local',
            width: 150,
            render(row) {
                return `${row.discount_local}%`;
            }
        },
        {
            title: "TOTAL DISKON LOKAL",
            key: 'total_discount_local',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_local);
            }
        },
        {
            title: "DISKON WS",
            key: 'discount_ws',
            width: 150,
            render(row) {
                return `${row.discount_ws}%`;
            }
        },
        {
            title: "TOTAL DISKON WS",
            key: 'total_discount_ws',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discount_ws);
            }
        },
        {
            title: "DISKON ED",
            key: 'discount_ed',
            width: 150,
            render(row) {
                return `${row.discount_ed}%`;
            }
        },
        {
            title: "TOTAL DISKON ED",
            key: 'total_discout_ed',
            width: 200,
            render(row) {
                return formatRupiah(row.total_discout_ed);
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
                                alert(`${row.key} is selected!`);
                            }
                        },
                        { default: () => 'Detail' }
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
        faktur_number: 1001,
        customer_name: "PT. Elektronik",
        item_name: "Laptop",
        package: "Box",
        quantity: 2,
        price: 10000000,
        total: 20000000,
        discount_local: 100000,
        total_discount_local: 200000,
        discount_ws: 50000,
        total_discount_ws: 100000,
        discount_ed: 30000,
        total_discout_ed: 60000,
        total_all: 19680000,
    },
    {
        key: 2,
        faktur_date: 2,
        faktur_number: 1002,
        customer_name: "CV. Teknologi",
        item_name: "Mouse",
        package: "Plastic",
        quantity: 10,
        price: 50000,
        total: 500000,
        discount_local: 5000,
        total_discount_local: 10000,
        discount_ws: 2000,
        total_discount_ws: 4000,
        discount_ed: 1000,
        total_discout_ed: 2000,
        total_all: 484000,
    },
    {
        key: 3,
        faktur_date: 4,
        faktur_number: 1003,
        customer_name: "PT. Komputer",
        item_name: "Keyboard",
        package: "Box",
        quantity: 3,
        price: 300000,
        total: 900000,
        discount_local: 5000,
        total_discount_local: 15000,
        discount_ws: 3000,
        total_discount_ws: 9000,
        discount_ed: 2000,
        total_discout_ed: 6000,
        total_all: 870000,
    },
    {
        key: 4,
        faktur_date: 19,
        faktur_number: 1004,
        customer_name: "UD. Perangkat",
        item_name: "Monitor",
        package: "Box",
        quantity: 1,
        price: 1500000,
        total: 1500000,
        discount_local: 10000,
        total_discount_local: 10000,
        discount_ws: 5000,
        total_discount_ws: 5000,
        discount_ed: 3000,
        total_discout_ed: 3000,
        total_all: 1472000,
    },
    {
        key: 5,
        faktur_date: 20,
        faktur_number: 1005,
        customer_name: "CV. Hardware",
        item_name: "Printer",
        package: "Box",
        quantity: 1,
        price: 2000000,
        total: 2000000,
        discount_local: 15000,
        total_discount_local: 15000,
        discount_ws: 7000,
        total_discount_ws: 7000,
        discount_ed: 5000,
        total_discout_ed: 5000,
        total_all: 1963000,
    }
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