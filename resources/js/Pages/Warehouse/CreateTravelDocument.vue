<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Surat Jalan" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('warehouse.travel-document'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <!-- row1 -->
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">No CO</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">No Surat Jalan</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Tanggal Surat Jalan</label>
                        <n-date-picker size="large" />
                    </div>
                    <!-- row2 -->
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Nama Customer</label>
                        <n-input size="large" />
                    </div>
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Gudang Pengiriman</label>
                        <n-select size="large" />
                    </div>
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Pengiriman</label>
                        <n-select size="large" />
                    </div>
                    <!-- row3 -->
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">No Polisi</label>
                        <n-select size="large" />
                    </div>
                    <div class="col-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Nama Driver</label>
                        <n-select size="large" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">Keterangan</label>
                        <n-input size="large" type="textarea" />
                    </div>
                </div>
            </div>
        </div>

        <!-- LIST PRODUCTS -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" />
            </div>
        </div>

        <n-button type="primary" size="medium" class="ms-auto mb-5">Buat Surat Jalan</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns } from 'naive-ui';
import { router } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';

interface RowData {
    item_code: string;
    item_name: string;
    quantity: string;
    package: string;
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
            key: "item_code",
            width: 150,
            render(row) {
                return row.item_code;
            }
        },
        {
            title: "NAMA BARANG",
            key: "item_name",
            width: 150,
            render(row) {
                return row.item_name;
            }
        },
        {
            title: "QTY",
            key: "quantity",
            width: 100,
            render(row) {
                return row.quantity;
            }
        },
        {
            title: "SATUAN",
            key: "package",
            width: 100,
            render(row) {
                return row.package;
            }
        }
    ]
}

export default defineComponent({
    setup() {


        return {
            columns: createColumns(),
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