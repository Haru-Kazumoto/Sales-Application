<template>

    <Head title="Create CO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="CUSTOMER ORDER | DNP" />
        <!-- INPUT CO FORM -->
        <div class="card shadow" style="border: none;">
            <!-- INPUT CO -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR CO</label>
                            <n-input size="large" disabled v-model:value="form.document_code" placeholder="" />
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL DIBUAT CO</label>
                            <n-input size="large" disabled v-model:value="transaction_details.customer_order_date"
                                placeholder="" />
                        </div>
                    </div>
                    <n-divider>CUSTOMER INFO</n-divider>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NAMA CUSTOMER</label>
                            <n-select filterable :loading="loading" :options="customerOptions" size="large"
                                placeholder="" v-model:value="transaction_details.customer" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">PENGIRIMAN
                                <RequiredMark />
                            </label>
                            <n-select size="large" v-model:value="transaction_details.delivery" :options="sendType"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4" v-if="transaction_details.delivery === 'DIRECT'">
                        <div class="d-flex flex-column gap-1">
                            <label for="">WILAYAH DISTRIBUSI
                                <RequiredMark />
                            </label>
                            <n-select size="large" :options="sendType" placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BADAN USAHA</label>
                            <n-input size="large" v-model:value="transaction_details.legality" placeholder=""
                                disabled />
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TERMIN</label>
                            <n-input size="large" v-model:value="form.term_of_payment" placeholder="" disabled>
                                <template #suffix>HARI</template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL JATUH TEMPO</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field8"
                                size="large" v-model:formatted-value="form.due_date" disabled />
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">SALESMAN</label>
                            <n-input size="large" v-model:value="transaction_details.salesman" disabled
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">ALAMAT CUSTOMER</label>
                            <n-input size="large" type="textarea" v-model:value="transaction_details.customer_address"
                                placeholder="" disabled />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">SEGMEN CUSTOMER</label>
                        <n-select size="large" v-model:value="transaction_details.segment_customer" placeholder=""
                            :options="segmentCustomer" />
                    </div>

                    <!-- <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA ANGKUTAN<RequiredMark /></label>
                            <n-input size="large" placeholder="" v-model:value="transaction_details.transportation_cost"
                                @input="(value) => transaction_details.transportation_cost = value.replace(/\D/g, '')">
                                <template #prefix>
                                    Rp
                                </template>
                            </n-input>
                        </div>
                    </div> -->
                    <n-divider>OPSIONAL</n-divider>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR PO CUSTOMER </label>
                            <n-input size="large" v-model:value="transaction_details.po_customer"
                                @input="(value) => transaction_details.po_customer = value.toUpperCase()"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">CASHBACK + PPH 4%</label>
                            <n-input size="large" v-model:value="transaction_details.cashback" placeholder=""
                                @input="(value) => transaction_details.cashback = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA BONGKAR</label>
                            <n-input size="large" v-model:value="transaction_details.unloading_cost" placeholder=""
                                @input="(value) => transaction_details.unloading_cost = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="ppn">PPN</label>
                            <n-select size="large" placeholder="" :options="ppnOptions"
                                v-model:value="transaction_details.use_tax" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="ppn">Pengajuan Diskon</label>
                            <n-select size="large" placeholder="" :options="discountSubmission"
                                v-model:value="transaction_details.submission_discount" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-9">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Syarat Pembayaran</label>
                        </div>
                        <n-space item-style="display: flex;" align="center">
                            <n-checkbox size="large" label="Invoice" v-model:checked="condition_payment.invoice"/>
                            <n-checkbox size="large" label="Surat Jalan Asli PO/CO" v-model:checked="condition_payment.travel_document"/>
                            <n-checkbox size="large" label="Faktur Pajak" v-model:checked="condition_payment.tax_invoice"/>
                            <n-checkbox size="large" label="Kwitansi" v-model:checked="condition_payment.receipt"/>
                            <n-checkbox size="large" label="Surat Penerimaan Barang Asli" v-model:checked="condition_payment.items_receipt"/>
                        </n-space>
                        <!-- <n-checkbox-group v-model:value="transaction_details.condition_checked">
                            <n-grid :y-gap="8" :cols="2">
                                <n-gi>
                                    <n-checkbox value="1" label="Invoice" />
                                </n-gi>
                                <n-gi>
                                    <n-checkbox value="2" label="Surat Jalan Asli PO/CO" />
                                </n-gi>
                                <n-gi>
                                    <n-checkbox value="3" label="Faktur Pajak" />
                                </n-gi>
                                <n-gi>
                                    <n-checkbox value="4" label="Kwitansi" />
                                </n-gi>
                                <n-gi>
                                    <n-checkbox value="5" label="Surat Penerimaan Barang Asli" />
                                </n-gi>
                            </n-grid>
                        </n-checkbox-group> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- INPUT PRODUCTS -->
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-3">
                <div class="card-title">
                    <h3>Informasi Produk</h3>
                </div>
                <div class="row g-3">
                    <!-- INPUT PRODUCTS FORM -->
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column gap-1">
                        <label for="">NAMA PRODUK</label>
                        <n-select filterable :options="availableProducts" placeholder="" size="large"
                            v-model:value="products.name" clearable />
                        <!-- Warning quantity atau status quantity -->
                        <span :style="{ color: stockStatusColor }">
                            {{ stockMessage }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column gap-1">
                        <label for="">QUANTITY</label>
                        <n-input size="large" v-model:value="transaction_items.quantity" placeholder="" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-2 d-flex flex-column gap-1">
                        <label for="">KEMASAN</label>
                        <n-input size="large" v-model:value="transaction_items.unit" disabled placeholder="" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">HARGA PRODUK</label>
                        <n-input size="large" v-model:value="transaction_items.amount" placeholder="">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                    <div v-if="hasPromo && isPromoActive" class="row g-3">
                        <n-divider>INFORMASI</n-divider>
                        <!-- Tambahan kolom untuk Promo, Deskripsi, dan Harga Diskon -->
                        <div v-if="hasPromo && isPromoActive"
                            class="p-3 bg-info bg-opacity-10 border border-info border-start-0 rounded-end">
                            <div class="row g-3">
                                <span>PRODUK INI MEMPUNYAI PROMO</span>
                                <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                    <label for="">DESKRIPSI</label>
                                    <n-input size="large" :value="products.description" disabled />
                                </div>
                                <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                    <label for="">MINIMAL</label>
                                    <n-input size="large" :value="products.min" disabled />
                                </div>
                                <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                    <label for="">MAKSIMAL</label>
                                    <n-input size="large" :value="products.max" disabled />
                                </div>
                            </div>
                        </div>

                        <!-- INPUT DISCOUNT FORM -->
                        <div class="col-6 d-flex flex-column gap-1">
                            <label for="">DISCOUNT 1</label>
                            <n-input size="large" placeholder="" v-model:value="products.promo_value_1" disabled
                                @input="(value) => products.promo_value_1 = value.replace(/\D/g, '')">
                                <template #suffix>
                                    %
                                </template>
                            </n-input>
                        </div>
                        <div class="col-6 d-flex flex-column gap-1">
                            <label for="">HARGA</label>
                            <n-input size="large" disabled placeholder=""
                                v-model:value="transaction_details.total_discount_1">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>
                        <div class="col-6 d-flex flex-column gap-1">
                            <label for="">DISCOUNT 2</label>
                            <n-input size="large" placeholder="" v-model:value="products.promo_value_2" disabled
                                @input="(value) => products.promo_value_2 = value.replace(/\D/g, '')">
                                <template #suffix>
                                    %
                                </template>
                            </n-input>
                        </div>
                        <div class="col-6 d-flex flex-column gap-1">
                            <label for="">HARGA</label>
                            <n-input size="large" disabled placeholder=""
                                v-model:value="transaction_details.total_discount_2">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>
                        <div class="col-6 d-flex flex-column gap-1">
                            <label for="">DISCOUNT 3</label>
                            <n-input size="large" placeholder="" v-model:value="products.promo_value_3" disabled
                                @input="(value) => products.promo_value_3 = value.replace(/\D/g, '')">
                                <template #suffix>
                                    %
                                </template>
                            </n-input>
                        </div>
                        <div class="col-6 d-flex flex-column gap-1">
                            <label for="">HARGA</label>
                            <n-input size="large" disabled placeholder=""
                                v-model:value="transaction_details.total_discount_3">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>

                    </div>
                </div>
                <n-button type="primary" class="ms-auto" @click="handleAddProduct">Tambah Produk</n-button>
            </div>
        </div>

        <!-- PRODUCT CHOOSEN LIST -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <span>* Geser tabel untuk lebih detail</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="card-title">
                    <h3>Produk Pesanan</h3>
                </div>

                <n-data-table :bordered="false" :columns="columns" :data="form.transaction_items" />
            </div>
        </div>

        <!-- CALCULATION RESULT -->
        <div class="card shadow border-0 mb-4">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2" v-if="totalDiscounts > 0">
                    <span>Diskon</span>
                    <span>{{ formatRupiah(totalDiscounts) }}</span>
                </div>
                <!-- <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>{{ formatRupiah(subtotal) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total harga</span>
                    <span>{{ formatRupiah(totalPrice) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2" v-if="transaction_details.use_tax === true">
                    <span>PPN 11%</span>
                    <span>{{ formatRupiah(totalPPN) }}</span>
                </div> -->
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total Tagihan</span>
                    <span>{{ formatRupiah(grandTotal) }}</span>
                </div>
                <div class="d-flex flex-column w-100 justify-content-between mt-2 gap-3">
                    <div class="d-flex justify-content-between">
                        <span>TERM OF PAYMENT</span>
                        <span class="fw-bold">{{ form.term_of_payment ? `${form.term_of_payment} HARI` : '' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>JATUH TEMPO</span>
                        <span class="fw-bold">{{ form.due_date ? dayjs(form.due_date).format('dddd, D MMMM YYYY') :
                            '' }}</span>
                    </div>
                </div>
                <n-button class="my-3 ms-auto" type="primary" @click="handleSubmitCo">SUBMIT CO</n-button>
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import { computed, defineComponent, h, ref, watch } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, SelectOption, useNotification } from 'naive-ui';
import { useForm, usePage, Head } from '@inertiajs/vue3';
import RequiredMark from "../../../Components/RequiredMark.vue";
import Swal from 'sweetalert2';
import { Lookup, Parties, Products, TransactionDetail, TransactionItems, User } from '../../../types/model';
import { formatRupiah } from '../../../Utils/options-input.utils';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();
        const customerOptionsRef = ref<SelectOption[]>([]);
        const productsOptionsRef = ref<SelectOption[]>([]);
        const loading = ref(false);
        const loadingProducts = ref(false);
        const stockMessage = ref(''); // Untuk pesan stok
        const stockStatusColor = ref('black'); // Untuk warna teks

        function createColumns(): DataTableColumns<TransactionItems> {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "NAMA PRODUK",
                    key: 'name',
                    width: 300,
                    render(row) {
                        return row.product?.name;
                    }
                },
                {
                    title: "QTY",
                    key: 'quantity',
                    width: 100,
                },
                {
                    title: "SATUAN",
                    key: 'unit',
                    width: 150,
                },
                {
                    title: "HARGA",
                    key: 'amount',
                    width: 150,
                    render(row) {
                        return formatRupiah(row.amount ?? 0);
                    }
                },

                {
                    title: "DISCOUNT 1",
                    key: 'discount_1',
                    width: 200,
                    render(row) {
                        return `${row.discount_1} %`
                    }
                },
                {
                    title: "HARGA DISCOUNT 1",
                    key: 'total_price_discount_1',
                    width: 250,
                    render(row) {
                        return formatRupiah(row.total_discount_1 ?? 0);
                    }
                },
                {
                    title: "DISCOUNT 2",
                    key: 'discount_2',
                    width: 200,
                    render(row) {
                        return `${row.discount_2} %`
                    }
                },
                {
                    title: "HARGA DISCOUNT 2",
                    key: 'total_price_discount_2',
                    width: 250,
                    render(row) {
                        return formatRupiah(row.total_discount_2 ?? 0);
                    }
                },
                {
                    title: "DISCOUNT 3",
                    key: 'discount_3',
                    width: 200,
                    render(row) {
                        return `${row.discount_3} %`
                    }
                },
                {
                    title: "HARGA DISCOUNT 3",
                    key: 'total_price_discount_3',
                    width: 250,
                    render(row) {
                        return formatRupiah(row.total_discount_3 ?? 0);
                    }
                },
                {
                    title: "TOTAL HARGA",
                    key: 'total',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total_price ?? 0);
                    }
                },
                {
                    title: "ACTION",
                    key: 'action',
                    width: 100,
                    render(row, index) {
                        return h(
                            NButton,
                            {
                                type: 'error',
                                size: 'small',
                                onClick: () => {
                                    Swal.fire({
                                        icon: 'question',
                                        text: `Delete ${row.product?.name}?`,
                                        showCancelButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            removeProduct(index);

                                            notification.success({
                                                title: `${row.product?.name} has been deleted!`,
                                                closable: true,
                                                keepAliveOnHover: false,
                                                duration: 2000,
                                            });
                                        }
                                    });
                                }
                            },
                            { default: () => "Hapus" }
                        )
                    }
                }
            ]
        }

        const form = useForm({
            document_code: (page.props.coNumber as string),
            due_date: null as string | null,
            description: '',
            sub_total: null as unknown as number,
            total: null as unknown as number,
            total_discount: null as unknown as number,
            term_of_payment: null as unknown as number,
            tax_amount: null as unknown as number,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_details = ref({
            customer_order_date: (page.props.dateNow),
            customer: '',
            legality: '',
            customer_address: '',
            npwp: '',
            salesman: (page.props.auth as any).user.fullname,
            transportation_cost: null as unknown as number,
            cashback: null as unknown as number,
            delivery: null as unknown as string,
            unloading_cost: null as unknown as number,
            po_customer: null as unknown as string,
            total_discount_1: null as any,
            total_discount_2: null as any,
            total_discount_3: null as any,
            segment_customer: null as any,
            use_tax: false,
            submission_discount: "NOT SUBMIT",
            condition_checked: [] as any[]
        });

        const condition_payment = ref({
            invoice: null as unknown as any, // Untuk value 1
            tax_invoice: null as unknown as any, // Untuk value 3
            travel_document: null as unknown as any, // Untuk value 2
            receipt: null as unknown as any, // Untuk value 4
            items_receipt: null as unknown as any, // Untuk value 5
        });



        const products = ref({
            code: '',
            unit: '',
            name: '',
            last_stock: null as unknown as number,
            retail_price: null as unknown as number,
            promo_value_1: null as unknown as number,
            promo_value_2: null as unknown as number,
            promo_value_3: null as unknown as number,
            description: '',
            min: null as unknown as number,
            max: null as unknown as number,
            start_date: null as unknown as string,
            end_date: null as unknown as string,
            promo_name: null as unknown as string,
            transaction_items: [] as TransactionItems[],
            restaurant_price: null as unknown as number,
            price_3: null as unknown as number,
            all_segment_price: null as unknown as number,
        });

        const transaction_items = ref({
            unit: '',
            quantity: null as unknown as number,
            tax_amount: 0,
            amount: null as unknown as number,
            amount_after_discount: null as unknown as number, // for discount if exist
            product_id: null as unknown as number,
            tax_id: null as unknown as number,
            discount_1: null as unknown as number,
            discount_2: null as unknown as number,
            discount_3: null as unknown as number,
            total_price: null as unknown as number,
            product_journals: [] as any[],
            real_amount: null as unknown as number,
            // total_price_discount: null as unknown as number,
        });

        const customerGroup = ref(null);
        const availableProducts = ref<any>([]);

        const productOptions = (page.props.products as any[]).map((data) => ({
            label: data.name,
            value: data.name,
            unit: data.unit,
            code: data.code,
            warehouse: data.warehouse,
            batch_code: data.batch_code,
            expiry_date: data.expiry_date,
            last_stock: data.last_stock,
            status: data.status,
            id: data.id,
            promo_value_1: data.promo_value_1,
            promo_value_2: data.promo_value_2,
            promo_value_3: data.promo_value_3,
            description: data.description,
            min: data.min,
            max: data.max,
            start_date: data.start_date,
            end_date: data.end_date,
            promo_name: data.promo_name,
            retail_price: data.retail_price,
            redemp_price: data.redemp_price,
            restaurant_price: data.restaurant_price,
            all_segment_price: data.all_segment_price,
            end_user_price: data.price_3,
        }));

        const productMasters = (page.props.all_products as any[]).map((data) => ({
            label: data.name,
            value: data.name,
            category: data.category,
            unit: data.unit,
            code: data.code,
            id: data.id,
            retail_price: data.retail_price,
            redemp_price: data.redemp_price,
            restaurant_price: data.restaurant_price,
            all_segment_price: data.all_segment_price,
            end_user_price: data.price_3,
        }));

        /**
         *  watch the sendType if send type is direct, direct depo, and beli do then list all products from data master,
         *  elese list all products from warehouse (batch)
         */
        watch(
            () => transaction_details.value.delivery, // Perhatikan perubahan di `delivery`
            (delivery) => {
                if (['DIRECT', 'DIRECT_DEPO', 'DO'].includes(delivery)) {
                    // Jika delivery adalah salah satu dari jenis pengiriman tersebut, gunakan data master
                    availableProducts.value = productMasters;
                } else {
                    // Selain itu, gunakan data dari gudang
                    availableProducts.value = productOptions;
                }
            },
            { immediate: true } // Jalankan watch segera saat di-mount
        );

        watch(() => form.term_of_payment, (term) => {
            if (term) {
                // Buat tanggal baru yang merepresentasikan hari ini
                const today = new Date();
                // Tambahkan hari sesuai `term_of_payment`
                today.setDate(today.getDate() + term);
                // Set `due_date` menjadi hasil perhitungan
                form.due_date = today.toISOString().slice(0, 19).replace('T', ' '); // Format ke "YYYY-MM-DD"
            } else {
                form.due_date = null;
            }
        });

        watch(() => products.value.name, (name) => {
            if (customerGroup.value === null) {
                notification.warning({
                    title: "Dimohon untuk memilih customer terlebih dahulu",
                    meta: "Agar pemilihan harga barang tepat!",
                    duration: 2500,
                    closable: false,
                });
            }

            let selectedProduct = null as unknown as any;

            //, 'DO'
            if (['DIRECT', 'DIRECT_DEPO'].includes(transaction_details.value.delivery)) {
                // Gunakan produk dari master
                selectedProduct = productMasters.find(product => product.value === products.value.name);
            } else {
                // Gunakan produk dari batch
                selectedProduct = productOptions.find(product => product.value === products.value.name);
            }

            if (selectedProduct) {
                const today = new Date();
                const endDate = new Date(selectedProduct.end_date);

                products.value.code = selectedProduct.code;
                transaction_items.value.product_id = selectedProduct.id;
                transaction_items.value.unit = selectedProduct.unit;
                products.value.last_stock = selectedProduct.last_stock;
                products.value.retail_price = selectedProduct.retail_price;

                //todo : switch statement for handle the price of products
                switch (transaction_details.value.segment_customer) {
                    case "GROSIR":
                        transaction_items.value.real_amount = selectedProduct.retail_price
                        transaction_items.value.amount = selectedProduct.retail_price;
                        break;
                    case "RETAIL":
                        transaction_items.value.real_amount = selectedProduct.restaurant_price
                        transaction_items.value.amount = selectedProduct.restaurant_price;
                        break;
                    case "END_USER":
                        transaction_items.value.real_amount = selectedProduct.price_3
                        transaction_items.value.amount = selectedProduct.price_3;
                        break;
                    case "ALL_SEGMENT":
                        transaction_items.value.real_amount = selectedProduct.all_segment_price
                        transaction_items.value.amount = selectedProduct.all_segment_price;
                        break;
                    default:
                        transaction_items.value.real_amount = selectedProduct.all_segment_price
                        transaction_items.value.amount = products.value.all_segment_price;
                        break;
                }

                // Isi nilai promo_value hanya jika promosi masih berlaku
                if (today <= endDate) {
                    products.value.description = selectedProduct.description;
                    products.value.promo_value_1 = selectedProduct.promo_value_1;
                    products.value.promo_value_2 = selectedProduct.promo_value_2;
                    products.value.promo_value_3 = selectedProduct.promo_value_3;
                    products.value.min = selectedProduct.min;
                    products.value.max = selectedProduct.max;
                    products.value.start_date = selectedProduct.start_date;
                    products.value.end_date = selectedProduct.end_date;
                    products.value.promo_name = selectedProduct.promo_name;
                } else {
                    // Reset promo-related values if the promotion has expired
                    products.value.promo_value_1 = selectedProduct.promo_value_1;
                    products.value.promo_value_2 = selectedProduct.promo_value_2;
                    products.value.promo_value_3 = selectedProduct.promo_value_3;
                    products.value.promo_name = null as unknown as string;
                }

                // Reset product_journals dan tambahkan item baru
                product_journals.value = {
                    action: "OUT",
                    batch_code: selectedProduct.batch_code,
                    expiry_date: selectedProduct.expiry_date,
                    product_id: selectedProduct.id,
                }

                // Tentukan pesan dan warna status berdasarkan last_stock
                if (!['DIRECT', 'DIRECT_DEPO', 'DO'].includes(transaction_details.value.delivery)) {
                    if (products.value.last_stock < 10) {
                        stockMessage.value = `Stok saat ini : (${products.value.last_stock})`;
                        stockStatusColor.value = 'red';
                    } else {
                        stockMessage.value = `Stok saat ini : (${products.value.last_stock})`;
                        stockStatusColor.value = 'black';
                    }
                } else {
                    stockMessage.value = null as unknown as string;
                }
            } else {
                // Reset data jika tidak ada produk yang dipilih
                products.value.code = '';
                transaction_items.value.unit = '';
                stockMessage.value = '';
                stockStatusColor.value = 'black';
                products.value.last_stock = null as unknown as number;
                products.value.retail_price = null as unknown as number;
                products.value.promo_value_1 = null as unknown as number;
                products.value.promo_value_2 = null as unknown as number;
                products.value.promo_value_3 = null as unknown as number;
                products.value.promo_name = null as unknown as string;
                transaction_items.value.amount = null as unknown as number;

                // Reset product_journals
                transaction_items.value.product_journals = [];
            }
        });

        //TODO : create 3 watch for watch discoutns field and calculate it when filled
        watch(
            [
                () => products.value.promo_value_1,
                () => products.value.promo_value_2,
                () => products.value.promo_value_3,
            ],
            () => {
                let originalPrice = transaction_items.value.amount || 0;

                // Reset diskon jika nilainya berubah
                transaction_details.value.total_discount_1 = null;
                transaction_details.value.total_discount_2 = null;
                transaction_details.value.total_discount_3 = null;

                // Cek apakah promo_value_1 berubah dan belum dihitung
                if (products.value.promo_value_1 > 0) {
                    let discount1 = originalPrice * products.value.promo_value_1 / 100;
                    transaction_details.value.total_discount_1 = originalPrice - discount1;
                }

                // Cek apakah promo_value_2 berubah dan belum dihitung
                if (products.value.promo_value_2 > 0 && transaction_details.value.total_discount_1 !== null) {
                    let discount2 = transaction_details.value.total_discount_1 * products.value.promo_value_2 / 100;
                    transaction_details.value.total_discount_2 = transaction_details.value.total_discount_1 - discount2;
                }

                // Cek apakah promo_value_3 berubah dan belum dihitung
                if (products.value.promo_value_3 > 0 && transaction_details.value.total_discount_2 !== null) {
                    let discount3 = transaction_details.value.total_discount_2 * products.value.promo_value_3 / 100;
                    transaction_details.value.total_discount_3 = transaction_details.value.total_discount_2 - discount3;
                }

                // Mengupdate total_price menjadi nilai terakhir yang dihitung dari diskon yang tersedia
                transaction_items.value.total_price =
                    transaction_details.value.total_discount_3 ||
                    transaction_details.value.total_discount_2 ||
                    transaction_details.value.total_discount_1 ||
                    originalPrice;

                transaction_items.value.amount_after_discount = transaction_items.value.total_price;
            }
        );



        // Watch the segment customer and set the product amount
        // watch(() => transaction_details.value.segment_customer, (segment) => {

        // });

        watch(() => transaction_details.value.customer, (name) => {
            const selectedCustomer = customerOptions.find(data => data.label === name);

            // set customer group
            customerGroup.value = selectedCustomer?.partiesGroup?.name;

            if (selectedCustomer) {
                transaction_details.value.customer_address = selectedCustomer.address as any;
                transaction_details.value.npwp = selectedCustomer.npwp as any;
                transaction_details.value.legality = selectedCustomer.legality as any || '';
                transaction_details.value.segment_customer = selectedCustomer.segment_customer;
                form.term_of_payment = selectedCustomer.term_payment ?? 0;
            } else {
                transaction_details.value.customer_address = '';
                transaction_details.value.npwp = '',
                    transaction_details.value.legality = '';
            }
        });

        // Properti computed untuk promosi dan harga diskon
        const hasPromo = computed(() => {
            return products.value.promo_value_1 !== null;
        });

        const isPromoActive = computed(() => {
            if (!products.value.end_date) return false;
            const today = new Date();
            const endDate = new Date(products.value.end_date);
            // Reset waktu agar perbandingan hanya berdasarkan tanggal
            today.setHours(0, 0, 0, 0);
            endDate.setHours(0, 0, 0, 0);
            return endDate >= today;
        });

        const promoPercentage = computed(() => {
            const promo = products.value.promo_value ?? 0;
            return promo;
        });


        const discountedPrice = computed(() => {
            const { amount, quantity } = transaction_items.value;
            const { promo_value, min, max } = products.value;

            if (amount !== null && promo_value !== null) {
                // Cek apakah quantity berada dalam range min dan max
                if (quantity >= min && quantity <= max) {
                    const discountAmount = amount * (promo_value / 100); // Menghitung diskon
                    const discounted = amount - discountAmount; // Mengurangi harga dengan diskon
                    return discounted.toFixed(0); // Mengembalikan harga yang telah dikurangi diskon
                }
            }
            return amount.toFixed(0); // Kembalikan harga asli jika tidak memenuhi syarat
        });

        watch(() => transaction_items.value.amount, (newAmount, oldAmount) => {
            // Ambil harga default berdasarkan segmen pelanggan
            let defaultPrice = null as unknown as number;

            switch (transaction_details.value.segment_customer) {
                case "GROSIR":
                    defaultPrice = products.value.retail_price;
                    break;
                case "RETAIL":
                    defaultPrice = products.value.restaurant_price;
                    break;
                case "END_USER":
                    defaultPrice = products.value.price_3;
                    break;
                case "ALL_SEGMENT":
                    defaultPrice = products.value.all_segment_price;
                    break;
                default:
                    defaultPrice = products.value.all_segment_price;
                    break;
            }

            // Validasi harga baru
            if (newAmount !== null && defaultPrice !== null && newAmount < defaultPrice) {
                // Jika harga baru lebih rendah dari harga default
                Swal.fire({
                    icon: 'error',
                    title: 'Harga Tidak Valid',
                    text: 'Harga tidak boleh lebih rendah dari harga default.',
                }).then(() => {
                    // Reset harga ke harga sebelumnya jika tidak valid
                    transaction_items.value.amount = oldAmount as number;
                });
            }
        }, {
            immediate: true,
            deep: true,
        });




        // const subtotal = computed(() => {
        //     // Menghitung subtotal dari semua produk tanpa mengalikan quantity
        //     const total = form.transaction_items.reduce((total, item) => {
        //         return total + Number(item.total_price ?? 0); // Konversi amount ke number
        //     }, 0);

        //     // Menyimpan subtotal ke dalam form
        //     form.sub_total = total;

        //     // Mengembalikan subtotal yang diformat
        //     // return total;
        //     return formatRupiah(total);
        // });
        const subtotal = computed(() => {
            // Menghitung subtotal dari semua produk menggunakan harga asli sebelum diskon
            const total = form.transaction_items.reduce((total, item) => {
                // Menghitung harga sebelum diskon: quantity * unit_price
                const priceBeforeDiscount = (Number(item.amount) ?? 0) * (Number(item.quantity) ?? 0);
                return total + priceBeforeDiscount; // Menambahkan harga sebelum diskon
            }, 0);

            // Menyimpan subtotal ke dalam form
            form.sub_total = total;

            // Mengembalikan subtotal yang diformat
            return total;
        });


        const totalDiscounts = computed(() => {
            // Menghitung total diskon dari semua produk dalam transaction_items
            const resultDiscounts = form.transaction_items.reduce((total, item) => {
                const quantity = item.quantity || 0;
                const originalPrice = item.amount * quantity;

                // Periksa apakah quantity memenuhi syarat rentang promo
                const isValidPromo = quantity >= (products.value.min || 0) && quantity <= (products.value.max || Infinity);

                if (!isValidPromo) {
                    // Jika tidak memenuhi rentang promo, lewati item ini (tidak ada diskon)
                    return total;
                }

                // Menghitung diskon 1 berdasarkan harga asli
                const discount1 = originalPrice * (item.discount_1 || 0) / 100;

                // Menghitung diskon 2 berdasarkan harga setelah diskon 1
                const discount2 = (originalPrice - discount1) * (item.discount_2 || 0) / 100;

                // Menghitung diskon 3 berdasarkan harga setelah diskon 2
                const discount3 = (originalPrice - discount1 - discount2) * (item.discount_3 || 0) / 100;

                // Menambahkan total diskon untuk setiap item
                const result = total + discount1 + discount2 + discount3;

                form.total_discount = result;

                return result;
            }, 0);

            return resultDiscounts;
        });


        const totalPrice = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            // const subtotal = form.transaction_items.reduce((total, item) => {
            //     return total + Number(item.total_price ?? 0); // Konversi amount ke number
            // }, 0);

            // Menghitung total harga termasuk PPN 11%
            // const totalWithPPN = subtotal + (subtotal * 0.11);
            const totalWithDiscounts = subtotal.value - totalDiscounts.value;

            // Menyimpan total ke dalam form
            const total = Math.round(totalWithDiscounts);

            // form.total = total;

            // Mengembalikan total harga yang diformat
            // return total;
            return totalWithDiscounts;
        });

        const totalPPN = computed(() => {
            // Menghitung PPN 11%
            const ppn = totalPrice.value * 0.11;

            // Membulatkan PPN sesuai aturan yang kamu inginkan
            const roundedPPN = Math.round(ppn);

            // Menyimpan PPN yang sudah dibulatkan ke dalam form
            form.tax_amount = roundedPPN;

            // Mengembalikan nilai PPN yang diformat
            // return roundedPPN;
            return roundedPPN;
        });

        const grandTotal = computed(() => {
            let grandTotal = totalPrice.value;

            // if (transaction_details.value.use_tax) {
            //     grandTotal = totalPrice.value + totalPPN.value
            // } else {
            //     grandTotal = totalPrice.value;
            // }

            form.total = grandTotal;

            return grandTotal;
        });

        const defaultProductJournal = {
            quantity: null as unknown as number,
            amount: null as unknown as number,
            action: "IN",
            batch_code: '',
            expiry_date: null as unknown as string,
            product_id: null as unknown as number,
        };

        // Product journals dimulai dengan satu item default
        const product_journals = ref({
            quantity: null as unknown as number,
            amount: null as unknown as number,
            action: "IN",
            batch_code: '',
            expiry_date: null as unknown as string,
            product_id: null as unknown as number,
        });

        function handleAddProduct() {
            // Pastikan quantity dan stok adalah angka
            const quantity = Number(transaction_items.value.quantity);
            const lastStock = Number(products.value.last_stock);

            if (quantity === null) {
                Swal.fire({
                    icon: 'error',
                    title: 'Quantity kosong!',
                });
                return; // Hentikan eksekusi jika quantity tidak valid
            }

            // Periksa apakah quantity lebih besar dari stok
            if (quantity > lastStock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Quantity barang tidak cukup!',
                    text: `Stok aktual ${lastStock}`,
                });
                return; // Hentikan eksekusi jika quantity tidak valid
            }

            let finalAmount = transaction_items.value.amount; // Default ke harga asli

            if (isPromoActive.value) {
                const quantity = transaction_items.value.quantity; // Pastikan quantity tersedia
                const { min, max, promo_value_3, promo_value_2, promo_value_1 } = products.value;

                // Pastikan min dan max tidak undefined/null
                if (quantity !== undefined && min !== undefined && max !== undefined) {
                    if (quantity >= min && quantity <= max) {
                        if (promo_value_3) {
                            finalAmount = transaction_details.value.total_discount_3;
                        } else if (promo_value_2) {
                            finalAmount = transaction_details.value.total_discount_2;
                        } else if (promo_value_1) {
                            finalAmount = transaction_details.value.total_discount_1;
                        }
                    }
                }
            }

            if (finalAmount === null || isNaN(finalAmount)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nilai harga tidak valid!',
                });
                return;
            }

            // Tentukan harga barang (amount) berdasarkan harga asli
            const amount = Number(transaction_items.value.amount); // Harga asli

            if (amount === null || isNaN(amount)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Dimohon isi semua field produk',
                });
                return;
            }

            // Validasi: Harga tidak boleh berkurang
            if (transaction_items.value.amount < transaction_items.value.real_amount) {
                Swal.fire({
                    icon: 'error',
                    title: 'Harga tidak bisa dikurangi!',
                    text: 'Silakan masukkan harga yang lebih besar atau sama dengan harga sebelumnya.',
                });
                return; // Hentikan eksekusi
            }

            // Hitung total harga barang sebelum diskon (harga satuan * quantity)
            let totalPrice = finalAmount * quantity;

            // Kurangi total price dari program promo yang dibuat (jika ada dan aktif)
            // if (isPromoActive.value) {
            //     if (promoPercentage.value) {
            //         const convertedToPercentage = promoPercentage.value / 100;

            //         // Menghitung diskon berdasarkan persentase
            //         const discountAmount = totalPrice * convertedToPercentage;

            //         // Mengurangi harga total dengan diskon
            //         totalPrice -= discountAmount;
            //     }
            // }

            // Hitung total dari semua diskon yang diisi
            // const totalDiscount =
            //     (transaction_details.value.total_discount_1 || 0) +
            //     (transaction_details.value.total_discount_2 || 0) +
            //     (transaction_details.value.total_discount_3 || 0);

            // // Kurangi total_price dengan total diskon
            // totalPrice -= totalDiscount;

            // Pastikan total_price tidak negatif
            totalPrice = Math.max(totalPrice, 0);

            // Hitung PPN (11%)
            const taxAmount = totalPrice * 0.11; // Pajak PPN 11%
            const totalPriceWithTax = totalPrice + taxAmount;

            // Format angka pajak dan total harga
            const formattedTaxAmount = parseFloat(taxAmount.toFixed(2));
            const formattedTotalPriceWithTax = parseFloat(totalPriceWithTax.toFixed(2));

            // Salin data transaction_items untuk ditambahkan ke tabel
            try {
                const newItem = {
                    unit: transaction_items.value.unit,
                    quantity: quantity,
                    product_id: transaction_items.value.product_id,
                    tax_amount: formattedTaxAmount,
                    amount: amount,
                    amount_after_discount: transaction_items.value.amount_after_discount,
                    tax_id: transaction_items.value.tax_id,
                    total_price: totalPrice, // Total setelah semua diskon
                    discount_1: products.value.promo_value_1 || 0,
                    discount_2: products.value.promo_value_2 || 0,
                    discount_3: products.value.promo_value_3 || 0,
                    total_discount_1: transaction_details.value.total_discount_1,
                    total_discount_2: transaction_details.value.total_discount_2,
                    total_discount_3: transaction_details.value.total_discount_3,
                    product_journals: [
                        {
                            quantity: quantity,
                            amount: amount,
                            action: "OUT",
                            batch_code: product_journals.value.batch_code,
                            expiry_date: product_journals.value.expiry_date,
                            product_id: product_journals.value.product_id,
                        }
                    ],
                    product: {
                        code: products.value.code,
                        unit: transaction_items.value.unit,
                        name: products.value.name,
                    }
                };

                // Tambahkan item ke array form.transaction_items
                form.transaction_items.push(newItem);

                // Notifikasi sukses
                notification.success({
                    title: "Produk ditambahkan!",
                    duration: 1500,
                    closable: false,
                });
            } catch (err) {
                notification.error({
                    title: "Produk gagal ditambahkan!",
                    duration: 2500,
                    closable: false,
                });
            } finally {
                products.value.name = null;
                transaction_items.value.quantity = null;
            }
        }


        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        function handleSubmitCo() {
            form.transaction_details = [
                {
                    name: "Tanggal CO",
                    category: 'CO Date',
                    value: transaction_details.value.customer_order_date,
                    data_type: 'datetime',
                },
                {
                    name: 'Nama Customer',
                    category: 'Customer',
                    value: transaction_details.value.customer,
                    data_type: 'string',
                },
                {
                    name: 'Legalitas',
                    category: 'Legality',
                    value: transaction_details.value.legality,
                    data_type: 'string',
                },
                {
                    name: 'Alamat Customer',
                    category: 'Customer Address',
                    value: transaction_details.value.customer_address,
                    data_type: 'string',
                },
                {
                    name: "Pengiriman",
                    category: "Delivery",
                    value: transaction_details.value.delivery,
                    data_type: 'string',
                },
                {
                    name: 'Cashback',
                    category: 'Cashback',
                    value: transaction_details.value.cashback as any ?? '0',
                    data_type: 'float',
                },
                {
                    name: 'Biaya Bongkar',
                    category: 'Unloading Cost',
                    value: transaction_details.value.unloading_cost as any ?? '0',
                    data_type: 'float',
                },
                {
                    name: "Salesman",
                    category: "Salesman",
                    value: transaction_details.value.salesman,
                    data_type: 'string',
                },
                {
                    name: "Gudang",
                    category: "Warehouse",
                    value: "DNP",
                    data_type: "string",
                },
                {
                    name: "Alokasi",
                    category: "Allocation",
                    value: "DNP",
                    data_type: "string",
                },
                {
                    name: "NPWP",
                    category: "NPWP",
                    value: transaction_details.value.npwp,
                    data_type: "string",
                },
                {
                    name: "Segmen Customer",
                    category: "SEGMENT",
                    value: transaction_details.value.segment_customer ? transaction_details.value.segment_customer : "NO SEGMENT",
                    data_type: "string",
                },
                {
                    name: "Generated",
                    category: "Generating",
                    value: "false",
                    data_type: 'boolean',
                },
                {
                    name: "Pengajuan Diskon",
                    category: "Discount Submission",
                    value: transaction_details.value.submission_discount,
                    data_type: "string",
                },
                {
                    name: "Status Pengajuan",
                    category: "Submission Status",
                    value: "-",
                    data_type: "boolean",
                },
                {
                    name: "PO Customer",
                    category: "PO Customer",
                    value: transaction_details.value.po_customer ?? "no-value",
                    data_type: "string",
                },
                {
                    name: "Pajak",
                    category: "Taxes",
                    value: String(transaction_details.value.use_tax),
                    data_type: "boolean",
                },
                {
                    name: "Invoice",
                    category: "Condition Invoice",
                    value: String(condition_payment.value.invoice ?? "false"),
                    data_type: "string",
                },
                {
                    name: "Travel Document",
                    category: "Condition Travel Document",
                    value: String(condition_payment.value.travel_document ?? "false"),
                    data_type: "string",
                },
                {
                    name: "Tax Invoice",
                    category: "Condition Tax Invoice",
                    value: String(condition_payment.value.tax_invoice ?? "false"),
                    data_type: "string",
                },
                {
                    name: "Receipt",
                    category: "Condition Receipt",
                    value: String(condition_payment.value.receipt ?? "false"),
                    data_type: "string",
                },
                {
                    name: "Item Receipt",
                    category: "Condition Item Receipt",
                    value: String(condition_payment.value.items_receipt ?? "false"),
                    data_type: "string"
                }
            ];

            //replace real amount of product to amount after discount
            // form.transaction_items.forEach((item) => {
            //     const { amount, amount_after_discount, quantity } = item;

            //     if (amount_after_discount && amount_after_discount < amount) {
            //         item.amount = amount_after_discount;
            //     }

            //     item.total_price = item.amount * quantity;
            // })

            form.post(route('sales.create-co-dnp.post'), {
                onError(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message,
                    });
                },
                onSuccess(page) {
                    // Reset form dengan nilai awal
                    form.document_code = (page.props.coNumber as string);
                    form.term_of_payment = null as unknown as number;
                    form.due_date = null as unknown as string;
                    form.description = '';
                    form.sub_total = null as unknown as number;
                    form.total = null as unknown as number;
                    form.tax_amount = null as unknown as number;
                    form.transaction_details = [];
                    form.transaction_items = [];
                    transaction_details.value = {
                        cashback: null as unknown as number,
                        customer: '',
                        customer_address: '',
                        npwp: '',
                        customer_order_date: (page.props.dateNow),
                        legality: '',
                        salesman: ((page.props.auth as any).user.fullname),
                        total_discount_1: null as unknown as number,
                        total_discount_2: null as unknown as number,
                        total_discount_3: null as unknown as number,
                        transportation_cost: null as unknown as number,
                        unloading_cost: null as unknown as number,
                        po_customer: null as unknown as string,
                        delivery: null as unknown as string,
                        segment_customer: null as unknown as string,
                    };

                    products.value = {
                        code: '',
                        unit: '',
                        name: '',
                        last_stock: null as unknown as number,
                        transaction_items: [],
                    };

                    transaction_items.value = {
                        unit: '',
                        discount_1: null as unknown as number,
                        discount_2: null as unknown as number,
                        discount_3: null as unknown as number,
                        total_price: null as unknown as number,
                        total_price_discount: null as unknown as number,
                        quantity: null as unknown as number,
                        tax_amount: null as unknown as number,
                        amount: null as unknown as number,
                        product_id: null as unknown as number,
                        tax_id: null as unknown as number,
                    };
                    form.transaction_items.splice(0, form.transaction_items.length);

                    Swal.fire({
                        icon: 'success',
                        title: page.props.flash.success,
                    });
                }
            })
        }

        const ppnOptions = [
            { label: "PPN", value: true },
            { label: "NON-PPN", value: false },
        ]

        const sendType = [
            { label: "DEPO BEKASI", value: "DEPO BEKASI" },
            { label: "DIRECT", value: "DIRECT" },
            { label: "DIRECT DEPO", value: "DIRECT_DEPO" },
            { label: "Beli DO", value: "DO" },
        ];

        const segmentCustomer = [
            { label: "GROSIR", value: "GROSIR" },
            { label: "RETAIL", value: "RETAIL" },
            { label: "END USER", value: "END_USER" },
            { label: "ALL SEGMENT", value: "ALL_SEGMENT" }
        ];

        const discountSubmission = [
            { label: "PENGAJUAN DISKON", value: "SUBMIT" },
            { label: "TIDAK AJUKAN", value: "NOT SUBMIT" },
        ]

        const termPaymentOptions = (page.props.payment_terms as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const customerOptions = (page.props.customers as any[]).map((data) => ({
            label: data.name,
            value: data.name,
            legality: data.legality,
            address: data.address,
            npwp: data.npwp,
            term_payment: data.term_payment,
            partiesGroup: data.parties_group,
            segment_customer: data.segment_customer,
        }));



        return {
            columns: createColumns(),
            handleAddProduct,
            removeProduct,
            handleSubmitCo,
            formatRupiah,
            hasPromo,
            isPromoActive,
            promoPercentage,
            discountedPrice,
            segmentCustomer,
            dayjs,
            form,
            termPaymentOptions,
            transaction_details,
            products,
            transaction_items,
            ppnOptions,
            customerOptions,
            productOptions,
            loading,
            totalPPN,
            subtotal,
            totalPrice,
            stockStatusColor,
            stockMessage,
            totalDiscounts,
            grandTotal,
            sendType,
            productMasters,
            discountSubmission,
            availableProducts,
            condition_payment,
            handleSearchCustomer: (query: string) => {
                if (!query.length) {
                    customerOptionsRef.value = []
                    return
                }
                loading.value = true
                window.setTimeout(() => {
                    customerOptionsRef.value = customerOptions.filter(
                        item => ~item.label.indexOf(query)
                    )
                    loading.value = false
                }, 1000)
            },
            handleSearchProduct: (query: string) => {
                if (!query.length) {
                    productsOptionsRef.value = []
                    return
                }
                loadingProducts.value = true
                window.setTimeout(() => {
                    productsOptionsRef.value = productOptions.filter(
                        item => ~item.label.indexOf(query)
                    )
                    loadingProducts.value = false
                }, 1000)
            },
        }
    },
    components: {
        TitlePage,
        Head,
        RequiredMark
    }
})
</script>
