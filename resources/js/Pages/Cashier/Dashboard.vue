<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Dashboard Kasir" />
        <div class="d-flex flex-column gap-2">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-3" style="border-color: orange;">
                        <div class="card-body">
                            <div class="card-title">TRANSAKSI INSTALMENT</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">{{ $page.props.count_instalment }}</span>
                                <!-- <n-button type="warning" @click="router.visit(route('aging-finance.list-invoice'))">LIHAT DETAIL</n-button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-3" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">TAGIHAN BELUM BAYAR</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">{{ $page.props.count_unpaid }}</span>
                                <!-- <n-button type="error" @click="router.visit(route('aging-finance.list-invoice'))">LIHAT DETAIL</n-button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-3" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">TAGIHAN JATUH TEMPO</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">{{ $page.props.count_due_date}}</span>
                                <!-- <n-button type="error" @click="router.visit(route('aging-finance.list-invoice'))">LIHAT DETAIL</n-button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-3" style="border-color: red;">
                        <div class="card-body">
                            <div class="card-title">TAGIHAN OVERDUE</div>
                            <div class="card-content d-flex flex-column gap-2">
                                <span class="fs-2 fw-bold">{{ $page.props.count_overdue}}</span>
                                <!-- <n-button type="error" @click="router.visit(route('aging-finance.list-invoice'))">LIHAT DETAIL</n-button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-12 col-lg-8 ">
                    <span class="fs-4">Daftar Transaksi</span>
                </div>
                <div class="col-12 col-lg-2 d-flex gap-3 ">
                    <n-select size="large" placeholder="Status" />
                </div>
                <div class="col-12 col-lg-2 d-flex gap-3 ">
                    <n-input placeholder="Cari Nama Customer" />
                </div>
            </div>
            <div class="card shadow" style="border: none;">
                <div class="card-body ">
                    <n-data-table :columns="columns" :data="$page.props.invoices.data" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        function createColumns(){
            return [
                {
                    title: "#",
                    key: "index",
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "Nomor Faktur",
                    key: "document_code",
                    width: 200,
                },
                {
                    title: "Salesman",
                    key: "salesman",
                    width: 200,
                    render(row) {
                        return row.transaction_details.find(data => data.category === "Salesman")?.value;
                    }
                },
                {
                    title: "Customer",
                    key: 'customer',
                    width: 200,
                    render(row) {
                        return row.transaction_details.find(data => data.category === "Customer")?.value;
                    }
                },
                {
                    title: "Termin",
                    key: "term_of_payment",
                    width: 200,
                    render(row) {
                        return `${row.term_of_payment} HARI`;
                    }
                },
                {
                    title: "Due Date",
                    key: "due_date",
                    width: 200,
                    render(row) {
                        return dayjs(row.due_date).format('dddd, D MMMMYYYY ');
                    }
                },
                {
                    title: "Total Tagihan",
                    key: "total",
                    width: 250,
                    render(row) {
                        return formatRupiah(row.total);
                    }
                },
                {
                    title: "Sisa Tagihan",
                    key: "total_left",
                    width: 250,
                    render(row) {
                        return formatRupiah(row.total_left);
                    }
                },
                {
                    title: "Status",
                    key: "status_payment",
                    width: 200,
                    render(row) {
                        let type;

                        switch(row.status_payment){
                            case "INSTALMENT":
                                type = "warning";
                                break;
                            case "PAID":
                                type = "success";
                                break;
                            case "UNPAID":
                                type = 'error';
                                break;
                            default:
                                type = '';
                                break;
                        }

                        return h(
                            NTag,
                            {
                                type,
                                strong: true,
                                size: 'large',
                            }, { default: () => row.status_payment}
                        )
                    }
                },
            ]
        }
        // Columns for DataTable
        const columns = createColumns();

        return {
            columns,
            router
        };
    },
    components: {
        TitlePage,
        CountCard
    }
});
</script>

<style scoped>
.row-overdue {
    color: red !important;
}
</style>
