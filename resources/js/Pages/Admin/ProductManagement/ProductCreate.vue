<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Produk Baru" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.products'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4 d-flex flex-column">
                        <h4>Tambah data dari excel</h4>
                        <n-divider></n-divider>
                        <label for="file-upload" class="form-label">Upload File Excel</label>
                        <input type="file" id="file-upload" accept=".xlsx" class="form-control"
                            @change="handleChangeFile" />
                        <small class="text-muted">
                            Hanya file Excel (.xlsx) yang diperbolehkan.
                        </small>
                        <n-button type="primary" class="mt-3 w-25" @click="handleImportProducts">Import</n-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h4>Tambah data baru</h4>
                <n-divider></n-divider>
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
                            :options="supplierOptions" filterable />
                    </div>

                    <!-- SECOND ROW -->
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kemasan
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.unit"
                            @input="(value) => form.unit = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Kategori Produk
                            <RequiredMark />
                        </label>
                        <n-select size="large" placeholder="" v-model:value="form.category"
                            :options="categoryProductOptions" />
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column">
                        <label for="">Jenis Produk
                            <RequiredMark />
                        </label>
                        <n-select size="large" placeholder="" :options="productSubTypeOptions"
                            v-model:value="form.product_sub_type_id" filterable />
                    </div>
                    <n-divider></n-divider>
                    <h4>Kalkulasi Harga</h4>

                    <n-tabs type="segment" animated size="large" :theme-overrides="menuTheme">
                        <n-tab-pane name="forward-calculation" tab="PERHITUNGAN MAJU">
                            <div class="row g-2">
                                <div class="col-12 col-lg-2 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.redemp_price" label="Harga Tebus"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-2 d-flex flex-column justify-content-end">
                                    <n-button secondary type="primary" size="large"
                                        @click="calculateExcludePpnRedempPrice" :disabled="hasExluded">Exclude
                                        11%</n-button>
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Harga Trucking
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="deliveryRegionOptions" placeholder=""
                                        v-model:value="form.transportation_cost" size="large"></n-select>
                                </div>

                                <!-- THIRD ROW -->
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">OH Depo
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="dimentionOptions" v-model:value="form.oh_depo" placeholder=""
                                        size="large"></n-select>
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.bad_debt_dd" label="Bad Debt"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.saving_marketing" label="Budget Marketing"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.normal_margin" label="Margin Normal"
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
                                <div class="col-12 col-lg-3 d-flex flex-column" v-if="showPrice">
                                    <CurrencyInput v-model:modelValue="form.all_segment_price" label="Harga All Segment"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.restaurant_price" label="Harga Jual Grosir"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.retail_price" label="Harga Jual Retail"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.price_3" label="Harga Jual End User"
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
                                    <n-select size="large" placeholder="" v-model:value="form.percentage"
                                        :options="percentageOptions" />
                                </div>
                                <n-divider></n-divider>
                                <div class="col-12 col-lg-3 d-flex flex-column" v-if="showPrice">
                                    <CurrencyInput v-model:modelValue="form.all_segment_price"
                                        label="Harga Jual All Segment" :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.restaurant_price" label="Harga Jual Grosir"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.retail_price" label="Harga Jual Retail"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.price_3" label="Harga Jual End User"
                                        :required="true" />
                                </div>

                                <!-- FOURTH ROW -->
                                <n-divider></n-divider>

                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Harga Trucking
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="deliveryRegionOptions" placeholder=""
                                        v-model:value="form.transportation_cost" size="large"></n-select>
                                </div>

                                <!-- THIRD ROW -->
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">OH Depo
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="dimentionOptions" v-model:value="form.oh_depo" placeholder=""
                                        size="large"></n-select>
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.bad_debt_dd" label="Bad Debt"
                                        :required="true" />
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.saving_marketing" label="Budget Marketing"
                                        :required="true" />
                                </div>

                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.saving" label="Saving" :required="true" />
                                </div>

                                <n-divider></n-divider>

                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <CurrencyInput v-model:modelValue="form.normal_margin" label="Margin All Segment"
                                        :required="true" />
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
                            label="Pembulatan Harga All Segment" :required="true" />
                    </div>
                    <div class="d-flex">
                        <n-button type="info" class="ms-auto" @click="calculateRoundedPrice">Kalkulasi
                            pembulatan</n-button>
                    </div>
                    <n-divider></n-divider>

                    <div class="d-flex">
                        <div class="ms-auto d-flex gap-3">
                            <n-button type="primary" attr-type="submit">Tambah Data</n-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, reactive } from 'vue';
import TitlePage from '../../../Components/TitlePage.vue';
import RequiredMark from '../../../Components/RequiredMark.vue';
import { NButton, NIcon, SelectOption, TabsProps, useNotification } from 'naive-ui';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Flash, Lookup } from '../../../types/model';
import { findValueGlobalElement, formatRupiah } from '../../../Utils/options-input.utils';
import Swal from 'sweetalert2';
import CurrencyInput from '../../../Components/CurrencyInput.vue';
import { computed } from 'vue';
import { ArrowBack } from '@vicons/ionicons5';

type TabThemeOverrides = NonNullable<TabsProps['themeOverrides']>;

export default defineComponent({
    setup() {
        const menuTheme: TabThemeOverrides = {
            tabTextColorActiveSegment: "white",
            tabColorSegment: "green",
        }

        const currentPage = ref(1);
        const page = usePage();
        const notification = useNotification();
        const supplierOptionsRef = ref<SelectOption[]>([]);
        const loading = ref(false);
        const selectedData = ref({});
        const hasExluded = ref(false);

        const form = useForm({
            code: '',
            unit: '',
            name: '',
            category: '',
            package: '',
            redemp_price: null as unknown as number,
            retail_price: null as unknown as number,
            restaurant_price: null as unknown as number,
            price_3: null as unknown as number,
            dd_price: null as unknown as number,
            all_segment_price: null as unknown as number,
            normal_margin: null as unknown as number,
            oh_depo: null as unknown as number,
            saving: null as unknown as number,
            bad_debt_dd: findValueGlobalElement((page.props.global_element as any[]), 'BAD DEBT'),
            saving_marketing: findValueGlobalElement((page.props.global_element as any[]), 'BUDGET MARKETING'),
            product_type_id: null as unknown as number,
            product_sub_type_id: null as unknown as number,
            supplier_id: null as unknown as number,
            transportation_cost: null as unknown as number,
            margin_retail: null as unknown as number,
            margin_end_user: null as unknown as number,
            margin_grosir: null as unknown as number,
            percentage: null as unknown as number,
            rounded_all_segment_price: null as unknown as number,
            rounded_retail_price: null as unknown as number,
            rounded_restaurant_price: null as unknown as number,
            rounded_price_3: null as unknown as number,

        });

        const showPrice = computed(() => {
            // return form.supplier_id !== 6766;
            return form.supplier_id !== 7539;
        });

        const showPriceDetail = computed(() => {
            // return form.supplier_id === 6766;
            return form.supplier_id === 7539;
        });

        //reverse calculate
        //reverse calculate
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
                        end_user: form.price_3,
                        retail: form.retail_price,
                        grosir: form.restaurant_price,
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

            // Menghitung total biaya deductions
            deductions = form.bad_debt_dd + form.saving_marketing + form.saving
                + form.oh_depo + form.transportation_cost;

            // Menghitung normal margin setelah dikurangi biaya
            normal_margin = Math.round(marginAmount - deductions);

            // Menyimpan hasil ke dalam form
            form.redemp_price = redemp_price;
            form.all_segment_price = all_segment_price;
            form.normal_margin = normal_margin;
        }

        type SellingPrice = {
            // all_segment: number;
            end_user: number;
            retail: number;
            grosir: number;
        };

        function calculateFromRedempPrice(redemp_price: number, selling_price: SellingPrice) {
            // Biaya yang dikurangkan untuk menghitung margin
            const deductions = form.oh_depo + form.saving_marketing + form.saving + form.transportation_cost + form.bad_debt_dd;

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

        function calculatePercentagePrice() {
            if (!form.percentage) {
                notification.error({
                    title: "Masukan persentase terlebih dahulu",
                    duration: 3000,
                    closable: false,
                });
                return;
            }

            // calculateFromSellingPrice(form.redemp_price, form.percentage);

            // do calculate
            const percentage = Number(form.percentage);
            const redemp_price = Number(form.redemp_price);

            const discountAmount = redemp_price * percentage;

            // Kurangkan dari harga awal
            const result = Math.round(redemp_price - discountAmount);

        }


        // forward calculate
        function calculateRedempPrice() {
            const {
                transportation_cost, // harga angkutan
                oh_depo, //oh depo
                bad_debt_dd, // bad debt
                saving_marketing, // Budget Marketing
                saving, // saving
                normal_margin, // margin normal
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
                (Number(bad_debt_dd) || 0) +
                (Number(saving_marketing) || 0) +
                (Number(saving) || 0) +
                (Number(normal_margin) || 0);

            const ppn =
                (Number(transportation_cost) || 0) +
                (Number(oh_depo) || 0) +
                (Number(bad_debt_dd) || 0) +
                (Number(saving_marketing) || 0) +
                (Number(saving) || 0) +
                (Number(normal_margin) || 0);

            const resultPpn = Math.ceil(ppn * 0.11);

            // Kalkulasi harga jual all_segment_price tanpa PPN
            // form.all_segment_price = Math.ceil(basePrice + resultPpn);

            if (form.supplier_id === 6766) { // ID untuk ARES
                form.retail_price = Math.ceil(basePrice + resultPpn); // Retail
                form.restaurant_price = Math.ceil(basePrice + resultPpn); // Grosir
                form.price_3 = Math.ceil(basePrice + resultPpn); // End User
                form.all_segment_price = null as unknown as number; // Kosongkan harga all segment
            } else {
                form.all_segment_price = basePrice; // All Segment
                form.retail_price = null as unknown as number; // Kosongkan harga retail
                form.restaurant_price = null as unknown as number; // Kosongkan harga grosir
                form.price_3 = null as unknown as number; // Kosongkan harga end user
            }
        }

        function calculateExcludePpnRedempPrice() {
            let redemp_price = form.redemp_price;

            if (!redemp_price) {
                notification.error({
                    title: "Masukan harga sebelum kalkulasi!",
                    duration: 3000,
                    closable: false,
                });
                return;
            }

            if (redemp_price) {
                redemp_price = Math.trunc(Number(redemp_price) / 1.11); // Membulatkan hasil pembagian ke bilangan bulat
                form.redemp_price = redemp_price; // Update nilai redemp_price di form
            }

            hasExluded.value = true;
        }

        function calculateRoundedPrice() {
            const {
                all_segment_price,
                price_3,
                retail_price,
                restaurant_price,
                normal_margin,
                rounded_all_segment_price,
                rounded_price_3,
                rounded_retail_price,
                rounded_restaurant_price,
            } = form;

            // Perbarui harga berdasarkan nilai pembulatan yang dimasukkan oleh pengguna
            form.all_segment_price = Number(all_segment_price) + (Number(rounded_all_segment_price) || 0);
            form.normal_margin = Number(normal_margin) + (Number(rounded_all_segment_price) || 0);
            form.price_3 = Number(price_3) + (Number(rounded_price_3) || 0);
            form.retail_price = Number(retail_price) + (Number(rounded_retail_price) || 0);
            form.restaurant_price = Number(restaurant_price) + (Number(rounded_restaurant_price) || 0);
        }



        // Filter data
        const filterField = ref('name'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        function showDetail(row) {
            selectedData.value = row;

            const modal = new bootstrap.Modal(document.getElementById('exampleModal'));

            modal.show();

        }

        function handleSubmitProduct() {
            form.post(route('admin.products.post'), {
                onSuccess() {
                    form.reset();
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        duration: 1500,
                        closable: false,
                    });
                },
                onError() {
                    Swal.fire('Cek kembali form', 'Form dengan <span class="text-danger">*</span> harus di isi!', 'error');
                }
            });
        }

        const file = useForm({
            attachment: null as unknown as any,
        });

        function handleChangeFile(event) {
            file.attachment = event.target.files[0];
        }

        function handleImportProducts() {
            if (file.attachment === null) {
                Swal.fire('File belum di pilih!', 'Silahkan pilih file excel terlebih dahulu', 'error');
                return;
            }

            // Show loading notification
            Swal.fire({
                title: 'Importing data...',
                text: 'Please wait while we import the data.',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            file.post(route('admin.products.import'), {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Berhasil memasukan data!',
                        text: 'Silahkan cek list data',
                        icon: 'success'
                    });
                },
                onError: () => {
                    Swal.fire('Gagal memasukan data!', 'Silahkan lapor developer', 'error');
                }
            });
        }

        // Format nilai untuk ditampilkan di input
        const formattedRedempPrice = computed(() => {
            return formatCurrency(form.redemp_price);
        });

        // Fungsi untuk memformat nilai dengan separator ribuan
        const formatCurrency = (value) => {
            if (!value) return '';
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        // Fungsi untuk menghapus separator dan menyimpan nilai asli
        const handleInput = (value) => {
            // Hapus semua titik (separator) dan koma (jika ada)
            const rawValue = value.replace(/\./g, '');
            form.redemp_price = rawValue;
        };

        const unitOptions = (page.props.units as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const productTypeOptions = (page.props.product_type as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        const productSubTypeOptions = (page.props.product_sub_type as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        const categoryProductOptions = [
            { label: "NON TEPUNG", value: "NON TEPUNG" },
            { label: "TEPUNG", value: "TEPUNG" },
        ];

        const supplierOptions = (page.props.suppliers as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        const dimentionOptions = (page.props.dimention as any[]).map((data) => ({
            label: `${data.dimention_name} - ${formatRupiah(data.price_dimention)}`,
            value: data.price_dimention,
            max_value: data.max_value,
            min_value: data.min_value
        }));

        const percentageOptions = (page.props.percentage as any[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        const deliveryRegionOptions = (page.props.delivery_region as any[]).map((data) => ({
            label: `${data.region_name} - ${formatRupiah(data.region_price)}`,
            value: data.region_price,
            region_price: data.region_price,
            region_code: data.region_code
        }));

        return {
            calculateRedempPrice,
            calculateRoundedPrice,
            calculateReversePrice,
            calculateFromRedempPrice,
            calculatePercentagePrice,
            calculateExcludePpnRedempPrice,
            showDetail,
            showPrice,
            showPriceDetail,
            handleImportProducts,
            handleChangeFile,
            handleSubmitProduct,
            currentPage,
            unitOptions,
            productTypeOptions,
            hasExluded,
            productSubTypeOptions,
            selectedData,
            categoryProductOptions,
            form,
            supplierOptions,
            dimentionOptions,
            percentageOptions,
            deliveryRegionOptions,
            formatRupiah,
            loading,
            menuTheme,
            router,
            ArrowBack,
            formattedRedempPrice,
            formatCurrency,
            handleInput,
        }
    },
    components: {
        TitlePage,
        RequiredMark,
        CurrencyInput
    }
})
</script>