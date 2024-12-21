<template>

    <Head title="Purchase Order" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Purchase Order" />
        <div class="d-flex flex-column gap-3">
            <!-- Form pertama -->
            <div class="card shadow" style="border: none">
                <div class="card-body">
                    <form class="row g-3">
                        <!-- Baris Pertama -->
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field1">
                                No PO
                                <RequiredMark />
                            </label>
                            <n-input placeholder="" id="field1" size="large" v-model:value="form.document_code"
                                disabled />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field2">
                                Principal
                                <RequiredMark />
                            </label>
                            <n-select placeholder="" v-model:value="transaction_details.supplier" filterable
                                size="large" :options="pemasokOptions" />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field7">
                                Jenis Pengiriman
                                <RequiredMark />
                            </label>
                            <n-select placeholder="" id="field7" size="large" :options="sendType"
                                v-model:value="transaction_details.delivery_type" />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field4">
                                Perusahaan
                                <RequiredMark />
                            </label>
                            <n-select placeholder="" id="field4" size="large" :options="storeLocationOptions"
                                v-model:value="transaction_details.located" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field5">
                                Tanggal PO
                                <RequiredMark />
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field5"
                                size="large" v-model:formatted-value="transaction_details.purchase_order_date" />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field6">
                                Tanggal Kirim
                                <RequiredMark />
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field6"
                                size="large" v-model:formatted-value="transaction_details.send_date" />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field7">
                                Term Pembayaran
                                <RequiredMark />
                            </label>
                            <n-input placeholder="" id="field7" size="large" v-model:value="form.term_of_payment"
                                @input="(value) => form.term_of_payment = value.replace(/\D/g, '')">
                                <template #suffix>
                                    HARI
                                </template>
                            </n-input>
                        </div>
                        <!-- CHANGE -->
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field8">
                                Tanggal Jatuh Tempo
                                <RequiredMark />
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field8"
                                size="large" v-model:formatted-value="form.due_date" />
                        </div>

                        <!-- Baris Ketiga -->
                        <!-- CHANGE -->
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field5">
                                Nomor Polisi Ekspedisi
                            </label>
                            <n-input placeholder="" id="field5" size="large"
                                v-model:value="transaction_details.transportation"
                                @input="(value) => transaction_details.transportation = capitalize(value)" />
                        </div>
                        <!-- CHANGE -->
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field6">
                                Nama Ekspedisi
                                <RequiredMark />
                            </label>
                            <n-select placeholder="" filterable id="field6" size="large"
                                v-model:value="transaction_details.sender" :options="transportOptions" />
                        </div>

                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="field8">
                                PIC
                                <RequiredMark />
                            </label>
                            <n-input placeholder="" id="field8" size="large"
                                v-model:value="transaction_details.employee_name" disabled />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="catatan">Harga angkutan</label>
                            <n-input id="catatan" placeholder="" size="large"
                                v-model:value="transaction_details.transportation_cost">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>
                        <div class="col-12 col-md-3 col-md-4">
                            <label for="ppn">
                                PPN
                            </label>
                            <n-select size="large" id="ppn" :options="ppnOptions" placeholder=""
                                v-model:value="transaction_details.use_tax" />
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="" v-model:value="form.description" />
                        </div>
                    </form>

                </div>
            </div>

            <!-- Form Produk -->
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <span class="fs-4 fw-semibold ">Penginputan Barang</span>
                    <form @submit.prevent="addProduct" class="row g-3 mt-2">
                        <!-- Baris Pertama -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="product_name">
                                Nama Barang
                                <RequiredMark />
                            </label>
                            <n-select size="large" placeholder="" v-model:value="products.name" filterable
                                :options="productOptions" :loading="loading" />
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="grosir_account">Akun Grosir</label>
                            <n-select size="large" placeholder="" filterable clearable :options="tradePromoOptions"
                                v-model:value="transaction_items.trade_promo_id" />
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="amount">
                                Jumlah
                                <RequiredMark />
                            </label>
                            <n-input size="large" id="amount" placeholder="" v-model:value="transaction_items.quantity"
                                @input="(value) => transaction_items.quantity = value.replace(/\D/g, '')" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-6">
                            <label for="product_price">
                                Harga Barang
                            </label>
                            <n-input size="large" id="product_price" placeholder=""
                                v-model:value="transaction_items.amount" >
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>

                        <div class="col-md-6" v-if="transaction_items.trade_promo_id !== null">
                            <label for="ppn">
                                Harga Setelah Diskon
                            </label>
                            <n-input size="large" id="product_price" placeholder=""
                                v-model:value="transaction_items.amount_discount" disabled>
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                        <div class="d-flex justify-content-end">
                            <n-button color="green" attr-type="submit">Tambah Produk</n-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Produk -->
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <!--  -->
                    <n-data-table :columns="columns" :data="form.transaction_items" :pagination="pagination"
                        :bordered="false" size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>

            <!-- Total Data -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2">
                        <span>Sub Total</span>
                        <span>{{ subTotal }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" v-if="transaction_details.use_tax === true">
                        <span>PPN 11%</span>
                        <span>{{ resultPpn }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                        <span>Total harga</span>
                        <span>{{ total }}</span>
                    </div>
                    <div class="d-flex py-2">
                        <div class="d-flex flex-column w-100 justify-content-between gap-3">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold">{{ form.term_of_payment }} HARI</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold">
                                    {{ form.due_date ? dayjs(form.due_date).format('dddd, D MMMM YYYY') : '' }}
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <n-button type="primary" class="d-flex ms-auto w-md-25 mb-4" @click="handleSubmit">SUBMIT PO</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed, watch } from 'vue'
import { Lookup, Parties, POProduct, Products, PurchaseOrder, Storehouse, Tax, TransactionDetail, TransactionItems, Transactions, User } from '../../../types/model';
import { DataTableColumns, NButton, SelectOption, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import RequiredMark from '../../../Components/RequiredMark.vue';
import TitlePage from '../../../Components/TitlePage.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { formatRupiah, capitalize } from '../../../Utils/options-input.utils';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();
        const loadingRef = ref(false);
        const optionsRef = ref<SelectOption[]>([]);
        const pemasokOptionsRef = ref<SelectOption[]>([]);

        const form = useForm({
            document_code: (page.props.po_number as string),
            term_of_payment: 45,
            due_date: null as unknown as string,
            description: '',
            sub_total: null as unknown as number,
            total: null as unknown as number,
            tax_amount: null as unknown as number,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_details = ref({
            supplier: '',
            storehouse: '',
            located: '',
            purchase_order_date: null as string | null,
            send_date: null as string | null,
            transportation: '-',
            sender: '',
            delivery_type: '',
            employee_name: (page.props.auth.user as User).fullname,
            transportation_cost: null as unknown as string,
            use_tax: false,
        });

        const products = ref({
            code: '',
            unit: '',
            name: '',
            transaction_items: [] as TransactionItems[],
        });

        const transaction_items = ref({
            unit: '',
            quantity: null as unknown as number,
            tax_amount: 0,
            amount: null as unknown as number,
            amount_discount: null as unknown as number,
            product_id: null as unknown as number,
            tax_id: null as unknown as number,
            tax_value: null as unknown as number,
            trade_promo_id: null as unknown as number,
        });

        const tradePromoOptions = (page.props.trade_promos as any[]).map((data) => ({
            label: data.grosir_account,
            value: data.id,
            id: data.id,
            price: data.discount_price,
            quota: data.quota
        }));
        const quotaTradePromo = ref(null as unknown as number);

        // Watcher untuk akun grosir
        watch(() => transaction_items.value.trade_promo_id, (id) => {
            const selectedTradePromo = tradePromoOptions.find(data => data.id === id);

            if (selectedTradePromo) {
                quotaTradePromo.value = selectedTradePromo.quota;
                transaction_items.value.amount_discount = selectedTradePromo.price;
            } else {
                transaction_items.value.amount_discount = null as unknown as number;
            }
        }, { deep: true, immediate: true });

        watch(() => transaction_items.value.quantity, (quantity) => {
            if(quotaTradePromo.value !== null && quotaTradePromo.value < quantity){
                Swal.fire({
                    icon: "error",
                    title: "Kuota tidak cukup!",
                    text: `Kuota hanya tersedia ${quotaTradePromo.value}`
                }).then(() => {
                    transaction_items.value.quantity = null as unknown as number;
                });
                return;
            }
        })

        // Watcher untuk memantau perubahan pada 'products.name'
        watch(() => products.value.name, (newName) => {
            // Cari produk yang cocok berdasarkan nama produk yang dipilih
            const selectedProduct = productOptions.find(product => product.label === newName);

            // Jika produk ditemukan, isi 'products.code' dan 'transaction_items.product_id' secara otomatis
            if (selectedProduct) {
                products.value.code = selectedProduct.code; // Set 'code' produk
                transaction_items.value.product_id = selectedProduct.id ?? 0; // Set 'product_id' dari produk yang dipilih
                transaction_items.value.unit = selectedProduct.unit;
                transaction_items.value.amount = selectedProduct.redemp_price;
            } else {
                products.value.code = ''; // Reset 'code' jika produk tidak ditemukan
                transaction_items.value.product_id = null as unknown as number; // Reset 'product_id' jika produk tidak ditemukan
                transaction_items.value.unit = "";
            }
        });

        // Watcher untuk memantau perubahan pada purchase_order_date
        watch(
            [() => transaction_details.value.purchase_order_date, () => form.term_of_payment],
            ([newPurchaseOrderDate, newTermOfPayment]) => {
                if (newPurchaseOrderDate) {
                    // Jika purchase_order_date sudah ada, set due_date berdasarkan term_of_payment
                    const termDays = newTermOfPayment; // Gunakan term_of_payment atau default 45
                    form.due_date = handleSetFutureDateTo(termDays, newPurchaseOrderDate);
                }
            }
        );

        function addProduct() {
            // Validasi input
            if (!products.value.name || !transaction_items.value.quantity || !transaction_items.value.amount) {
                notification.error({
                    title: 'Form barang harus diisi',
                    closable: true,
                    keepAliveOnHover: false,
                    duration: 1500,
                });
                return;
            }

            // Ambil persentase PPN dari tax_id yang dipilih
            const selectedTax = ppnOptions.find(tax => tax.value === transaction_items.value.tax_id);
            const selectedPpnValue = selectedTax ? selectedTax.percentage : 0;

            // Periksa apakah ada diskon
            const useDiscount = transaction_items.value.amount_discount !== null && transaction_items.value.amount_discount !== undefined;

            // Format nilai `amount` untuk menangani separator ribuan/desimal
            const formattedAmount = useDiscount
                ? String(transaction_items.value.amount_discount) // Gunakan amount_discount jika tersedia
                : String(transaction_items.value.amount).replace(/\./g, '').replace(',', '.'); // Atau gunakan input amount

            // Parsing amount yang sudah diformat ke angka desimal
            const productPrice = parseFloat(formattedAmount);
            if (isNaN(productPrice)) {
                notification.error({
                    title: 'Nilai jumlah produk tidak valid',
                    closable: true,
                    duration: 1500,
                });
                return;
            }

            // Perhitungan tanpa pembulatan
            const ppnAmount = productPrice * selectedPpnValue;
            const totalPrice = productPrice * transaction_items.value.quantity;

            // Format total harga agar memiliki dua desimal
            const formattedTotalPrice = parseFloat(totalPrice.toFixed(2));

            form.transaction_items.push({
                unit: transaction_items.value.unit,
                quantity: transaction_items.value.quantity,
                product_id: transaction_items.value.product_id,
                // tax_amount: selectedTax?.value_tax,
                amount: productPrice,
                tax_id: transaction_items.value.tax_id,
                // tax_value: selectedTax?.value_tax ?? 0,
                use_tax: transaction_details.value.use_tax,
                trade_promo_id: transaction_items.value.trade_promo_id,
                total_price: formattedTotalPrice,
                product: {
                    code: products.value.code,
                    unit: transaction_items.value.unit,
                    name: products.value.name,
                }
            });

            // Clear form 
            products.value.name = "";
            transaction_items.value.quantity = null as unknown as number;
            transaction_items.value.amount = null as unknown as number;
            transaction_items.value.tax_id = null as unknown as number;
            transaction_items.value.trade_promo_id = null as unknown as number;

            notification.success({
                title: 'Berhasil',
                meta: 'Produk berhasil ditambahkan',
                closable: true,
                keepAliveOnHover: false,
                duration: 2000,
            });
        }



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
            // subtotal + (subtotal * 0.11)
            let grandTotal = null as unknown as number;

            if (transaction_details.value.use_tax) {
                grandTotal = subtotal + (subtotal * 0.11);
            } else {
                grandTotal = subtotal;
            }

            // Menyimpan total ke dalam form
            const roundedTotalWithPpn = Math.round(grandTotal);

            form.total = roundedTotalWithPpn;

            // Mengembalikan total harga yang diformat
            // return roundedTotalWithPpn;
            return formatRupiah(grandTotal);
        });

        // Fungsi untuk menghapus produk dari array
        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        function createColumns(): DataTableColumns<TransactionItems> {
            return [
                {
                    title: '#',
                    key: 'index',
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: 'Kode Barang',
                    key: 'product_code',
                    width: 150,
                    render(row) {
                        return row.product?.code;
                    }
                },
                {
                    title: 'Nama Barang',
                    key: 'product_name',
                    width: 200,
                    render(row) {
                        return row.product?.name;
                    }
                },
                {
                    title: 'Jumlah',
                    key: 'quantity',
                    width: 150
                },
                {
                    title: 'Kemasan',
                    key: 'unit',
                    width: 150
                },
                {
                    title: 'Harga Barang',
                    key: 'amount',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.amount ?? 0);
                    }
                },
                {
                    title: 'Total harga',
                    key: 'total_price',
                    width: 200,
                    render(row) {
                        return formatRupiah((row.total_price ?? 0));
                    }
                },
                {
                    title: 'Action',
                    key: 'actions',
                    width: 100,
                    render(row, index) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
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
                                { default: () => 'Hapus' }
                            )
                        ]);
                    }
                }
            ];
        }

        function handleSubmit() {
            console.log(form.transaction_details);
            form.transaction_details = [
                {
                    name: 'Pemasok',
                    category: 'Supplier',
                    value: transaction_details.value.supplier,
                    data_type: 'string',
                },
                {
                    name: 'Alokasi',
                    category: 'Allocation',
                    value: transaction_details.value.located,
                    data_type: 'string',
                },
                {
                    name: 'Tanggal PO',
                    category: 'PO Date',
                    value: transaction_details.value.purchase_order_date as any,
                    data_type: 'datetime',
                },
                {
                    name: 'Tanggal Kirim',
                    category: 'Delivery Date',
                    value: transaction_details.value.send_date as any,
                    data_type: 'datetime',
                },
                {
                    name: 'Transportasi',
                    category: 'Transportation',
                    value: transaction_details.value.transportation,
                    data_type: 'string',
                },
                {
                    name: 'Pengirim',
                    category: 'Sender',
                    value: transaction_details.value.sender,
                    data_type: 'string',
                },
                {
                    name: 'Jenis Pengiriman',
                    category: 'Delivery Type',
                    value: transaction_details.value.delivery_type,
                    data_type: 'string',
                },
                {
                    name: 'Karyawan',
                    category: 'Employee',
                    value: transaction_details.value.employee_name,
                    data_type: 'string',
                },
                {
                    name: "Harga Angkutan",
                    category: "Transportation Cost",
                    value: transaction_details.value.transportation_cost || "0",
                    data_type: "float",
                },
                {
                    name: "PPN",
                    category: "Use Tax",
                    value: String(transaction_details.value.use_tax),
                    data_type: 'boolean'
                }
            ];

            form.post(route('procurement.create-po'), {
                onError: (error) => {
                    if (error !== null) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cek kembali form',
                            text: 'Isi form yang terdapat tanda *',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: page.props.flash.failed,
                        })
                    }
                },
                onSuccess: (page) => {
                    // Reset form dengan nilai awal
                    form.document_code = (page.props.po_number as string);
                    form.due_date = null as unknown as string;
                    form.description = '';
                    form.sub_total = null as unknown as number;
                    form.total = null as unknown as number;
                    form.tax_amount = null as unknown as number;
                    form.transaction_details = [];
                    form.transaction_items = [];

                    // Kosongkan objek yang menggunakan ref
                    transaction_details.value = {
                        supplier: '',
                        storehouse: '',
                        located: '',
                        purchase_order_date: null,
                        send_date: null,
                        transportation: '-',
                        sender: '',
                        delivery_type: '',
                        employee_name: (page.props.auth.user as User).fullname,
                    };

                    products.value = {
                        code: '',
                        unit: '',
                        name: '',
                        transaction_items: [],
                    };

                    transaction_items.value = {
                        unit: '',
                        quantity: null as unknown as number,
                        tax_amount: null as unknown as number,
                        amount: null as unknown as number,
                        product_id: null as unknown as number,
                        tax_id: null as unknown as number,
                    };
                    form.transaction_items.splice(0, form.transaction_items.length);

                    Swal.fire({
                        icon: 'success',
                        title: page.props.flash.success
                    });

                },
            });

        }

        function handleSetFutureDateTo(days: number, start_date: any) {
            const baseDate = new Date(start_date);
            baseDate.setDate(baseDate.getDate() + days);

            return baseDate.toISOString().slice(0, 19).replace('T', ' ');
        }

        //options
        const storeLocationOptions = [
            {label: "DNP", value: "DNP"},
            {label: "DKU", value: "DKU"}
        ];

        const storehouseOptions = (page.props.storehouses as Storehouse[]).map((data) => ({
            label: data.name,
            value: data.name
        }));

        const termPaymentOptions = (page.props.payment_terms as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const productOptions = (page.props.products as any[]).map((data) => ({
            id: data.id,
            label: data.name,
            value: data.name,
            code: data.code,
            unit: data.unit,
            redemp_price: data.redemp_price,
            retail_price: data.retail_price,
            restaurant_price: data.restaurant_price,
            all_segment_price: data.all_segment_price,
        }));

        const pemasokOptions = (page.props.suppliers as Parties[]).map((data) => ({
            label: data.name,
            value: data.name,
        }));

        const transportOptions = (page.props.transports as Parties[]).map((data) => ({
            label: data.name,
            value: data.name
        }));



        const sendType = [
            { label: "DEPO BEKASI", value: "DEPO BEKASI" },
            { label: "DIRECT", value: "DIRECT" },
            { label: "DIRECT DEPO", value: "DIRECT_DEPO" },
            { label: "BELI DO", value: "DO" },
        ];

        const ppnOptions = [
            { label: "PPN", value: true },
            { label: "NON-PPN", value: false },
        ]

        const units = (page.props.units as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        return {
            addProduct,
            removeProduct,
            handleSubmit,
            formatRupiah,
            capitalize,
            handleSetFutureDateTo,
            columns: createColumns(),
            form,
            dayjs,
            products,
            transaction_items,
            units,
            pagination: { pageSize: 10 },
            transaction_details,
            storeLocationOptions,
            storehouseOptions,
            termPaymentOptions,
            sendType,
            transportOptions,
            ppnOptions,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
            loading: loadingRef,
            productOptions,
            pemasokOptions,
            tradePromoOptions,
            handleSearch: (query: string) => {
                if (!query.length) {
                    optionsRef.value = []
                    return
                }
                loadingRef.value = true
                window.setTimeout(() => {
                    optionsRef.value = productOptions.filter(
                        item => ~item.label.indexOf(query)
                    )
                    loadingRef.value = false
                }, 1000)
            },
            handleSearchSupplier: (query: string) => {
                if (!query.length) {
                    pemasokOptionsRef.value = []
                    return
                }
                loadingRef.value = true
                window.setTimeout(() => {
                    pemasokOptionsRef.value = pemasokOptions.filter(
                        item => ~item.label.indexOf(query)
                    )
                    loadingRef.value = false
                }, 1000)
            },

        }
    },
    components: {
        TitlePage,
        Head,
        RequiredMark
    }
});
</script>