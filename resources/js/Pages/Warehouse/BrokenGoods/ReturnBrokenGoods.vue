<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="RETUR BARANG" />
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">No PO</label>
                            <n-input />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Tanggal PO</label>
                            <n-date-picker />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">No Pemusnahan</label>
                            <n-input />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <form @submit.prevent="" class="row g-3">
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Kode Barang</label>
                            <n-input />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Nama Barang</label>
                            <n-input />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Jumlah</label>
                            <n-input />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Kemasan</label>
                            <n-input />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Total Harga Barang</label>
                            <n-date-picker />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Alasan Retur</label>
                            <n-input />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <n-button color="green" attr-type="submit">Tambah Produk Retur</n-button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" />
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body ">
                <div class="row d-flex mb-2">
                    <div class="col-12 col-sm-7 col-lg-6">
                        <span class="fw-bold">SUB TOTAL</span>
                    </div>
                    <div class="col-12 col-sm-5 col-lg-6 d-flex">
                        <span class="ms-md-auto fw-semibold">Rp 800.000,-</span>
                    </div>
                </div>
                <div class="row d-flex">
                    <div class="col-12 col-sm-7 col-lg-6">
                        <span class="fw-bold">PPN 11%</span>
                    </div>
                    <div class="col-12 col-sm-5 col-lg-6 d-flex">
                        <span class="ms-md-auto fw-semibold">Rp 800.000,-</span>
                    </div>
                </div>
                <div style="border: 1px solid grey; margin: 1rem 0" />
                <div class="row d-flex">
                    <div class="col-12 col-sm-7 col-lg-6">
                        <span class="fw-bold">Total Harga</span>
                    </div>
                    <div class="col-12 col-sm-5 col-lg-6 d-flex">
                        <span class="ms-md-auto fw-semibold">Rp 1.800.000,-</span>
                    </div>
                </div>
                <div style="border: 1px solid grey; margin: 1rem 0" />
                <div class="d-flex flex-column gap-1 w-25">
                    <label for="">Catatan</label>
                    <n-input type="textarea" />
                </div>
            </div>
        </div>
        <n-button type="primary" class="mb-3 ms-auto w-25">Submit Retur</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton } from 'naive-ui';

interface RowData {
    item_code: string;
    item_name: string;
    amount: number;
    package: string;
    total: number;
    reason_return: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: 'Kode Barang',
            key: 'item_code',
            width: 150,
            render(row) {
                return row.item_code
            }
        },
        {
            title: 'Nama Barang',
            key: 'item_name',
            width: 200,
            render(row) {
                return row.item_name
            }
        },
        {
            title: 'Jumlah',
            key: 'amount',
            width: 100,
            render(row) {
                return row.amount;
            }
        },
        {
            title: 'Kemasan',
            key: 'package',
            width: 100,
            render(row) {
                return row.package;
            }
        },
        {
            title: 'Total Harga',
            key: 'total',
            width: 150,
            render(row) {
                row.total;
            }
        },
        {
            title: 'Alasan Retur',
            key: 'reason_return',
            width: 200,
            render(row) {
                return row.reason_return;
            }
        },
        {
            title: 'Action',
            key: 'action',
            width: 100,
            render(row) {
                return h(
                    NButton,
                    {
                        type: 'error',
                        onClick: () => {
                            alert('jir dihapus woilah');
                        }
                    },
                    { default: () => 'Hapus' }
                )
            }
        }
    ]
}

export default defineComponent({
    setup() {


        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>