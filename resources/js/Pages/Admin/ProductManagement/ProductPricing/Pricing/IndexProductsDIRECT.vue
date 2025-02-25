<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-1">
            <TitlePage title="Daftar Produk"
                :subTitle="`Daftar produk yang terdaftar di sub delivery ${$page.props.subShipping.name}`" />
            <PreviousButton route="admin.pricing.direct" />
        </div>

        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center">
                <div class="d-flex ms-auto">
                    <div class="row">
                        <div class="col-12 d-flex gap-2">
                            <n-input size="medium" placeholder="Nama Barang" v-model:value="filterProduct.search" @keyup.enter="handleFilter">
                                <template #prefix>
                                    <n-icon :component="Search24Filled" />
                                </template>
                            </n-input>
                            <n-tooltip trigger="hover">
                                <template #trigger>
                                    <n-button type="primary" color="#006B3F" strong
                                        @click="router.visit(route('admin.pricing.direct.create', { subShipping: subShipping.id }))">
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
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column gap-2">
                    <n-data-table :columns :bordered="false" :data="product_prices?.data" size="small" />
                    <n-pagination class="ms-auto" v-if="product_prices?.total > product_prices?.per_page"
                        :page="product_prices?.current_page" :page-count="product_prices?.last_page"
                        @update:page="handleChangePage" />
                </div>
            </div>

            <!-- drawer detail -->
            <DetailProduct v-model:active="active" :dataProduct />
        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue';
import TitlePage from '../../../../../Components/TitlePage.vue';
import CurrencyInput from '../../../../../Components/CurrencyInput.vue';
import { Head, router } from '@inertiajs/vue3';
import { Search24Filled, Add16Filled, BoxSearch20Regular } from '@vicons/fluent';
import { NButton, NIcon } from 'naive-ui';
import DetailProduct from '../../../../../Components/DetailProduct.vue';
import Swal from 'sweetalert2';
import PreviousButton from '../../../../../Components/PreviousButton.vue';
import { formatRupiah } from '../../../../../Utils/options-input.utils';

export default defineComponent({
    props: {
        product_prices: { type: Object },
        subShipping: { type: Object }
    },
    setup(props) {
        const active = ref(false);
        const dataProduct = ref({});
        const filterProduct = ref({ search: '' });

        function handleFilter() {
            router.get(route("admin.pricing.direct-depo.index.products", props.subShipping?.id), {
                search_product: filterProduct.value.search, // Tetap kirim query pencarian
                page: 1 // Reset ke halaman pertama saat filter berubah
            }, {
                preserveState: true,
                replace: true
            });
        }

        function handleChangePage(page) {
            router.get(route("admin.pricing.direct-depo.index.products", props.subShipping?.id), {
                search_product: filterProduct.value.search, // Pastikan query tetap ada
                page: page
            }, {
                preserveState: true,
                replace: true
            });
        }

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
                    title: "HARGA TEBUS",
                    key: "redemp_price",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.redemp_price);
                    }
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
                                        router.visit(route('admin.pricing.direct.edit', row.id));
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
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.pricing.do.delete', row.id), {
                                                    onSuccess: (page) => {
                                                        Swal.fire(page.props.flash.success, '', 'success');
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
            Add16Filled,
            filterProduct,
            handleFilter,
            handleChangePage
        }
    },
    components: {
        TitlePage,
        CurrencyInput,
        Head,
        DetailProduct,
        PreviousButton
    }
})
</script>

<style scoped></style>