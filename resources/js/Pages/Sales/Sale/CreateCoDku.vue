<template>

    <Head title="Create CO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="CUSTOMER ORDER | DKU" />
        <!-- INPUT CO FORM -->
        <div class="card shadow" style="border: none;">
            <!-- INPUT CO -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR CO <span class="text-danger">*</span></label>
                            <n-input size="large" readonly v-model:value="form.document_code" placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL DIBUAT CO<span class="text-danger">*</span></label>
                            <n-input size="large" readonly v-model:value="transaction_details.customer_order_date"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NAMA CUSTOMER<span class="text-danger">*</span></label>
                            <n-select filterable :loading="loading" :options="customerOptions" size="large"
                                placeholder="" v-model:value="transaction_details.customer" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">LEGALITAS<span class="text-danger">*</span></label>
                            <n-input size="large" v-model:value="transaction_details.legality" placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">ALAMAT CUSTOMER<span class="text-danger">*</span></label>
                            <n-input size="large" v-model:value="transaction_details.customer_address" placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TERMIN<span class="text-danger">*</span></label>
                            <n-input size="large" v-model:value="form.term_of_payment" placeholder="">
                                <template #suffix>HARI</template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL JATUH TEMPO<span class="text-danger">*</span></label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field8"
                                size="large" v-model:formatted-value="form.due_date" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">SALESMAN<span class="text-danger">*</span></label>
                            <n-input size="large" v-model:value="transaction_details.salesman" readonly
                                placeholder="" />
                        </div>
                    </div>
                    <!-- <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA ANGKUTAN<span class="text-danger">*</span></label>
                            <n-input size="large" placeholder="" v-model:value="transaction_details.transportation_cost"
                                @input="(value) => transaction_details.transportation_cost = value.replace(/\D/g, '')">
                                <template #prefix>
                                    Rp
                                </template>
                            </n-input>
                        </div>
                    </div> -->
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">CASHBACK + PPH 4%</label>
                            <n-input size="large" v-model:value="transaction_details.cashback" placeholder=""
                                @input="(value) => transaction_details.cashback = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA BONGKAR</label>
                            <n-input size="large" v-model:value="transaction_details.unloading_cost" placeholder=""
                                @input="(value) => transaction_details.unloading_cost = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INPUT PRODUCTS -->
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row g-3">
                    <!-- INPUT PRODUCTS FORM -->
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NAMA PRODUK</label>
                        <n-select filterable :options="productOptions" placeholder="" size="large"
                            v-model:value="products.name" />
                        <!-- Warning quantity atau status quantity -->
                        <span :style="{ color: stockStatusColor }">
                            {{ stockMessage }}
                        </span>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">QUANTITY</label>
                        <n-input size="large" v-model:value="transaction_items.quantity" placeholder="" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">KEMASAN</label>
                        <n-input size="large" v-model:value="transaction_items.unit" readonly placeholder="" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">HARGA PRODUK</label>
                        <n-input size="large" v-model:value="transaction_items.amount" placeholder="">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                    <!-- Tambahan kolom untuk Promo, Deskripsi, dan Harga Diskon -->
                    <div v-if="hasPromo && isPromoActive"
                        class="p-3 bg-info bg-opacity-10 border border-info border-start-0 rounded-end">
                        <div class="row g-3">
                            <span>PRODUK INI MENDAPATKAN PROMO {{ products.promo_name }}</span>
                            <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                <label for="">PROMO </label>
                                <n-input size="large" :value="promoPercentage + '%'" readonly />
                            </div>
                            <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                <label for="">DESKRIPSI</label>
                                <n-input size="large" :value="products.description" readonly />
                            </div>
                            <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                <label for="">MINIMAL</label>
                                <n-input size="large" :value="products.min" readonly />
                            </div>
                            <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                                <label for="">MAKSIMAL</label>
                                <n-input size="large" :value="products.max" readonly />
                            </div>
                        </div>
                    </div>

                    <!-- INPUT DISCOUNT FORM -->
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">DISCOUNT 1</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_items.discount_1"
                            @input="(value) => transaction_items.discount_1 = value.replace(/\D/g, '')">
                            <template #suffix>
                                %
                            </template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">HARGA</label>
                        <n-input size="large" readonly placeholder=""
                            v-model:value="transaction_details.total_discount_1">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">DISCOUNT 2</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_items.discount_2"
                            @input="(value) => transaction_items.discount_2 = value.replace(/\D/g, '')">
                            <template #suffix>
                                %
                            </template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">HARGA</label>
                        <n-input size="large" readonly placeholder=""
                            v-model:value="transaction_details.total_discount_2">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">DISCOUNT 3</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_items.discount_3"
                            @input="(value) => transaction_items.discount_3 = value.replace(/\D/g, '')">
                            <template #suffix>
                                %
                            </template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">HARGA</label>
                        <n-input size="large" readonly placeholder=""
                            v-model:value="transaction_details.total_discount_3">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                </div>
                <n-button type="primary" class="ms-auto" @click="handleAddProduct">Tambah Produk</n-button>
            </div>
        </div>

        <!-- PRODUCT CHOOSEN LIST -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="form.transaction_items" />
            </div>
        </div>

        <!-- CALCULATION RESULT -->
        <div class="card shadow border-0 mb-4">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>{{ formatRupiah(subtotal) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2" v-if="totalDiscounts > 0">
                    <span>Diskon</span>
                    <span>{{ formatRupiah(totalDiscounts) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total harga</span>
                    <span>{{ formatRupiah(totalPrice) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>{{ formatRupiah(totalPPN) }}</span>
                </div>
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
import { ProductCustomerOrder } from '../../../types/dto';
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
            unloading_cost: null as unknown as number,
            total_discount_1: null as any,
            total_discount_3: null as any,
            total_discount_2: null as any,
        });


        const products = ref({
            code: '',
            unit: '',
            name: '',
            last_stock: null as unknown as number,
            retail_price: null as unknown as number,
            promo_value: null as unknown as number,
            description: '',
            min: null as unknown as number,
            max: null as unknown as number,
            start_date: null as unknown as string,
            end_date: null as unknown as string,
            promo_name: null as unknown as string,
            transaction_items: [] as TransactionItems[],
        });

        const transaction_items = ref({
            unit: '',
            quantity: null as unknown as number,
            tax_amount: 0,
            amount: null as unknown as number,
            product_id: null as unknown as number,
            tax_id: null as unknown as number,
            discount_1: null as unknown as number,
            discount_2: null as unknown as number,
            discount_3: null as unknown as number,
            total_price: null as unknown as number,
            product_journals: [] as any[],
            // total_price_discount: null as unknown as number,
        });

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

        //TODO : create 3 watch for watch discoutns field and calculate it when filled
        watch(
            [
                () => transaction_items.value.discount_1,
                () => transaction_items.value.discount_2,
                () => transaction_items.value.discount_3,
            ],
            () => {
                let originalPrice = transaction_items.value.amount || 0;

                // Reset diskon jika nilainya berubah
                transaction_details.value.total_discount_1 = null;
                transaction_details.value.total_discount_2 = null;
                transaction_details.value.total_discount_3 = null;

                // Cek apakah diskon 1 berubah dan belum dihitung
                if (transaction_items.value.discount_1 !== null && transaction_items.value.discount_1 !== undefined && transaction_items.value.discount_1 > 0) {
                    let discount1 = originalPrice * transaction_items.value.discount_1 / 100;
                    transaction_details.value.total_discount_1 = originalPrice - discount1;
                }

                // Cek apakah diskon 2 berubah dan belum dihitung
                if (transaction_items.value.discount_2 !== null && transaction_items.value.discount_2 !== undefined && transaction_items.value.discount_2 > 0 && transaction_details.value.total_discount_1 !== null) {
                    let discount2 = transaction_details.value.total_discount_1 * transaction_items.value.discount_2 / 100;
                    transaction_details.value.total_discount_2 = transaction_details.value.total_discount_1 - discount2;
                }

                // Cek apakah diskon 3 berubah dan belum dihitung
                if (transaction_items.value.discount_3 !== null && transaction_items.value.discount_3 !== undefined && transaction_items.value.discount_3 > 0 && transaction_details.value.total_discount_2 !== null) {
                    let discount3 = transaction_details.value.total_discount_2 * transaction_items.value.discount_3 / 100;
                    transaction_details.value.total_discount_3 = transaction_details.value.total_discount_2 - discount3;
                }

                // Mengupdate total_price menjadi nilai terakhir yang dihitung dari diskon yang tersedia
                transaction_items.value.total_price = transaction_details.value.total_discount_3 || transaction_details.value.total_discount_2 || transaction_details.value.total_discount_1 || originalPrice;
            }
        );




        watch(() => transaction_details.value.customer, (name) => {
            const selectedCustomer = customerOptions.find(data => data.label === name);

            if (selectedCustomer) {
                transaction_details.value.customer_address = selectedCustomer.address as any;
                transaction_details.value.npwp = selectedCustomer.npwp as any;
                transaction_details.value.legality = selectedCustomer.legality as any || '';
                form.term_of_payment = selectedCustomer.term_payment ?? 0;
            } else {
                transaction_details.value.customer_address = '';
                transaction_details.value.npwp = '',
                    transaction_details.value.legality = '';
            }
        });

        // Properti computed untuk promosi dan harga diskon
        const hasPromo = computed(() => {
            return products.value.promo_value !== null;
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

        watch(() => products.value.name, (name) => {
            const selectedProduct = productOptions.find(data => data.value === name);

            if (selectedProduct) {
                const today = new Date();
                const endDate = new Date(selectedProduct.end_date);

                products.value.code = selectedProduct.code;
                transaction_items.value.product_id = selectedProduct.id;
                transaction_items.value.unit = selectedProduct.unit;
                products.value.last_stock = selectedProduct.last_stock;
                products.value.retail_price = selectedProduct.retail_price;

                // Isi nilai promo_value hanya jika promosi masih berlaku
                if (today <= endDate) {
                    products.value.description = selectedProduct.description;
                    products.value.promo_value = selectedProduct.promo_value;
                    products.value.min = selectedProduct.min;
                    products.value.max = selectedProduct.max;
                    products.value.start_date = selectedProduct.start_date;
                    products.value.end_date = selectedProduct.end_date;
                    products.value.promo_name = selectedProduct.promo_name;
                } else {
                    // Reset promo-related values if the promotion has expired
                    products.value.promo_value = null;
                    products.value.promo_name = null;
                }

                // Reset product_journals dan tambahkan item baru
                product_journals.value = {
                    action: "OUT",
                    batch_code: selectedProduct.batch_code,
                    expiry_date: selectedProduct.expiry_date,
                    product_id: selectedProduct.id,
                }

                // Tentukan pesan dan warna status berdasarkan last_stock
                if (products.value.last_stock < 10) {
                    stockMessage.value = `Stok saat ini : (${products.value.last_stock})`;
                    stockStatusColor.value = 'red';
                } else {
                    stockMessage.value = `Stok saat ini : (${products.value.last_stock})`;
                    stockStatusColor.value = 'black';
                }
            } else {
                // Reset data jika tidak ada produk yang dipilih
                products.value.code = '';
                transaction_items.value.unit = '';
                stockMessage.value = '';
                stockStatusColor.value = 'black';
                products.value.last_stock = null;
                products.value.retail_price = null;
                products.value.promo_value = null;
                products.value.promo_name = null;

                // Reset product_journals
                transaction_items.value.product_journals = [];
            }
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

                // Menghitung diskon 1 berdasarkan harga asli
                const discount1 = originalPrice * (item.discount_1 || 0) / 100;

                // Menghitung diskon 2 berdasarkan harga setelah diskon 1
                const discount2 = (originalPrice - discount1) * (item.discount_2 || 0) / 100;

                // Menghitung diskon 3 berdasarkan harga setelah diskon 2
                const discount3 = (originalPrice - discount1 - discount2) * (item.discount_3 || 0) / 100;

                // Menambahkan total diskon untuk setiap item
                return total + discount1 + discount2 + discount3;
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
            const grandTotal = totalPrice.value + totalPPN.value;

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
            if (transaction_items.value.discount_3) {
                finalAmount = transaction_details.value.total_discount_3;
            } else if (transaction_items.value.discount_2) {
                finalAmount = transaction_details.value.total_discount_2;
            } else if (transaction_items.value.discount_1) {
                finalAmount = transaction_details.value.total_discount_1;
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

            // Hitung total harga barang sebelum diskon (harga satuan * quantity)
            let totalPrice = finalAmount * quantity;

            // kurangi total price dari program promo yang dibuat (jika ada dan aktif)
            if (isPromoActive.value) {
                if (promoPercentage.value) {
                    const convertedToPercentage = promoPercentage.value / 100;

                    // Menghitung diskon berdasarkan persentase
                    const discountAmount = totalPrice * convertedToPercentage;

                    // Mengurangi harga total dengan diskon
                    totalPrice -= discountAmount;
                }
            }

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
                    tax_id: transaction_items.value.tax_id,
                    total_price: totalPrice, // Total setelah semua diskon
                    discount_1: transaction_items.value.discount_1 || 0,
                    discount_2: transaction_items.value.discount_2 || 0,
                    discount_3: transaction_items.value.discount_3 || 0,
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
                // RESET FORM YANG DIISI
                transaction_items.value = {
                    unit: "",
                    quantity: null as unknown as number,
                    product_id: null,
                    tax_id: null,
                    discount_1: null as unknown as number,
                    discount_2: null as unknown as number,
                    discount_3: null as unknown as number,
                    amount: null as unknown as number,
                };

                transaction_details.value = {
                    total_discount_1: null as unknown as number,
                    total_discount_2: null as unknown as number,
                    total_discount_3: null as unknown as number,
                };

                product_journals.value = {
                    batch_code: "",
                    expiry_date: null,
                    product_id: null,
                };

                products.value = {
                    code: "",
                    name: "",
                    last_stock: null as unknown as number,
                };
            }
        }

        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        function handleSubmitCo() {
            form.transaction_details = [
                // {
                //     name: "Total Harga Diskon 1",
                //     category: "Total Discount 1",
                //     value: form.transaction_items.find(data => data.total_discount_1)?.total_discount_1 ? '0' : '0',
                //     data_type: 'float',
                // },
                // {
                //     name: "Total Harga Diskon 2",
                //     category: "Total Discount 2",
                //     value: transaction_details.value.total_discount_2 ? '0' : '0',
                //     data_type: 'float',
                // },
                // {
                //     name: "Total Harga Diskon 3",
                //     category: "Total Discount 3",
                //     value: transaction_details.value.total_discount_3 ? '0' : '0',
                //     data_type: 'float',
                // },
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
                // {
                //     name: 'Biaya Angkutan',
                //     category: 'Transportation Cost',
                //     value: transaction_details.value.transportation_cost as any,
                //     data_type: 'float',
                // },
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
                    value: "DKU",
                    data_type: "string",
                },
                {
                    name: "Alokasi",
                    category: "Allocation",
                    value: "DKU",
                    data_type: "string",
                },
                {
                    name: "NPWP",
                    category: "NPWP",
                    value: transaction_details.value.npwp,
                    data_type: "string",
                },
                {
                    name: "Generated",
                    category: "Generating",
                    value: "false",
                    data_type: 'boolean',
                }
            ];

            form.post(route('sales.create-co-dku.post'), {
                onError(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message,
                    });
                },
                onSuccess() {
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
                        unloading_cost: null as unknown as number
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
                        title: 'CO berhasil dibuat',
                    });
                }
            })
        }

        const termPaymentOptions = (page.props.payment_terms as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const customerOptions = (page.props.customers as Parties[]).map((data) => ({
            label: data.name,
            value: data.name,
            legality: data.legality,
            address: data.address,
            npwp: data.npwp,
            term_payment: data.term_payment,
        }));

        const productOptions = (page.props.products as any[]).map((data) => ({
            label: `${data.name} - ${data.batch_code}`,
            value: `${data.name} - ${data.batch_code}`,
            unit: data.unit,
            code: data.code,
            warehouse: data.warehouse,
            batch_code: data.batch_code,
            expiry_date: data.expiry_date,
            last_stock: data.last_stock,
            status: data.status,
            retail_price: data.retail_price,
            id: data.id,
            promo_value: data.promo_value,
            description: data.description,
            min: data.min,
            max: data.max,
            start_date: data.start_date,
            end_date: data.end_date,
            promo_name: data.promo_name,
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
            dayjs,
            form,
            termPaymentOptions,
            transaction_details,
            products,
            transaction_items,
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
        Head
    }
})
</script>