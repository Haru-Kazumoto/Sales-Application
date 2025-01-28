<template>
    <div class="d-flex flex-column gap-4">

        <Head title="Edit Promo Beli" />
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Program Promo Beli" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.trade-promo.index'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            Akun Grosir
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.grosir_account"
                            @input="(value) => form.grosir_account = value.toUpperCase()" />
                        <!-- <ErrorInput :error="$page.props.errors['grosir_account']" /> -->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            Harga setelah diskon
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.discount_price"
                            @input="(value) => form.discount_price = value.replace(/\D/g, '')">
                            <template #prefix>Rp</template>
                        </n-input>
                        <!-- <ErrorInput :error="$page.props.errors['discount_price']" /> -->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            Jumlah Kuota
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.quota"
                            @input="(value) => form.quota = value.replace(/\D/g, '')" />
                        <!-- <ErrorInput :error="$page.props.errors['quota']" /> -->
                    </div>
                </div>

                <n-button class="ms-auto" type="primary" size="large" @click="handleUpdateTradePromo">Update Promo beli</n-button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="card-title">
                    <span class="fs-5 fw-semibold">Tambahkan Barang</span>
                </div>
                <div style="border: 1px solid grey;" class="my-3"></div>
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">NAMA BARANG</label>
                        <n-select placeholder="Pilih Barang" size="large" :options="productOptions" filterable
                            v-model:value="formAssignProducts.product_id" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">KODE BARANG</label>
                        <n-input placeholder="" size="large" disabled v-model:value="formAssignProducts.product_code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">SATUAN BARANG</label>
                        <n-input placeholder="" size="large" disabled v-model:value="formAssignProducts.product_unit" />
                    </div>
                </div>
                <div class="d-flex mt-3">
                    <n-button size="medium" type="primary" class="ms-auto" @click="assignProduct">
                        Tambah Barang
                    </n-button>
                </div>
            </div>
        </div>


        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <span class="fw-semibold">Data barang yang sudah ter-registrasi</span>
                <n-data-table :bordered="false" :columns :data="$page.props.registered_products" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import TitlePage from '../../../Components/TitlePage.vue';
import RequiredMark from '../../../Components/RequiredMark.vue';
import ErrorInput from '../../../Components/ErrorInput.vue';
import { defineComponent, ref, h } from 'vue';
import { NButton, NTag, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import { Flash } from '../../../types/model';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { ArrowBack } from '@vicons/ionicons5';
import { watch } from 'vue';

export default defineComponent({
    setup() {

        function createColumns() {
            return [
                {
                    title: "#",
                    key: "index",
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "NAMA BARANG",
                    key: "name",
                    width: 200,
                },
                {
                    title: "KODE BARANG",
                    key: "code",
                    width: 150,
                },
                {
                    title: "SATUAN BARANG",
                    key: "unit",
                    width: 150
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 150,
                    render(row){
                        return h(NButton, {
                            type: 'error',
                            size: 'medium',
                            onClick: () => unassignProduct(row.name, row.id)
                        }, () => 'Hapus');
                    }
                }
            ]
        }

        const page = usePage();
        const trade_promo = page.props.tradePromo as any;


        const form = useForm({
            grosir_account: trade_promo.grosir_account,
            discount_price: trade_promo.discount_price,
            quota: trade_promo.quota,
        });

        const formAssignProducts = useForm({
            product_id: null as unknown as number,
            product_name: null as unknown as string,
            product_code: null as unknown as string,
            product_unit: null as unknown as string,
        });

        const productOptions = page.props.products.map((product: any) => ({
            label: product.name,
            value: product.id,
            unit: product.unit,
            code: product.code,
        }));

        watch(
            () => formAssignProducts.product_id,
            (value) => {
                const product = productOptions.find((product) => product.value === value);

                if (product) {
                    formAssignProducts.product_name = product.label;
                    formAssignProducts.product_code = product.code;
                    formAssignProducts.product_unit = product.unit;
                }
            }
        );

        function handleUpdateTradePromo() {
            Swal.fire({
                title: `Update trade promo ${trade_promo.grosir_account}?`,
                icon: 'question',
                showCancelButton: true,
            }).then((result) => {
                if(result.isConfirmed) {
                    form.put(route('admin.trade-promo.update', trade_promo.id), {
                        onSuccess: (page) => {
                            Swal.fire((page.props.flash as Flash).success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire((page.props.flash as Flash).failed, '', 'error');
                        }
                    });
                }
            });
        }

        function assignProduct() {
            if(formAssignProducts.product_id === null) {
                Swal.fire('Harap memilih produk', '', 'error');
                return;
            }

            Swal.fire({
                title: `Tambahkan produk ${formAssignProducts.product_name}?`,
                showCancelButton: true,
                icon: 'question' 
            }).then((result) => {
                if(result.isConfirmed) {
                    formAssignProducts.patch(route('admin.trade-promo.assign-product', trade_promo.id), {
                        onSuccess: (page) => {
                            formAssignProducts.reset();
                            Swal.fire((page.props.flash as Flash).success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire((page.props.flash as Flash).failed, '', 'error');
                        }
                    });
                }
            });
        }

        function unassignProduct(product_name: string, product_id: number) {
            Swal.fire({
                title: `Hapus ${product_name}?`,
                icon: 'question',
                showCancelButton: true,
            }).then((result) => {
                if(result.isConfirmed) {
                    router.patch(route('admin.trade-promo.unassign-product', trade_promo.id), {
                        product_id
                    },{
                        onSuccess: (page) => {
                            Swal.fire((page.props.flash as Flash).success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire((page.props.flash as Flash).failed, '', 'error');
                        }
                    });
                }
            });
        }

        return {
            columns: createColumns(),
            handleUpdateTradePromo,
            ArrowBack,
            router,
            trade_promo,
            form,
            productOptions,
            formAssignProducts,
            assignProduct,
            unassignProduct
        }
    },
    components: {
        Head,
        TitlePage,
        RequiredMark,
        ErrorInput,
        NButton,
        NTag
    }
})
</script>

<style scoped></style>