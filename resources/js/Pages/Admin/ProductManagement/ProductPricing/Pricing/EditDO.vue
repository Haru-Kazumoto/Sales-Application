<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-1">
            <TitlePage title="Update Harga Produk DO" subTitle="Pembuatan harga produk untuk delivery DO" />
            <PreviousButton route="admin.pricing.do" />
        </div>

        <div class="card shadow-sm border border-success-subtle">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Nama Barang</label>
                        <n-input size="large" placeholder="" v-model:value="productDetail.product_name"></n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Unit Barang</label>
                        <n-input size="large" placeholder="" disabled
                            v-model:value="productDetail.product_unit"></n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Kode Barang</label>
                        <n-input size="large" placeholder="" disabled
                            v-model:value="productDetail.product_code"></n-input>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border border-success-subtle">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.redemp_price" label="Harga Tebus" :required="true" />
                    </div>
                    <!-- <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Harga Trucking
                            <RequiredMark />
                        </label>
                        <n-select :options="deliveryRegionOptions" placeholder=""
                            v-model:value="form.transportation_cost" size="large"></n-select>
                    </div>

                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">OH Depo
                            <RequiredMark />
                        </label>
                        <n-select :options="dimensionOptions" v-model:value="form.oh_depo" placeholder=""
                            size="large"></n-select>
                    </div> -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.bad_debt" label="Bad Debt" :required="true" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.budget_marketing" label="Budget Marketing"
                            :required="true" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.margin_all_segment" label="Margin Normal"
                            :required="true" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.saving" label="Saving" :required="true" />
                    </div>
                    <div class="d-flex">
                        <div class="d-flex ms-auto gap-2">
                            <n-button type="info" @click="calculateRedempPrice" strong>Kalkulasi
                                Harga</n-button>
                            <n-button type="error" strong @click="clearPricing">Reset harga</n-button>
                        </div>
                    </div>



                    <!-- FOURTH ROW -->
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.all_segment_price" label="Harga All Segment"
                            :required="true" />
                    </div>
                    <n-divider></n-divider>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.rounded_all_segment_price"
                            label="Pembulatan Harga All Segment" :required="true" />
                    </div>
                    <div class="d-flex">
                        <n-button type="info" class="ms-auto" @click="calculateRoundedPrice"
                            :disabled="hasRounded">Kalkulasi
                            pembulatan</n-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex ms-auto">
            <n-button type="primary" size="medium" @click="handleSubmitPricing">Submit Harga Baru</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { defineComponent, ref, watch } from 'vue'
import TitlePage from '../../../../../Components/TitlePage.vue';
import CurrencyInput from '../../../../../Components/CurrencyInput.vue';
import PreviousButton from '../../../../../Components/PreviousButton.vue';
import { findValueGlobalElement, formatRupiah } from '../../../../../Utils/options-input.utils';
import { useNotification } from 'naive-ui';
import Swal from 'sweetalert2';

export default defineComponent({
    props: {
        utils: {
            type: Object,
            required: true,
        },
        data: {
            type: Object,
            required: true,
        }
    },
    setup(props) {
        const notification = useNotification();
        const productDetail = ref({
            product_name: props.data.name,
            product_unit: props.data.unit,
            product_code: props.data.code,
        });
        const form = useForm({
            redemp_price: props.data.redemp_price,
            retail_price: props.data.retail_price,
            grosir_price: props.data.grosir_price,
            end_user_price: props.data.end_user_price,
            all_segment_price: props.data.all_segment_price,
            transportation_cost: props.data.transportation_cost,
            oh_depo: props.data.oh_depo,
            budget_marketing: findValueGlobalElement(props.utils.global_element, 'BUDGET MARKETING'),
            bad_debt: findValueGlobalElement(props.utils.global_element, 'BAD DEBT'),
            saving: props.data.saving,
            margin_all_segment: props.data.margin_all_segment,
            rounded_all_segment_price: props.data.rounded_all_segment_price,
            delivery_type: 'DO',
            product_id: props.data.product_id,
        });
        const hasRounded = ref(false);

        watch(() => form.product_id, (id) => {
            const selectedProduct = productOptions.find(data => data.value === id);

            productDetail.value.product_name = selectedProduct?.label;
            productDetail.value.product_unit = selectedProduct?.unit;
            productDetail.value.product_code = selectedProduct?.code;
        });

        function calculateRoundedPrice() {
            const {
                all_segment_price,
                end_user_price,
                retail_price,
                grosir_price,
                margin_all_segment,
                rounded_all_segment_price,
            } = form;

            if (!form.rounded_all_segment_price) {
                notification.warning({ title: "Angka pembulatan kosong", closable: false, duration: 2000 });
                return;
            }

            // Perbarui harga berdasarkan nilai pembulatan yang dimasukkan oleh pengguna
            form.all_segment_price = Number(all_segment_price) + (Number(rounded_all_segment_price) || 0);
            form.margin_all_segment = Number(margin_all_segment) + (Number(rounded_all_segment_price) || 0);

            hasRounded.value = true;

            notification.success({ title: "Dibulatkan!", duration: 2500, closable: false });
        }

        function clearPricing() {
            form.reset();
        }

        function calculateRedempPrice() {
            const {
                transportation_cost, // harga angkutan
                oh_depo, //oh depo
                bad_debt, // bad debt
                budget_marketing, // Budget Marketing
                saving, // saving
                margin_all_segment, // margin normal
            } = form;

            if (!form.redemp_price) {
                notification.error({ title: "Harga tebus kosong!", duration: 3000, closable: false });
                return;
            }

            if (form.redemp_price) {
                form.redemp_price = Math.round(Number(form.redemp_price)); // Pembulatan ke bilangan bulat
            }

            if (form.redemp_price) {
                form.redemp_price = Math.trunc(form.redemp_price); // Membulatkan ke bilangan bulat dengan membuang koma
            }

            let redemp_price = form.redemp_price;

            // Hitung basePrice tanpa PPN
            const basePrice =
                redemp_price +
                (Number(transportation_cost) || 0) +
                (Number(oh_depo) || 0) +
                (Number(bad_debt) || 0) +
                (Number(budget_marketing) || 0) +
                (Number(saving) || 0) +
                (Number(margin_all_segment) || 0);

            const ppn =
                (Number(transportation_cost) || 0) +
                (Number(oh_depo) || 0) +
                (Number(bad_debt) || 0) +
                (Number(budget_marketing) || 0) +
                (Number(saving) || 0) +
                (Number(margin_all_segment) || 0);

            const resultPpn = Math.ceil(ppn * 0.11);

            form.all_segment_price = basePrice; // All Segment

            notification.success({ title: "Berhasil terkalkulasi!", duration: 2500, closable: false });
        }

        function handleSubmitPricing() {
            form.put(route('admin.pricing.do.update', props.data.id), {
                onSuccess: (page) => {
                    Swal.fire({
                        icon: "success",
                        title: (page.props.flash as any).success,
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                },
                onError: () => {
                    Swal.fire({
                        icon: 'error',
                        title: "Terjadi kesalahan :(",
                    });
                }
            });
        }

        const deliveryRegionOptions = props.utils.region_delivery.map((data) => ({
            label: `${data.region_name} - ${formatRupiah(data.region_price)}`,
            value: data.region_price
        }));

        const dimensionOptions = props.utils.dimensions.map((data) => ({
            label: `${data.dimention_name} - ${data.price_dimention}`,
            value: data.price_dimention
        }));

        return {
            form,
            productDetail,
            deliveryRegionOptions,
            dimensionOptions,
            calculateRedempPrice,
            clearPricing,
            handleSubmitPricing,
            calculateRoundedPrice,
            hasRounded
        }
    },
    components: {
        Head,
        TitlePage,
        CurrencyInput,
        PreviousButton
    }
})
</script>

<style scoped></style>