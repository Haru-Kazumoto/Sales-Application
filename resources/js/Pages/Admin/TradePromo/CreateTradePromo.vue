<template>
    <div class="d-flex flex-column gap-4">

        <Head title="Buat Promo Beli" />
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
                <div class="card-title">
                    <span class="fs-5 fw-semibold">Informasi Trade Promo</span>
                    <div style="border: 1px solid grey; margin-top: 10px;"></div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            NAMA PELANGGAN
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.grosir_account"
                            @input="(value) => form.grosir_account = value.toUpperCase()" />
                        <!-- <ErrorInput :error="$page.props.errors['grosir_account']" /> -->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            HARGA TRADE PROMO
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
                            JUMLAH KUOTA
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.quota"
                            @input="(value) => form.quota = value.replace(/\D/g, '')" />
                        <!-- <ErrorInput :error="$page.props.errors['quota']" /> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column gap-3">
                <span class="fs-5 fw-semibold">Tambahkan Barang</span>
                <div style="border: 1px solid grey;"></div>
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">NAMA BARANG</label>
                        <n-select placeholder="Pilih Barang" size="large" :options="productOptions" filterable
                            v-model:value="assignProducts.product_id" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">KODE BARANG</label>
                        <n-input placeholder="" size="large" disabled v-model:value="assignProducts.product_code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">SATUAN BARANG</label>
                        <n-input placeholder="" size="large" disabled v-model:value="assignProducts.product_unit" />
                    </div>
                </div>
                <div class="d-flex mt-3">
                    <n-button size="medium" type="primary" class="ms-auto" @click="addProduct">
                        Tambah Barang
                    </n-button>
                </div>
                <span class="fw-semibold">Data barang yang sudah ter-registrasi</span>
                <n-data-table :bordered="false" :columns :data="form.products" />
            </div>
        </div>

        <n-button class="ms-auto mb-3" type="primary" size="large" @click="handleSubmitTradePromo">SUBMIT TRADE PROMO</n-button>
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
                    render(row, index) {
                        return h(NButton, {
                            type: 'error',
                            size: 'medium',
                            onClick: () => removeProduct(index)
                        }, () => 'Hapus');
                    }
                }
            ]
        }

        const page = usePage();
        const notification = useNotification();
        // const trade_promo = page.props.tradePromo as any;


        const form = useForm({
            grosir_account: null as unknown as string,
            discount_price: null as unknown as string,
            quota: null as unknown as string,
            products: [] as any[]
        });

        const assignProducts = ref({
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
            () => assignProducts.value.product_id,
            (value) => {
                const product = productOptions.find((product) => product.value === value);

                if (product) {
                    assignProducts.value.product_name = product.label;
                    assignProducts.value.product_code = product.code;
                    assignProducts.value.product_unit = product.unit;
                }
            }
        );

        function addProduct() {
            console.log(form.products);
            if (assignProducts.value.product_id === null) {
                notification.warning({
                    title: "Harap memilih produk terlebih dahulu",
                    closable: false,
                    duration: 2000
                });
                return;
            }

            // Cek apakah produk sudah ada di dalam array
            const isDuplicate = form.products.some(
                (product) => product.id === assignProducts.value.product_id
            );

            if (isDuplicate) {
                Swal.fire('Produk sudah ada di daftar', '', 'warning');
                return;
            }

            console.log(assignProducts.value);

            form.products.push({
                id: assignProducts.value.product_id,
                name: assignProducts.value.product_name,
                code: assignProducts.value.product_code,
                unit: assignProducts.value.product_unit,
            });

            assignProducts.value.product_id = null as unknown as any;
            assignProducts.value.product_name = null as unknown as any;
            assignProducts.value.product_code = null as unknown as any;
            assignProducts.value.product_unit = null as unknown as any;

            notification.success({
                title: "Produk berhasil ditambahkan",
                closable: false,
                duration: 2500
            });
            // notification.success('Berhasil menambahkan produk');
        }

        function removeProduct(index) {
            if (index >= 0 && index < form.products.length) {
                form.products.splice(index, 1); // Menghapus elemen berdasarkan index
                notification.success({
                    title: "Produk berhasil dihapus",
                    closable: false,
                    duration: 2500
                });
            } else {
                notification.error({
                    title: "Index tidak valid",
                    closable: false,
                    duration: 2500
                });
            }
        }

        function handleSubmitTradePromo() {
            form.post(route('admin.trade-promo.store'), {
                onSuccess: (page) => {
                    Swal.fire((page.props.flash as Flash).success, '', 'success');
                    form.reset();
                },
                onError: () => {
                    Swal.fire("Dimohon cek fom kembali", '', 'error');
                }
            });
        }

        // function assignProduct() {
        //     if(formAssignProducts.product_id === null) {
        //         Swal.fire('Harap memilih produk', '', 'error');
        //         return;
        //     }

        //     Swal.fire({
        //         title: `Tambahkan produk ${formAssignProducts.product_name}?`,
        //         showCancelButton: true,
        //         icon: 'question' 
        //     }).then((result) => {
        //         if(result.isConfirmed) {
        //             formAssignProducts.patch(route('admin.trade-promo.assign-product', trade_promo.id), {
        //                 onSuccess: (page) => {
        //                     formAssignProducts.reset();
        //                     Swal.fire((page.props.flash as Flash).success, '', 'success');
        //                 },
        //                 onError: () => {
        //                     Swal.fire((page.props.flash as Flash).failed, '', 'error');
        //                 }
        //             });
        //         }
        //     });
        // }

        // function unassignProduct(product_name: string, product_id: number) {
        //     Swal.fire({
        //         title: `Hapus ${product_name}?`,
        //         icon: 'question',
        //         showCancelButton: true,
        //     }).then((result) => {
        //         if(result.isConfirmed) {
        //             router.patch(route('admin.trade-promo.unassign-product', trade_promo.id), {
        //                 product_id
        //             },{
        //                 onSuccess: (page) => {
        //                     Swal.fire((page.props.flash as Flash).success, '', 'success');
        //                 },
        //                 onError: () => {
        //                     Swal.fire((page.props.flash as Flash).failed, '', 'error');
        //                 }
        //             });
        //         }
        //     });
        // }

        return {
            columns: createColumns(),
            addProduct,
            handleSubmitTradePromo,
            removeProduct,
            ArrowBack,
            router,
            // trade_promo,
            form,
            productOptions,
            assignProducts
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