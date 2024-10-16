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
                            <n-input size="large" disabled v-model:value="form.document_code" placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL DIBUAT CO<span class="text-danger">*</span></label>
                            <n-input size="large" disabled v-model:value="transaction_details.customer_order_date"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NAMA CUSTOMER<span class="text-danger">*</span></label>
                            <n-select filterable :loading="loading" :options="customerOptions" clearable
                                remoteplaceholder="" @search="handleSearchCustomer" size="large"
                                v-model:value="transaction_details.customer" />
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
                            <n-select size="large" v-model:value="form.term_of_payment" :options="termPaymentOptions"
                                placeholder="" />
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
                            <n-input size="large" v-model:value="transaction_details.salesman" disabled
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA ANGKUTAN<span class="text-danger">*</span></label>
                            <n-input size="large" placeholder="" v-model:value="transaction_details.transportation_cost"
                                @input="(value) => transaction_details.transportation_cost = value.replace(/\D/g, '')">
                                <template #prefix>
                                    Rp
                                </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">CASHBACK<span class="text-danger">*</span></label>
                            <n-input size="large" v-model:value="transaction_details.cashback" placeholder=""
                                @input="(value) => transaction_details.cashback = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA BONGKAR<span class="text-danger">*</span></label>
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
                        <n-select filterable :loading="loading" :options="productOptions" clearable remote
                            placeholder="" @search="handleSearchProduct" size="large" v-model:value="products.name" />
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
                        <n-input size="large" v-model:value="transaction_items.unit" disabled placeholder="" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">HARGA PRODUK</label>
                        <n-input size="large" v-model:value="transaction_items.amount" placeholder="">
                            <template #prefix>Rp</template>
                        </n-input>
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
                        <n-input size="large" disabled placeholder=""
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
                        <n-input size="large" disabled placeholder=""
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
                        <n-input size="large" disabled placeholder=""
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
                    <span>{{ subtotal }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>{{ totalPPN }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total harga</span>
                    <span>{{ totalPrice }}</span>
                </div>
                <div class="d-flex flex-column w-100 justify-content-between mt-2 gap-3">
                    <div class="d-flex justify-content-between">
                        <span>TERM OF PAYMENT</span>
                        <span class="fw-bold">{{ form.term_of_payment.replace("_", "") }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>JATUH TEMPO</span>
                        <span class="fw-bold">{{ form.due_date ? dayjs(form.due_date).format('dddd, D MMMM YYYY') : ''
                            }}</span>
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
                    width: 200,
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
                    title: "TOTAL HARGA",
                    key: 'total',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total_price ?? 0);
                    }
                },
                {
                    title: "TOTAL HARGA (INC PPN)",
                    key: 'tax_amount',
                    width: 250,
                    render(row) {
                        return formatRupiah(row.tax_amount ?? 0);
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
                    title: "TOTAL DISCOUNT 1",
                    key: 'total_price_discount_1',
                    width: 250,
                    render(row) {
                        const discount1 = row.discount_1 ?? 0;
                        const result_discount_1 = row.amount * (discount1 / 100);
                        return formatRupiah(result_discount_1);
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
                    title: "TOTAL DISCOUNT 2",
                    key: 'total_price_discount_2',
                    width: 250,
                    render(row) {
                        const discount1 = row.discount_1 ?? 0;
                        const discount2 = row.discount_2 ?? 0;

                        // Total setelah discount 1
                        const remaining_after_discount_1 = row.amount * (1 - discount1 / 100);

                        // Diskon 2 dari harga sisa setelah diskon 1
                        const result_discount_2 = remaining_after_discount_1 * (discount2 / 100);
                        return formatRupiah(result_discount_2);
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
                    title: "TOTAL DISCOUNT 3",
                    key: 'total_price_discount_3',
                    width: 250,
                    render(row) {
                        const discount1 = row.discount_1 ?? 0;
                        const discount2 = row.discount_2 ?? 0;
                        const discount3 = row.discount_3 ?? 0;

                        // Total setelah diskon 1 dan 2
                        const remaining_after_discount_1 = row.amount * (1 - discount1 / 100);
                        const remaining_after_discount_2 = remaining_after_discount_1 * (1 - discount2 / 100);

                        // Diskon 3 dari harga sisa setelah diskon 2
                        const result_discount_3 = remaining_after_discount_2 * (discount3 / 100);
                        return formatRupiah(result_discount_3);
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
            term_of_payment: '',
            tax_amount: null as unknown as number,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_details = ref({
            customer_order_date: (page.props.dateNow),
            customer: '',
            legality: '',
            npwp: '',
            customer_address: '',
            salesman: (page.props.auth as any).user.fullname,
            transportation_cost: null as unknown as number,
            cashback: null as unknown as number,
            unloading_cost: null as unknown as number,
            total_discount_1: null as unknown as number,
            total_discount_2: null as unknown as number,
            total_discount_3: null as unknown as number,
        });

        const products = ref({
            code: '',
            unit: '',
            name: '',
            last_stock: null as unknown as number,
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
            total_price_discount: null as unknown as number,
        });

        // const discounts = ref({
        //     total_discount_1: 0,
        //     total_discount_2: 0,
        //     total_discount_3: 0,
        // });

        //TODO : create 3 watch for watch discoutns field and calculate it when filled
        watch(
            [
                () => transaction_items.value.discount_1,
                () => transaction_items.value.discount_2,
                () => transaction_items.value.discount_3
            ],
            () => {
                let originalPrice = transaction_items.value.amount || 0;

                // Jika discount_1 diisi, hitung total_discount_1
                if (transaction_items.value.discount_1 !== null && transaction_items.value.discount_1 !== undefined && transaction_items.value.discount_1 > 0) {
                    let discount1 = originalPrice * transaction_items.value.discount_1 / 100;
                    transaction_details.value.total_discount_1 = originalPrice - discount1;
                } else {
                    transaction_details.value.total_discount_1 = null as unknown as number; // Kosongkan jika tidak ada discount_1
                }

                // Jika discount_2 diisi, hitung total_discount_2 menggunakan hasil dari total_discount_1
                if (transaction_items.value.discount_2 !== null && transaction_items.value.discount_2 !== undefined && transaction_items.value.discount_2 > 0 && transaction_details.value.total_discount_1 !== null) {
                    let discount2 = transaction_details.value.total_discount_1 * transaction_items.value.discount_2 / 100;
                    transaction_details.value.total_discount_2 = transaction_details.value.total_discount_1 - discount2;
                } else {
                    transaction_details.value.total_discount_2 = null as unknown as number; // Kosongkan jika tidak ada discount_2
                }

                // Jika discount_3 diisi, hitung total_discount_3 menggunakan hasil dari total_discount_2
                if (transaction_items.value.discount_3 !== null && transaction_items.value.discount_3 !== undefined && transaction_items.value.discount_3 > 0 && transaction_details.value.total_discount_2 !== null) {
                    let discount3 = transaction_details.value.total_discount_2 * transaction_items.value.discount_3 / 100;
                    transaction_details.value.total_discount_3 = transaction_details.value.total_discount_2 - discount3;
                } else {
                    transaction_details.value.total_discount_3 = null as unknown as number; // Kosongkan jika tidak ada discount_3
                }

                // Mengupdate total_price menjadi nilai terakhir yang dihitung dari diskon yang tersedia
                transaction_items.value.total_price = transaction_details.value.total_discount_3 || transaction_details.value.total_discount_2 || transaction_details.value.total_discount_1 || originalPrice;
            }
        );

        // watch(() => transaction_items.value.quantity, (quantity) => {

        // });

        watch(() => transaction_details.value.customer, (name) => {
            const selectedCustomer = customerOptions.find(data => data.label === name);

            if (selectedCustomer) {
                transaction_details.value.customer_address = selectedCustomer.address as any;
                transaction_details.value.legality = selectedCustomer.legality as any || '';
            } else {
                transaction_details.value.customer_address = '';
                transaction_details.value.legality = '';
            }
        });

        watch(() => products.value.name, (name) => {
            const selectedProduct = productOptions.find(data => data.label === name);

            if (selectedProduct) {
                products.value.code = selectedProduct.code;
                transaction_items.value.product_id = selectedProduct.id as number;
                transaction_items.value.unit = selectedProduct.unit as any;
                products.value.last_stock = selectedProduct.last_stock; // Set last_stock

                // Tentukan pesan dan warna status berdasarkan last_stock
                if (products.value.last_stock < 10) {
                    stockMessage.value = `Stok saat ini : (${products.value.last_stock})`;
                    stockStatusColor.value = 'red'; // Merah jika stok kurang dari 10
                } else {
                    stockMessage.value = `Stok saat ini : (${products.value.last_stock})`;
                    stockStatusColor.value = 'black'; // Default hitam
                }
            } else {
                products.value.code = '';
                transaction_items.value.unit = '';
                stockMessage.value = ''; // Kosongkan pesan jika tidak ada produk yang dipilih
                stockStatusColor.value = 'black'; // Reset warna ke default
            }
        });

        const totalPPN = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = form.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung PPN 11%
            const ppn = subtotal * 0.11;

            // Membulatkan PPN sesuai aturan yang kamu inginkan
            const roundedPPN = Math.round(ppn);

            // Menyimpan PPN yang sudah dibulatkan ke dalam form
            form.tax_amount = roundedPPN;

            // Mengembalikan nilai PPN yang diformat
            // return roundedPPN;
            return formatRupiah(roundedPPN);
        });


        const subtotal = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const total = form.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menyimpan subtotal ke dalam form
            form.sub_total = total;

            // Mengembalikan subtotal yang diformat
            // return total;
            return formatRupiah(total);
        });

        const totalPrice = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = form.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung total harga termasuk PPN 11%
            const totalWithPPN = subtotal + (subtotal * 0.11);

            // Menyimpan total ke dalam form
            const roundedTotalWithPpn = Math.round(totalWithPPN);

            form.total = roundedTotalWithPpn;

            // Mengembalikan total harga yang diformat
            // return roundedTotalWithPpn;
            return formatRupiah(totalWithPPN);
        });

        function handleAddProduct() {
            console.log('triggered');

            // Pastikan kedua nilai adalah angka
            const quantity = Number(transaction_items.value.quantity);
            const lastStock = Number(products.value.last_stock);

            console.log('Transaction Quantity:', quantity);
            console.log('Last Stock:', lastStock);

            // Periksa apakah quantity lebih besar dari stok
            if (quantity > lastStock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Quantity melebihi stok yang tersedia (${lastStock})!`,
                });
                return; // Hentikan eksekusi jika quantity tidak valid
            }

            const ppnAmount = transaction_items.value.amount * 0.11;
            const total = transaction_items.value.amount * quantity;
            const roundedTotal = Math.round(total);
            const roundedPpnAmount = Math.round(ppnAmount);

            form.transaction_items.push({
                unit: transaction_items.value.unit,
                quantity: quantity,
                product_id: transaction_items.value.product_id,
                tax_amount: roundedPpnAmount,
                amount: transaction_items.value.amount,
                tax_id: transaction_items.value.tax_id,
                total_price: roundedTotal,
                discount_1: transaction_items.value.discount_1 || 0,
                discount_2: transaction_items.value.discount_2 || 0,
                discount_3: transaction_items.value.discount_3 || 0,
                product: {
                    code: products.value.code,
                    unit: transaction_items.value.unit,
                    name: products.value.name,
                }
            });

            notification.success({
                title: "Produk ditambahkan!",
                duration: 1500,
                closable: false,
            });
        }


        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        function handleSubmitCo() {
            form.transaction_details = [
                {
                    name: "Total Harga Diskon 1",
                    category: "Total Discount 1",
                    value: transaction_details.value.total_discount_1 ? '0' : '0',
                    data_type: 'float',
                },
                {
                    name: "Total Harga Diskon 2",
                    category: "Total Discount 2",
                    value: transaction_details.value.total_discount_2 ? '0' : '0',
                    data_type: 'float',
                },
                {
                    name: "Total Harga Diskon 3",
                    category: "Total Discount 3",
                    value: transaction_details.value.total_discount_3 ? '0' : '0',
                    data_type: 'float',
                },
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
                    name: 'Biaya Angkutan',
                    category: 'Transportation Cost',
                    value: transaction_details.value.transportation_cost as any,
                    data_type: 'float',
                },
                {
                    name: 'Cashback',
                    category: 'Cashback',
                    value: transaction_details.value.cashback as any,
                    data_type: 'float',
                },
                {
                    name: 'Biaya Bongkar',
                    category: 'Unloading Cost',
                    value: transaction_details.value.unloading_cost as any,
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
                    name: "NPWP",
                    category: "NPWP",
                    value: transaction_details.value.npwp,
                    data_type: "string",
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
                    form.document_code = (page.props.coNumber as string),
                        form.term_of_payment = '',
                        form.due_date = null,
                        form.description = '',
                        form.sub_total = null as unknown as number,
                        form.total = null as unknown as number,
                        form.tax_amount = null as unknown as number,
                        form.transaction_details = [],
                        form.transaction_items = [],
                        transaction_details.value = {
                            cashback: null as unknown as number,
                            customer: '',
                            customer_address: '',
                            customer_order_date: (page.props.dateNow),
                            legality: '',
                            npwp: '',
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
        }));

        const productOptions = (page.props.products as any[]).map((data) => ({
            label: data.name,
            value: data.name,
            unit: data.unit,
            code: data.code,
            warehouse: data.warehouse,
            last_stock: data.last_stock, // Tambahkan last_stock ke options
            status: data.status,         // Tambahkan status ke options
            id: data.id,
        }));

        return {
            columns: createColumns(),
            handleAddProduct,
            removeProduct,
            handleSubmitCo,
            dayjs,
            form,
            termPaymentOptions,
            transaction_details,
            products,
            transaction_items,
            customerOptions: customerOptionsRef,
            productOptions: productsOptionsRef,
            loading,
            totalPPN,
            subtotal,
            totalPrice,
            stockStatusColor,
            stockMessage,
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