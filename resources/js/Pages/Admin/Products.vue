<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Produk" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitProduct">
                    <!-- FIRST ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kode Barang<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Nama Produk<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kelompok<span class="text-danger">*</span></label>
                        <n-select size="large" placeholder="" :options="productTypeOptions"
                            v-model:value="form.product_type_id" />
                    </div>

                    <!-- SECOND ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Kemasan<span class="text-danger">*</span></label>
                        <n-select size="large" placeholder="" :options="unitOptions" v-model:value="form.unit" />
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Tebus<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.redemp_price"
                            @input="(value) => form.redemp_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Margin Normal<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.normal_margin"
                            @input="(value) => form.normal_margin = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Kategori Produk<span class="text-danger">*</span></label>
                        <n-select size="large" placeholder="" v-model:value="form.category"
                            :options="categoryProductOptions" />
                    </div>

                    <!-- THIRD ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">OH Depo<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.oh_depo"
                            @input="(value) => form.oh_depo = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Saving<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.saving"
                            @input="(value) => form.saving = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Bad Debt<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.bad_debt_dd"
                            @input="(value) => form.bad_debt_dd = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Saving Marketing<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.saving_marketing"
                            @input="(value) => form.saving_marketing = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>

                    <!-- FOURTH ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual Retail<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.retail_price"
                            @input="(value) => form.retail_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual Hotel & Resto<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.restaurant_price"
                            @input="(value) => form.restaurant_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual 3<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.price_3"
                            @input="(value) => form.price_3 = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Harga Jual DD<span class="text-danger">*</span></label>
                        <n-input size="large" placeholder="" v-model:value="form.dd_price"
                            @input="(value) => form.dd_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="d-flex">
                        <n-button type="primary" class="ms-auto" attr-type="submit">Tambah Data</n-button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Customer</label>
                <span class="border px-4 py-1 bg-white" style="border-radius: 3px;">
                    {{ ($page.props.products as any).total }}
                </span>
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
                <n-data-table :bordered="false" :columns="columns" :data="$page.props.products.data" />
                <n-pagination v-model:page="currentPage" :page-count="$page.props.products.to"
                    :page-size="$page.props.products.per_page" @update:page="handlePageChange" />
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { NButton, useNotification } from 'naive-ui';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Lookup } from '../../types/model';
import { formatRupiah } from '../../Utils/options-input.utils';
import Swal from 'sweetalert2';



export default defineComponent({
    setup() {
        const currentPage = ref(1);
        const page = usePage();
        const notification = useNotification();

        const form = useForm({
            code: '',
            unit: '',
            name: '',
            category: '',
            redemp_price: null as unknown as number,
            retail_price: null as unknown as number,
            restaurant_price: null as unknown as number,
            price_3: null as unknown as number,
            dd_price: null as unknown as number,
            normal_margin: null as unknown as number,
            oh_depo: null as unknown as number,
            saving: null as unknown as number,
            bad_debt_dd: null as unknown as number,
            saving_marketing: null as unknown as number,
            product_type_id: null as unknown as number,
        });

        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 70,
                    render(row, index) {
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
                    render(row) {
                        return row.product_type.name
                    }
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
                        return h('div', { class: "d-flex gap-2" }, [
                            h(
                                NButton, {
                                type: 'info',
                                onClick() {
                                    router.get(route('admin.products.edit', row.id));
                                },
                            },
                                { default: () => 'UPDATE' }
                            ),
                            h(
                                NButton, {
                                type: 'error',
                                onClick() {
                                    Swal.fire({
                                        icon: 'question',
                                        title: `Hapus barang ${row.name} ? `,
                                        showCancelButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            router.delete(route('admin.products.delete', row.id), {
                                                onSuccess: () => {
                                                    notification.success({
                                                        title: "Barang berhasil dihapus",
                                                        duration: 1500,
                                                        closable: false,
                                                    })
                                                }
                                            });
                                        }
                                    })
                                }
                            }, { default: () => "HAPUS" }
                            )
                        ]);
                    }
                }
            ]
        }

        function handleSubmitProduct() {
            form.post(route('admin.products.post'), {
                onSuccess() {
                    form.reset();
                    notification.success({
                        title: page.props.flash.success,
                        duration: 1500,
                        closable: false,
                    });
                },
                onError() {
                    Swal.fire({
                        icon: "error",
                        title: "Isi form dengan tanda *",
                    });
                }
            });
        }

        function handlePageChange(page: number) {
            currentPage.value = page;
            router.get(route('admin.products'), { page }, { preserveState: true }); // Request data for the selected page
        }

        const unitOptions = (page.props.units as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const productTypeOptions = (page.props.product_type as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        const categoryProductOptions = [
            { label: "NT", value: "NT" },
            { label: "T", value: "T" },
        ]

        return {
            columns: createColumns(),
            handlePageChange,
            handleSubmitProduct,
            currentPage,
            unitOptions,
            productTypeOptions,
            categoryProductOptions,
            form
        }
    },
    components: {
        TitlePage
    }
})
</script>