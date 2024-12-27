<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Edit Produk" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.products'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitProduct">
                    
                    <!-- FIRST ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kode Barang
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Nama Produk
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kelompok
                            <RequiredMark />
                        </label>
                        <n-select size="large" placeholder="" :options="productTypeOptions"
                            v-model:value="form.product_type_id" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Pemasok
                            <RequiredMark />
                        </label>
                        <n-select placeholder="" v-model:value="form.supplier_id" size="large"
                            :options="supplierOptions" filterable/>
                    </div>

                    <!-- SECOND ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kemasan
                            <RequiredMark />
                        </label>
                        <n-select size="large" placeholder="" :options="unitOptions" v-model:value="form.unit" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga Tebus
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.redemp_price" @input="(value) => form.redemp_price = value.replace(/\./g, '').replace(',', '.')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga Trucking
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.transportation_cost"
                            @input="(value) => form.transportation_cost = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kategori Produk
                            <RequiredMark />
                        </label>
                        <n-select size="large" placeholder="" v-model:value="form.category"
                            :options="categoryProductOptions" />
                    </div>

                    <!-- THIRD ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">OH Depo
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.oh_depo"
                            @input="(value) => form.oh_depo = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Saving
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.saving"
                            @input="(value) => form.saving = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Bad Debt
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.bad_debt_dd"
                            @input="(value) => form.bad_debt_dd = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Saving Marketing
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.saving_marketing"
                            @input="(value) => form.saving_marketing = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>

                    <!-- FOURTH ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga Jual Grosir
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.retail_price"
                            @input="(value) => form.retail_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga Retail
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.restaurant_price"
                            @input="(value) => form.restaurant_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga End User
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.price_3"
                            @input="(value) => form.price_3 = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga All Segment
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.all_segment_price"
                            @input="(value) => form.all_segment_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga Jual DD
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.dd_price"
                            @input="(value) => form.dd_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Margin All Segment
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.normal_margin"
                            @input="(value) => form.normal_margin = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Margin End User
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.margin_end_user"
                            @input="(value) => form.margin_end_user = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Margin Retail
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.margin_retail"
                            @input="(value) => form.margin_retail = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Margin Grosir
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.margin_grosir"
                            @input="(value) => form.margin_grosir = value.replace(/\D/g, '')">
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
import { ArrowBack } from '@vicons/ionicons5';
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
            all_segment_price: product.all_segment_price || null as unknown as number,
            supplier_id: product.supplier_id || null as unknown as number,
            transportation_cost: product.transportation_cost || null as unknown as number,
            margin_end_user: product.margin_end_user || null as unknown as number,
            margin_retail: product.margin_retail || null as unknown as number,
            margin_grosir: product.margin_grosir || null as unknown as number,
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
            { label: "NON TEPUNG", value: "NON TEPUNG" },
            { label: "TEPUNG", value: "TEPUNG" },
        ]
        
        const supplierOptions = (page.props.suppliers as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        return {
            handleSubmitProduct,
            currentPage,
            unitOptions,
            productTypeOptions,
            categoryProductOptions,
            form,
            ArrowBack,
            supplierOptions,
            router
        }
    },
    components: {
        TitlePage
    }
})
</script>