<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-1">
            <TitlePage title="Penghargaan Produk DEPO" subTitle="Pembuatan harga produk untuk delivery DEPO" />
            <PreviousButton route="admin.pricing.depo" />
        </div>

        <div class="card shadow-sm border border-success-subtle">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Nama Barang</label>
                        <n-select size="large" placeholder="" :options="productOptions" filterable
                            v-model:value="form.product_id"></n-select>
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

        <div class="card shadow-sm border border-success-subtle mb-3">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitPricing">
                    <n-tabs type="segment" animated size="large" :theme-overrides="menuTheme">
                        <n-tab-pane name="forward-calculation" tab="PERHITUNGAN MAJU">
                            <div class="row g-2">
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.redemp_price" label="Harga Tebus"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Harga Trucking
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="deliveryRegionOptions" placeholder=""
                                        v-model:value="selectedPrice" size="large"></n-select>
                                </div>

                                <!-- THIRD ROW -->
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">OH Depo
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="dimensionOptions" v-model:value="form.oh_depo" placeholder=""
                                        size="large"></n-select>
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.bad_debt" label="Bad Debt"
                                        :required="true" />
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
                                    <n-button type="info" class="ms-auto" @click="calculateRedempPrice">Kalkulasi
                                        Harga</n-button>
                                </div>

                                <n-divider></n-divider>


                                <!-- FOURTH ROW -->
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.all_segment_price" label="Harga All Segment"
                                        :required="true" />
                                </div>
                                <n-divider></n-divider>
                            </div>

                        </n-tab-pane>
                        <n-tab-pane name="reverse-calculation" tab="PERHITUNGAN MUNDUR">
                            <div class="row g-2">
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.redemp_price" label="Harga Tebus"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Persentase Harga
                                        <RequiredMark />
                                    </label>
                                    <n-select size="large" placeholder="" clearable v-model:value="form.percentage"
                                        :options="percentageOptions" />
                                </div>
                                <n-divider></n-divider>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.all_segment_price"
                                        label="Harga Jual All Segment" :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.grosir_price" label="Harga Jual Grosir"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.retail_price" label="Harga Jual Retail"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.end_user_price" label="Harga Jual End User"
                                        :required="true" />
                                </div>

                                <!-- FOURTH ROW -->
                                <n-divider></n-divider>

                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Harga Trucking
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="deliveryRegionOptions" placeholder=""
                                        v-model:value="selectedPrice" size="large"></n-select>
                                </div>

                                <!-- THIRD ROW -->
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">OH Depo
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="dimensionOptions" v-model:value="form.oh_depo" placeholder=""
                                        size="large"></n-select>
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.bad_debt" label="Bad Debt"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.budget_marketing" label="Budget Marketing"
                                        :required="true" />
                                </div>

                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.saving" label="Saving" :required="true" />
                                </div>

                                <n-divider></n-divider>

                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.margin_all_segment"
                                        label="Margin All Segment" :required="true" />
                                </div>

                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.margin_grosir" label="Margin Grosir"
                                        :required="true" />
                                </div>

                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.margin_retail" label="Margin Retail"
                                        :required="true" />
                                </div>

                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.margin_end_user" label="Margin End User"
                                        :required="true" />
                                </div>
                            </div>
                            <div class="d-flex mt-3">
                                <n-button type="info" class="ms-auto" @click="calculateReversePrice">Kalkulasi
                                    Harga</n-button>
                            </div>
                        </n-tab-pane>
                    </n-tabs>



                    <h4>Pembulatan harga (opsional)</h4>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <CurrencyInput v-model:modelValue="form.rounded_all_segment_price"
                            label="Pembulatan Harga All " />
                    </div>
                    <div class="d-flex">
                        <n-button type="info" class="ms-auto" @click="calculateRoundedPrice"
                            :disabled="hasRounded">Kalkulasi
                            pembulatan</n-button>
                    </div>
                    <n-divider></n-divider>

                    <div class="d-flex">
                        <div class="ms-auto d-flex gap-3">
                            <n-button type="primary" attr-type="submit">Submit Harga</n-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, defineComponent, ref, watch } from 'vue'
import TitlePage from '../../../../../Components/TitlePage.vue';
import CurrencyInput from '../../../../../Components/CurrencyInput.vue';
import PreviousButton from '../../../../../Components/PreviousButton.vue';
import { findValueGlobalElement, formatRupiah } from '../../../../../Utils/options-input.utils';
import { TabsProps, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';

type TabThemeOverrides = NonNullable<TabsProps['themeOverrides']>;

export default defineComponent({
    props: {
        utils: {
            type: Object,
            required: true,
        },
        products: {
            type: Array,
            required: true,
        },
        subShipping: {
            type: Object,
            required: true,
        }
    },
    setup(props) {
        const menuTheme: TabThemeOverrides = {
            tabTextColorActiveSegment: "white",
            tabColorSegment: "green",
        }
        const hasRounded = ref(false);
        const notification = useNotification();
        const productDetail = ref({
            product_name: null,
            product_unit: null,
            product_code: null,
        });
        const selectedPrice = ref<string | null>(null);
        const form = useForm({
            redemp_price: null as unknown as number,
            retail_price: null as unknown as number,
            grosir_price: null as unknown as number,
            end_user_price: null as unknown as number,
            all_segment_price: null as unknown as number,
            percentage: null as unknown as number,
            transportation_cost: null as unknown as number,
            oh_depo: null as unknown as number,
            budget_marketing: findValueGlobalElement(props.utils.global_element, 'BUDGET MARKETING'),
            bad_debt: findValueGlobalElement(props.utils.global_element, 'BAD DEBT'),
            saving: null as unknown as number,
            margin_retail: null as unknown as number,
            margin_grosir: null as unknown as number,
            margin_end_user: null as unknown as number,
            margin_all_segment: null as unknown as number,
            rounded_all_segment_price: null as unknown as number,
            delivery_type: 'DEPO',
            product_id: null as unknown as number,
        });

        function clearPricing() {
            form.reset();
        }

        // calculate
        function calculateRedempPrice() {
            const {
                transportation_cost, // harga angkutan
                oh_depo, //oh depo
                bad_debt, // bad debt
                budget_marketing, // Budget Marketing
                saving, // saving
                margin_all_segment, // margin normal
            } = form;

            // Pembulatan redemp_price ke ratusan ribu tanpa koma/desimal
            if (form.redemp_price) {
                form.redemp_price = Math.round(Number(form.redemp_price)); // Pembulatan ke bilangan bulat
            }

            // Hilangkan desimal sepenuhnya jika ada
            if (form.redemp_price) {
                form.redemp_price = Math.trunc(form.redemp_price); // Membulatkan ke bilangan bulat dengan membuang koma
            }

            // Kalkulasi redemp_price dengan faktor 1.11
            let redemp_price = form.redemp_price;
            // if (redemp_price) {
            //     redemp_price = Math.trunc(Number(redemp_price) / 1.11); // Membulatkan hasil pembagian ke bilangan bulat
            //     form.redemp_price = redemp_price; // Update nilai redemp_price di form
            // }

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

            // Kalkulasi harga jual all_segment_price tanpa PPN
            // form.all_segment_price = Math.ceil(basePrice + resultPpn);

            form.all_segment_price = basePrice; // All Segment
        }

        function calculateRoundedPrice() {
            const {
                all_segment_price,
                end_user_price,
                margin_all_segment,
                rounded_all_segment_price,
            } = form;

            // Perbarui harga berdasarkan nilai pembulatan yang dimasukkan oleh pengguna
            form.all_segment_price = Number(all_segment_price) + (Number(rounded_all_segment_price) || 0);
            form.margin_all_segment = Number(margin_all_segment) + (Number(rounded_all_segment_price) || 0);
            // form.end_user_price = Number(end_user_price) + (Number(rounded_all_segment_price) || 0);

            hasRounded.value = true;

            notification.success({ title: "Dibulatkan", closable: false, duration: 2000 });
        }

        function calculateReversePrice() {
            Swal.fire({
                title: "Pilih cara perhitungan harga",
                icon: "question",
                showCancelButton: true,
                showDenyButton: false, // Tidak ada tombol deny
                confirmButtonText: "HARGA TEBUS",
                cancelButtonText: "HARGA JUAL",
                cancelButtonColor: "#17a2b8",
            }).then((result) => {
                const percentage = Number(form.percentage); // Pastikan angka valid
                const allSegmentPrice = Number(form.all_segment_price);
                const redempPrice = Number(form.redemp_price);

                if (result.isConfirmed) {
                    if (percentage === 0.075) {
                        // Jika persentase 0.075, tanyakan apakah ingin "Tambah" atau "Kurang"
                        Swal.fire({
                            title: "Pilih metode perhitungan",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Tambah",
                            cancelButtonText: "Kurang",
                            cancelButtonColor: "#dc3545",
                        }).then((subResult) => {
                            if (subResult.isConfirmed) {
                                // Jika memilih "Tambah", gunakan metode selain Zeelandia
                                calculateFromSellingPrice(redempPrice, percentage, true);
                            } else {
                                // Jika memilih "Kurang", gunakan metode normal Zeelandia
                                calculateFromSellingPrice(allSegmentPrice, percentage, false);
                            }
                        });
                    } else {
                        // Jika bukan 0.075, jalankan rumus biasa
                        calculateFromSellingPrice(redempPrice, percentage, false);
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    calculateFromRedempPrice(redempPrice, {
                        end_user: form.end_user_price,
                        retail: form.retail_price,
                        grosir: form.grosir_price,
                    });
                }
            });
        }

        function calculateFromSellingPrice(entry_price: number, percentage: number, isAdd: boolean) {
            const convertPercentage = Number(percentage);
            let redemp_price: number;
            let all_segment_price: number;
            let marginAmount: number;
            let deductions: number;
            let normal_margin: number;

            if (convertPercentage === 0.075 && !isAdd) {
                // Zeelandia (Kurang)
                redemp_price = Math.round(entry_price - (entry_price * convertPercentage));
                all_segment_price = entry_price;
                marginAmount = entry_price - redemp_price;
            } else {
                // Selain Zeelandia atau 0.075 dengan Tambah
                redemp_price = entry_price;
                all_segment_price = Math.round(entry_price + (entry_price * convertPercentage));
                marginAmount = all_segment_price - entry_price;
            }

            // console.log([
            //     form.bad_debt,
            //     form.budget_marketing,
            //     form.saving,
            //     form.oh_depo,
            //     form.transportation_cost
            // ]);

            // Menghitung total biaya deductions
            deductions = Number(form.bad_debt) + Number(form.budget_marketing) + Number(form.saving)
                + Number(form.oh_depo) + Number(form.transportation_cost);

            // console.log("deductions", deductions);

            // Menghitung normal margin setelah dikurangi biaya
            normal_margin = Math.round(marginAmount - deductions);

            // console.log("normal margin ", normal_margin);

            // Menyimpan hasil ke dalam form
            form.redemp_price = redemp_price;
            form.all_segment_price = all_segment_price;
            form.margin_all_segment = normal_margin;
        }

        type SellingPrice = {
            // all_segment: number;
            end_user: number;
            retail: number;
            grosir: number;
        };

        function calculateFromRedempPrice(redemp_price: number, selling_price: SellingPrice) {
            // Biaya yang dikurangkan untuk menghitung margin
            const deductions = Number(form.oh_depo) + Number(form.budget_marketing) + Number(form.saving) + Number(form.transportation_cost) + Number(form.bad_debt);

            // Menghitung selisih harga tebus dengan masing-masing harga jual
            const margin = {
                // all_segment: Math.round((selling_price.all_segment ?? 0) - redemp_price - deductions),
                end_user: Math.round((selling_price.end_user ?? 0) - redemp_price - deductions),
                retail: Math.round((selling_price.retail ?? 0) - redemp_price - deductions),
                grosir: Math.round((selling_price.grosir ?? 0) - redemp_price - deductions),
            };



            // Menyimpan hasil margin ke dalam form
            // form.normal_margin = margin.all_segment;
            form.margin_grosir = margin.grosir;
            form.margin_retail = margin.retail;
            form.margin_end_user = margin.end_user;
        }

        function handleSubmitPricing() {
            form.post(route('admin.pricing.depo.post', props.subShipping.id), {
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

        watch(() => form.product_id, (id) => {
            const selectedProduct = productOptions.find(data => data.value === id);

            productDetail.value.product_name = selectedProduct?.label;
            productDetail.value.product_unit = selectedProduct?.unit;
            productDetail.value.product_code = selectedProduct?.code;
        });


        const deliveryRegionOptions = props.utils.region_delivery.map((data) => ({
            label: `${data.region_name} - ${formatRupiah(data.region_price)}`,
            value: `${data.region_name}-${data.region_price}`
        }));

        // Watch perubahan transportation_cost dan pisahkan harga
        watch(
            () => selectedPrice.value,
            (newValue) => {
                if (newValue) {
                    let price = newValue.split("-")[1]; // Ambil harga dari string
                    form.transportation_cost = Number(price);
                } else {
                    selectedPrice.value = null;
                }
            }
        );

        const dimensionOptions = props.utils.dimensions.map((data) => ({
            label: `${data.dimention_name} - ${data.price_dimention}`,
            value: data.price_dimention
        }));

        const percentageOptions = (props.utils.percentages as any[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        const productOptions = (props.products as any[]).map((data: { id: number, name: string, unit: string, code: string }) => ({
            label: data.name,
            value: data.id,
            unit: data.unit,
            code: data.code
        }));


        return {
            form,
            menuTheme,
            hasRounded,
            productDetail,
            deliveryRegionOptions,
            dimensionOptions,
            percentageOptions,
            productOptions,
            calculateRedempPrice,
            clearPricing,
            handleSubmitPricing,
            calculateRoundedPrice,
            calculateReversePrice,
            calculateFromSellingPrice,
            calculateFromRedempPrice,
            selectedPrice
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