<template>

    <Head title="Harga Pengiriman DO"></Head>
    <div class="d-flex flex-column gap-3 pb-3">
        <TitlePage title="Pengiriman DO" subTitle="Daftar produk yang memiliki harga untuk delivery DO" />

        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center">
                <div class="d-flex ms-auto">
                    <div class="row">
                        <div class="col-12 d-flex gap-2">
                            <n-input size="medium" placeholder="Nama Barang">
                                <template #prefix>
                                    <n-icon :component="Search24Filled" />
                                </template>
                            </n-input>
                            <n-tooltip trigger="hover">
                                <template #trigger>
                                    <n-button type="primary" color="#006B3F" strong
                                        @click="router.visit(route('admin.pricing.do.create'))">
                                        <template #icon>
                                            <n-icon :component="Add16Filled" />
                                        </template>
                                        Tambah Barang
                                    </n-button>
                                </template>
                                Tambah produk untuk pembuatan harga delivery ini
                            </n-tooltip>
                        </div>
                    </div>
                </div>
            </div>
            <n-data-table :columns :bordered="false" :data="product_prices.data" size="small" />

            <!-- drawer detail -->
            <DetailProduct v-model:active="active" :dataProduct />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue';
import TitlePage from '../../../../Components/TitlePage.vue';
import CurrencyInput from '../../../../Components/CurrencyInput.vue';
import { Head, router } from '@inertiajs/vue3';
import { Search24Filled, Add16Filled, BoxSearch20Regular } from '@vicons/fluent';
import { NButton, NIcon } from 'naive-ui';
import DetailProduct from '../../../../Components/DetailProduct.vue';
import Swal from 'sweetalert2';

export default defineComponent({
    props: {
        product_prices: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const active = ref(false);
        const dataProduct = ref({});

        function createColumns() {
            return [
                {
                    title: "DETAIL",
                    key: "detail",
                    width: 100,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: 'info',
                                strong: true,
                                onClick() {
                                    active.value = true;
                                    dataProduct.value = row;
                                }
                            },
                            {
                                icon: () =>
                                    h(
                                        NIcon,
                                        {}, { default: () => h(BoxSearch20Regular) }
                                    )
                            }
                        )
                    }
                },
                {
                    title: "NAMA PRODUK",
                    key: "product_name",
                    width: 200,
                },
                {
                    title: "KEMASAN",
                    key: "unit",
                    width: 200,
                },
                {
                    title: "KODE PRODUK",
                    key: "code",
                    width: 200,
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 100,
                    render(row) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
                                NButton,
                                {
                                    strong: true,
                                    size: "medium",
                                    type: "primary",
                                    onClick() {
                                        router.visit(route('admin.pricing.do.edit', row.id));
                                    }
                                },
                                {
                                    default: () => "Edit"
                                }
                            ),
                            h(
                                NButton,
                                {
                                    strong: true,
                                    size: "medium",
                                    type: "error",
                                    onClick() {
                                        Swal.fire({
                                            title: "Hapus harga dari produk ini?",
                                            text: "Anda tidak dapat mengembalikan data yang telah dihapus",
                                            icon: "warning",
                                            showCancelButton: true,
                                            confirmButtonText: "Hapus",
                                            confirmButtonColor: 'red',
                                        }).then((result) => {
                                            if(result.isConfirmed) {
                                                router.delete(route('admin.pricing.do.delete', row.id), {
                                                    onSuccess: (page) => {
                                                        Swal.fire(page.props.flash.success,'','success');
                                                    }
                                                });
                                            }
                                        });
                                    }
                                },
                                {
                                    default: () => "Hapus"
                                }
                            )
                        ]);
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
            active,
            dataProduct,
            router,
            Search24Filled,
            Add16Filled
        }
    },
    components: {
        TitlePage,
        CurrencyInput,
        Head,
        DetailProduct
    }
})
</script>