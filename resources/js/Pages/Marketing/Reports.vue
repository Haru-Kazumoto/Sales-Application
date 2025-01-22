<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Marketing Reports" />

        <div class="d-flex flex-column gap-3">
            <div class="d-flex gap-3">
                <n-date-picker type="daterange" clearable size="large" />
                <n-button class="ms-auto" type="primary" size="large" @click="handleExportReport">Export Excel</n-button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <n-data-table :bordered="false" :columns="columns" :data="$page.props.data" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';

export default defineComponent({
    setup() {
        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "Tahun",
                    key: "tahun",
                    width: 100,
                },
                {
                    title: "Bulan",
                    key: "bulan",
                    width: 100,
                },
                {
                    title: "Salesman",
                    key: "salesman",
                    width: 200,
                },
                {
                    title: "Customer",
                    key: "customer",
                    width: 200,
                },
                {
                    title: "No. PO/CO",
                    key: "no_po_co",
                    width: 200,
                },
                {
                    title: "Produk",
                    key: "produk",
                    width: 200,
                },
                {
                    title: "Kemasan",
                    key: "kemasan",
                    width: 100,
                },
                {
                    title: "Golongan",
                    key: "golongan",
                    width: 200,
                },
                {
                    title: "Seg Produk",
                    key: "segmen",
                    width: 200,
                },
                {
                    title: "Delivery",
                    key: "delivery",
                    width: 200,
                },
                {
                    title: "Alokasi",
                    key: "alokasi",
                    width: 200,
                },
                {
                    title: "H. Tebus",
                    key: "harga_tebus",
                    width: 200,
                },
                {
                    title: "Angkutan",
                    key: "angkutan",
                    width: 200,
                },
                {
                    title: "Margin Normal",
                    key: "margin_normal",
                    width: 200,
                },
                {
                    title: "OH Depo",
                    key: "oh_depo",
                    width: 200,
                },
                {
                    title: "Cashback + PPH 4%",
                    key: "cashback_pph4",
                    width: 180,
                },
                {
                    title: "Bongkar",
                    key: "bongkar",
                    width: 200,
                },
                {
                    title: "Saving",
                    key: "saving",
                    width: 200,
                },
                {
                    title: "Bad Debt",
                    key: "bad_debt",
                    width: 200,
                },
                {
                    title: "Saving Mkt",
                    key: "saving_marketing",
                    width: 200,
                },
                {
                    title: "PPN 11%",
                    key: "ppn_11",
                    width: 100,
                },
                {
                    title: "HJ Normal",
                    key: "hj_normal",
                    width: 200,
                },
                {
                    title: "HJ Customer",
                    key: "hj_customer",
                    width: 200,
                },
                {
                    title: "Selisih (+/-)",
                    key: "selisih",
                    width: 200,
                },
                {
                    title: "Quantity",
                    key: "qty",
                    width: 100,
                },
                {
                    title: "Surplus/Defisit",
                    key: "surplus",
                    width: 200,
                },
                {
                    title: "Total H. Tebus",
                    key: "total_harga_tebus",
                    width: 200,
                },
                {
                    title: "Total Angkutan",
                    key: "total_harga_angkutan",
                    width: 200,
                },
                {
                    title: "Total OH",
                    key: "total_oh",
                    width: 200,
                },
                {
                    title: "Total Cashback",
                    key: "total_cashback",
                    width: 200,
                },
                {
                    title: "Total Bongkar",
                    key: "total_bongkar",
                    width: 200,
                },
                {
                    title: "Total BD",
                    key: "total_bd",
                    width: 200,
                },
                {
                    title: "Total Saving Mkt",
                    key: "total_saving_marketing",
                    width: 200,
                },
                {
                    title: "Total PPH",
                    key: "total_pph",
                    width: 200,
                },
                {
                    title: "Total Margin",
                    key: "total_margin",
                    width: 200,
                },
                {
                    title: "Total Nett Margin",
                    key: "total_nett_margin",
                    width: 200,
                },
                {
                    title: "Total Grand Omzet",
                    key: "total_grand_omzet",
                    width: 200,
                },
            ];
        }

        function handleExportReport() {
            const url = route('marketing.export-report');

            window.location.href = url;
        }


        return {
            columns: createColumns(),
            handleExportReport,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>