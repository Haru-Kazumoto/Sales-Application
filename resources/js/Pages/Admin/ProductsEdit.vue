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
        <div class="card shadow-sm border-0 mb-3">
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
                    <!-- <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga Vendor
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.discount_vendor">
                                    <template #prefix>
                                        Rp
                                    </template>
</n-input>
</div> -->
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
                                    <label for="">Harga Tebus
                                        <RequiredMark />
                                    </label>
                                    <n-input size="large" placeholder="" v-model:value="form.redemp_price"
                                        @input="(value) => form.redemp_price = value.replace(/\./g, '').replace(',', '.')">
                                        <template #prefix>
                                            Rp
                                        </template>
                                    </n-input>
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
                                    <n-select :options="deliveryRegionOptions" v-model:value="form.transportation_cost"
                                        size="large"></n-select>
                                    <!-- <n-input size="large" placeholder="" v-model:value="form.transportation_cost"
                            @input="(value) => form.transportation_cost = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input> -->
                                </div>

                                <!-- THIRD ROW -->
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">OH Depo
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="dimentionOptions" v-model:value="form.oh_depo"
                                        size="large"></n-select>
                                    <!-- <n-input size="large" placeholder="" v-model:value="form.oh_depo"
                            @input="(value) => form.oh_depo = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input> -->
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
                                    <label for="">Budget Marketing
                                        <RequiredMark />
                                    </label>
                                    <n-input size="large" placeholder="" v-model:value="form.saving_marketing"
                                        @input="(value) => form.saving_marketing = value.replace(/\D/g, '')">
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
                                <div class="d-flex">
                                    <n-button type="primary" class="ms-auto" @click="calculateRedempPrice">Kalkulasi
                                        Harga</n-button>
                                </div>

                                <n-divider></n-divider>


                                <!-- FOURTH ROW -->
                                <div class="col-12 col-lg-3 d-flex flex-column" v-if="showPrice">
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
                                <div class="col-12 col-lg-3 d-flex flex-column">
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
                                <div class="col-12 col-lg-3 d-flex flex-column">
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
                                <div class="col-12 col-lg-3 d-flex flex-column">
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
                                <n-divider></n-divider>
                            </div>

                        </n-tab-pane>
                        <n-tab-pane name="reverse-calculation" tab="PERHITUNGAN MUNDUR">
                            <div class="row g-2">
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Harga Tebus
                                        <RequiredMark />
                                    </label>
                                    <n-input size="large" placeholder="" v-model:value="form.redemp_price"
                                        @input="(value) => form.redemp_price = value.replace(/\./g, '').replace(',', '.')">
                                        <template #prefix>
                                            Rp
                                        </template>
                                    </n-input>
                                </div>
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Persentase Harga
                                        <RequiredMark />
                                    </label>
                                    <n-select size="large" placeholder="" v-model:value="form.percentage"
                                        :options="percentageOptions" />
                                </div>
                                <!-- <div class="col-12 col-lg-2 d-flex flex-column justify-content-end">
                                    <n-button secondary type="primary" size="large"
                                        @click="calculatePercentagePrice">Kalkulasi</n-button>
                                </div> -->
                                <n-divider></n-divider>
                                <div class="col-12 col-lg-3 d-flex flex-column" >
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
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <label for="">Harga Jual Grosir
                                        <RequiredMark />
                                    </label>
                                    <n-input size="large" placeholder="" v-model:value="form.restaurant_price"
                                        @input="(value) => form.restaurant_price = value.replace(/\D/g, '')">
                                        <template #prefix>
                                            Rp
                                        </template>
                                    </n-input>
                                </div>
                                <div class="col-12 col-lg-3 d-flex flex-column">
                                    <label for="">Harga Jual Retail
                                        <RequiredMark />
                                    </label>
                                    <n-input size="large" placeholder="" v-model:value="form.retail_price"
                                        @input="(value) => form.retail_price = value.replace(/\D/g, '')">
                                        <template #prefix>
                                            Rp
                                        </template>
                                    </n-input>
                                </div>

                                <div class="col-12 col-lg-3 d-flex flex-column">
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

                                <!-- FOURTH ROW -->
                                <n-divider></n-divider>

                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">Harga Trucking
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="deliveryRegionOptions" v-model:value="form.transportation_cost"
                                        size="large"></n-select>
                                    <!-- <n-input size="large" placeholder="" v-model:value="form.transportation_cost"
                            @input="(value) => form.transportation_cost = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input> -->
                                </div>

                                <!-- THIRD ROW -->
                                <div class="col-12 col-lg-4 d-flex flex-column">
                                    <label for="">OH Depo
                                        <RequiredMark />
                                    </label>
                                    <n-select :options="dimentionOptions" v-model:value="form.oh_depo"
                                        size="large"></n-select>
                                    <!-- <n-input size="large" placeholder="" v-model:value="form.oh_depo"
                            @input="(value) => form.oh_depo = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input> -->
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
                                    <label for="">Budget Marketing
                                        <RequiredMark />
                                    </label>
                                    <n-input size="large" placeholder="" v-model:value="form.saving_marketing"
                                        @input="(value) => form.saving_marketing = value.replace(/\D/g, '')">
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

                                <n-divider></n-divider>

                                <div class="col-12 col-lg-3 d-flex flex-column">
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

                                <div class="col-12 col-lg-3 d-flex flex-column">
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

                                <div class="col-12 col-lg-3 d-flex flex-column">
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

                                <div class="col-12 col-lg-3 d-flex flex-column">
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
                            </div>
                            <div class="d-flex mt-3">
                                <n-button type="primary" class="ms-auto" @click="calculateReversePrice">Kalkulasi
                                    Harga</n-button>
                            </div>
                        </n-tab-pane>
                    </n-tabs>
                    <!-- <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <span>Harga tebus sudah otomatis exclude 1.11</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> -->



                    <h4>Pembulatan harga (opsional)</h4>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Pembulatan Harga All Segment

                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.rounded_all_segment_price"
                            @input="(value) => form.rounded_all_segment_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <!-- <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Pembulatan Harga Jual Grosir

                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.rounded_retail_price"
                            @input="(value) => form.rounded_retail_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Pembulatan Harga Retail

                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.rounded_restaurant_price"
                            @input="(value) => form.rounded_restaurant_price = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-3 d-flex flex-column">
                        <label for="">Pembulatan Harga End User

                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.rounded_price_3"
                            @input="(value) => form.rounded_price_3 = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div> -->
                    <div class="d-flex">
                        <n-button type="primary" class="ms-auto" @click="calculateRoundedPrice">Kalkulasi
                            pembulatan</n-button>
                    </div>
                    <n-divider></n-divider>



                    <!-- <div class="col-12 col-lg-4 d-flex flex-column">
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
                            </div> -->

                    <div class="d-flex">
                        <div class="ms-auto d-flex gap-3">
                            <n-button type="primary" attr-type="submit">Tambah Data</n-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- <n-modal v-model:show="showModal" class="custom-card" preset="card" :style="bodyStyle" title="PILIH CARA PENENTUAN HARGA"
            :bordered="false" size="huge" :segmented="segmented">
            <div class="d-flex justify-content-center">
                <span class="fw-bold">HARGA AKAN DIHITUNG DARI CARA YANG DIPILIH</span>
            </div>
            <template #footer>
                <div class="d-flex gap-2 justify-content-center">
                    <n-button type="primary" size="medium">HITUNG DARI HARGA JUAL</n-button>
                    <n-button type="info" size="medium">HITUNG DARI HARGA TEBUS</n-button>
                </div>
            </template>
        </n-modal> -->

    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import RequiredMark from '../../Components/RequiredMark.vue';
import { NButton, TabsProps, useNotification } from 'naive-ui';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Lookup, Products } from '../../types/model';
import { ArrowBack } from '@vicons/ionicons5';
import Swal from 'sweetalert2';
import { formatRupiah } from '../../Utils/options-input.utils';

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
        const hasExluded = ref(false);

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
            bad_debt_dd: page.props.global_element.find((data) => data.name_element === 'BAD DEBT')?.price_element || null as unknown as number,
            saving_marketing: page.props.global_element.find((data) => data.name_element === 'BUDGET MARKETING')?.price_element || null as unknown as number,
            product_type_id: product.product_type_id || null as unknown as number,
            product_sub_type_id: product.product_sub_type_id || null as unknown as number,
            all_segment_price: product.all_segment_price || null as unknown as number,
            supplier_id: product.supplier_id || null as unknown as number,
            transportation_cost: product.transportation_cost || null as unknown as number,
            margin_end_user: product.margin_end_user || null as unknown as number,
            margin_retail: product.margin_retail || null as unknown as number,
            margin_grosir: product.margin_grosir || null as unknown as number,
            percentage: product.percentage || null as unknown as number,
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

        // calculate
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


        // //reverse calculate
        // function calculateReversePrice() {
        //     Swal.fire({
        //         title: "Pilih cara perhitungan harga",
        //         icon: "question",
        //         showCancelButton: true,
        //         showDenyButton: true,
        //         confirmButtonText: "HARGA TEBUS",
        //         cancelButtonText: "HARGA JUAL",
        //         cancelButtonColor: "#17a2b8",
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // zeelandia
        //             if (Number(form.percentage) === 0.075) {
        //                 calculateFromSellingPrice(Number(form.all_segment_price), Number(form.percentage));
        //             } else { // non zeelandia
        //                 calculateFromSellingPrice(Number(form.redemp_price), Number(form.percentage));
        //             }
        //         }

        //         if (result.isDismissed) {
        //             calculateFromRedempPrice(form.redemp_price, {
        //                 // all_segment: form.all_segment_price,
        //                 end_user: form.price_3,
        //                 retail: form.retail_price,
        //                 grosir: form.restaurant_price,
        //             });
        //         }
        //     });
        // }

        // function calculateFromSellingPrice(entry_price: number, percentage: number) {
        //     const convertPercentage = Number(percentage);
        //     let redemp_price: number;
        //     let all_segment_price: number;
        //     let marginAmount: number;
        //     let deductions: number;
        //     let normal_margin: number;

        //     if (convertPercentage === 0.075) {

        //         // Zeelandia: Hitung harga tebus
        //         redemp_price = Math.round(entry_price - (entry_price * convertPercentage)); // Harga tebus dihitung
        //         all_segment_price = entry_price; // Harga jual tetap sebagai entry price
        //         marginAmount = entry_price - redemp_price; // Selisih harga jual - harga tebus
        //     } else {
        //         // Selain Zeelandia: Hitung harga all segment
        //         redemp_price = entry_price; // Harga tebus tetap sebagai entry price
        //         all_segment_price = Math.round(entry_price + (entry_price * convertPercentage)); // Harga all segment dihitung
        //         marginAmount = all_segment_price - entry_price; // Selisih harga all segment - harga tebus
        //     }

        //     // Menghitung total biaya deductions
        //     deductions = form.bad_debt_dd + form.saving_marketing + form.saving
        //         + form.oh_depo + form.transportation_cost;

        //     // Menghitung normal margin setelah dikurangi biaya
        //     normal_margin = Math.round(marginAmount - deductions);

        //     // Menyimpan hasil ke dalam form
        //     form.redemp_price = redemp_price; // Jika Zeelandia, harga tebus yang dihitung
        //     form.all_segment_price = all_segment_price; // Jika selain Zeelandia, harga all segment yang dihitung
        //     form.normal_margin = normal_margin;
        // }

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

            calculateFromSellingPrice(form.redemp_price, form.percentage);

            // do calculate
            const percentage = Number(form.percentage);
            const redemp_price = Number(form.redemp_price);

            const discountAmount = redemp_price * percentage;

            // Kurangkan dari harga awal
            const result = Math.round(redemp_price - discountAmount);
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

        const productSubTypeOptions = (page.props.product_sub_type as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        const deliveryRegionOptions = (page.props.delivery_region as any[]).map((data) => ({
            label: `${data.region_name} - ${formatRupiah(data.region_price)}`,
            value: data.region_price,
            region_price: data.region_price,
            region_code: data.region_code
        }));

        const dimentionOptions = (page.props.dimention as any[]).map((data) => ({
            label: `${data.dimention_name}-(${formatRupiah(data.price_dimention)})`,
            value: data.price_dimention,
            max_value: data.max_value,
            min_value: data.min_value
        }));

        const percentageOptions = (page.props.percentage as any[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        return {
            calculatePercentagePrice,
            calculateRedempPrice,
            calculateReversePrice,
            calculateExcludePpnRedempPrice,
            calculateFromRedempPrice,
            calculateRoundedPrice,
            handleSubmitProduct,
            currentPage,
            unitOptions,
            productTypeOptions,
            productSubTypeOptions,
            categoryProductOptions,
            deliveryRegionOptions,
            percentageOptions,
            dimentionOptions,
            form,
            ArrowBack,
            supplierOptions,
            router,
            showPrice,
            hasExluded,
            showPriceDetail,
            menuTheme,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            showModal: ref(false),
            showSecondModal: ref(false),
            showThirdModal: ref(false)
        }
    },
    components: {
        TitlePage,
        RequiredMark
    }
})
</script>