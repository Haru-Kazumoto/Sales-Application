<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="STOK BARANG EXPIRED GUDANG DNP" />
        <div class="d-flex flex-column gap-2">
            <div class="row g-3 ">
                <div class="col-12 col-lg-9 ">
                    <span class="fs-4">Daftar Produk Expired</span>
                </div>
                <div class="col-12 col-lg-3 d-flex gap-3 ">
                    <n-input size="large" placeholder="Nama Produk" />
                </div>
            </div>
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="$page.props.expiredProducts" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import TitlePage from '../../../Components/TitlePage.vue';
import CountCard from '../../../Components/CountCard.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import dayjs from "dayjs";
import 'dayjs/locale/id';

dayjs.locale('id');

function createColumns() {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'NAMA BARANG',
            key: 'name',
            width: 300,
        },
        {
            title: 'JUMLAH BARANG',
            key: 'quantity',
            width: 150,
        },
        {
            title: 'NOMOR SSO',
            key: 'sso_number',
            width: 150,
        },
        {
            title: 'TANGGAL MASUK GUDANG',
            key: 'warehouse_entry_date',
            width: 200,
            render(row) {
                //pakai dayjs
                return dayjs(row.warehouse_entry_date).format('dddd, D MMMMYYYY ');
            }
        },
        {
            title: 'TANGGAL EXPIRED',
            key: 'expiry_date',
            width: 200,
            render(rowData) {
                return h(
                    NTag,
                    {
                        style: {
                            marginRight: '6px',
                        },
                        type: 'error',
                        bordered: false
                    },
                    { default: () => dayjs(rowData.expiry_date).format('dddd, D MMMM YYYY ') }
                );
            },
        },
        {
            title: "ACTION",
            key: "action",
            width: 150,
            render(rowData) {
                return h(
                    NButton,
                    {
                        type: "error",
                        onClick: () => {
                        }
                    },
                    { default: () => 'PROSES' }
                );
            }
        }
    ];
}

export default defineComponent({
    setup() {

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
        });

        return {
            pagination,
            columns:  createColumns(),
            dayjs,
        };
    },
    components: {
        TitlePage,
        CountCard
    }
});
</script>

<style scoped></style>