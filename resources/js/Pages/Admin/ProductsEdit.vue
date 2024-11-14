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
                        <label for="">Harga Jual Grosir<span class="text-danger">*</span></label>
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
                        <label for="">Harga Jual Bakery<span class="text-danger">*</span></label>
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
                        <n-button type="primary" class="ms-auto" attr-type="submit">Update Product</n-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { NButton, useNotification } from 'naive-ui';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Lookup, Products } from '../../types/model';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        const currentPage = ref(1);
        const page = usePage();
        const notification = useNotification();

        const product = page.props.product as any;

        const form = useForm({
            id: product.id,
            code: product.code || '',
            unit: product.unit || '',
            name: product.name || '',
            category: product.category || '',
            redemp_price: product.redemp_price || null as unknown as number,
            retail_price: product.retail_price || null as unknown as number,
            restaurant_price: product.restaurant_price || null as unknown as number,
            price_3: product.price_3 || null as unknown as number,
            dd_price: product.dd_price || null as unknown as number,
            normal_margin: product.normal_margin || null as unknown as number,
            oh_depo: product.oh_depo || null as unknown as number,
            saving: product.saving || null as unknown as number,
            bad_debt_dd: product.bad_debt_dd || null as unknown as number,
            saving_marketing: product.saving_marketing || null as unknown as number,
            product_type_id: product.product_type_id || null as unknown as number,
        });

        function handleSubmitProduct() {
            form.patch(route('admin.products.update', form.id), {
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