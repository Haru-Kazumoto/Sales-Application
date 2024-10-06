<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Produk" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <!-- FIRST ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kode Barang</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Nama Produk</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kelompok</label>
                        <n-input size="large" placeholder="" />
                    </div>

                    <!-- SECOND ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Kemasan</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Tebus</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Margin Normal</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Kategori Produk</label>
                        <n-input size="large" placeholder="" />
                    </div>

                    <!-- THIRD ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">OH Depo</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Saving</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Bad Debt</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Saving Marketing</label>
                        <n-input size="large" placeholder="" />
                    </div>

                    <!-- FOURTH ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual Retail</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual Hotel & Resto</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual 3</label>
                        <n-input size="large" placeholder="" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual DD</label>
                        <n-input size="large" placeholder="" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Customer</label>
                <n-input :disabled="true" class="w-25"  />
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <div class="ms-auto d-flex align-items-lg-center gap-2 justify-content-lg-end w-100">
                    <label>Pencarian Data</label>
                    <n-input class="w-50" />
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns"/>
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { NButton } from 'naive-ui';

function createColumns() {
    return [
        {
            title: "#",
            key: 'index',
            render(row,index) {
                return index + 1;
            }
        },
        {
            title: "KODE BARANG",
            key: "code",
            width: 150
        },
        {
            title: "NAMA BARANG",
            key: 'name',
            width: 200,
        },
        {
            title: "SEG PRODUK",
            key: "segment",
            width: 150,
        },
        {
            title: "KATEGORI",
            key: "category",
            width: 100,
        },
        {
            title: "SATUAN",
            key: 'unit',
            width: 100,
        },
        {
            title: "PEMASOK",
            key: "supplier",
            width: 150,
        },
        {
            title: "HARGA TEBUS",
            key: 'redemption_price',
            width: 150,
        },
        {
            title: "HARGA JUAL RETAIL",
            key: "retail_selling_price",
            width: 150,
        },
        {
            title: "HARGA JUAL HOTEL & RESTO",
            key: "selling_price",
            width: 150,
        },
        {
            title: "HARGA 3",
            key: "price_3",
            width: 150,
        },
        {
            title: "HARGA DD",
            key: "price_dd",
            width: 150,
        },
        {
            title: "MARGIN NORMAL",
            key: "margin_normal",
            width: 100,
        },
        {
            title: "OH DEPO",
            key: "over_head_depo",
            width: 100,
        },
        {
            title: "SAVING",
            key: "saving",
            width: 100,
        },
        {
            title: "BAD DEBT DD",
            key: "bad_debt_dd",
            width: 100,
        },
        {
            title: "SAVING MARKETING",
            key: "sacving_marketing",
            width: 100,
        },
        {
            title: 'ACTION',
            key: "action",
            width: 100,
            render(row) {
                return h('div', {class: "d-flex gap-2"}, [
                    h(
                        NButton,{
                            type: 'info',
                            onClick() {
                                alert('clicked');
                            },
                        },
                        {defaul: () => 'UPDATE'}
                    ),
                    h(
                        NButton,{
                            type: 'error',
                            onClick() {
                                alert('clicked');
                            }
                        }
                    )
                ]);
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