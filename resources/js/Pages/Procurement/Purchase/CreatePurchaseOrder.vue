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
                        <div class="col-md-3">
                            <label for="field1">
                                No PO<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="field1" size="large" v-model:value="form.document_code"
                                :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field2">
                                Pemasok<span class="text-danger">*</span>
                            </label>
                            <n-select placeholder="" v-model:value="transaction_details.supplier" filterable
                                size="large" :options="pemasokOptions" :loading="loading" clearable remote
                                @search="handleSearchSupplier" />
                        </div>
                        <div class="col-md-3">
                            <label for="field3">
                                Gudang<span class="text-danger">*</span>
                            </label>
                            <n-select placeholder="" id="field3" size="large" :options="storehouseOptions"
                                v-model:value="transaction_details.storehouse" />
                        </div>
                        <div class="col-md-3">
                            <label for="field4">
                                Alokasi<span class="text-danger">*</span>
                            </label>
                            <n-select placeholder="" id="field4" size="large" :options="storeLocationOptions"
                                v-model:value="transaction_details.located" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-3">
                            <label for="field5">
                                Tanggal PO<span class="text-danger">*</span>
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field5"
                                size="large" v-model:formatted-value="transaction_details.purchase_order_date" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">
                                Tanggal Kirim<span class="text-danger">*</span>
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field6"
                                size="large" v-model:formatted-value="transaction_details.send_date" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">
                                Term Pembayaran<span class="text-danger">*</span>
                            </label>
                            <n-select placeholder="" id="field7" size="large" :options="termPaymentOptions"
                                v-model:value="form.term_of_payment" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">
                                Tanggal Jatuh Tempo<span class="text-danger">*</span>
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field8"
                                size="large" v-model:formatted-value="form.due_date" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-md-3">
                            <label for="field5">
                                Transportasi<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="field5" size="large"
                                v-model:value="transaction_details.transportation"
                                @input="(value) => transaction_details.transportation = capitalize(value)" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">
                                Pengirim<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="field6" size="large" v-model:value="transaction_details.sender"
                                @input="(value) => transaction_details.sender = capitalize(value)" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">
                                Jenis Pengiriman<span class="text-danger">*</span>
                            </label>
                            <n-select placeholder="" id="field7" size="large" :options="sendType"
                                v-model:value="transaction_details.delivery_type" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">
                                Karyawan<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="field8" size="large"
                                v-model:value="transaction_details.employee_name" :disabled="true" />
                        </div>
                    </form>

                </div>
            </div>

            <!-- Form Produk -->
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <form @submit.prevent="addProduct" class="row g-3">
                        <!-- Baris Pertama -->
                        <div class="col-md-6">
                            <label for="product_name">
                                Nama Barang<span class="text-danger">*</span>
                            </label>
                            <n-select size="large" placeholder="" v-model:value="products.name" filterable
                                :options="productOptions" :loading="loading" clearable remote @search="handleSearch" />
                        </div>
                        <div class="col-md-6">
                            <label for="amount">
                                Jumlah<span class="text-danger">*</span>
                            </label>
                            <n-input size="large" id="amount" placeholder="" v-model:value="transaction_items.quantity"
                                @input="(value) => transaction_items.quantity = value.replace(/\D/g, '')" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-6">
                            <label for="product_price">
                                Harga Barang<span class="text-danger">*</span>
                            </label>
                            <!-- <n-input size="large" id="product_price" placeholder="" v-model:value="transaction_items.amount"
                                @input="(value) => transaction_items.amount = value.replace(/\D/g, '')"> -->
                            <n-input size="large" id="product_price" placeholder=""
                                v-model:value="transaction_items.amount">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                        <div class="col-md-6">
                            <label for="ppn">
                                PPN<span class="text-danger">*</span>
                            </label>
                            <n-select size="large" id="ppn" :options="ppnOptions" placeholder=""
                                v-model:value="transaction_items.tax_id" />
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
                    <div class="d-flex justify-content-between py-2">
                        <span>PPN 11%</span>
                        <span>{{ resultPpn }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                        <span>Total harga</span>
                        <span>{{ total }}</span>
                    </div>
                    <div class="d-flex py-2">
                        <div class="flex-column pe-3">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="" style="width: 30rem;"
                                v-model:value="form.description" />
                        </div>
                        <div class="flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold">{{ form.term_of_payment.replace("_", " ") }}</span>
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
            term_of_payment: '',
            due_date: null as string | null,
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
            transportation: '',
            sender: '',
            delivery_type: '',
            employee_name: (page.props.auth.user as User).fullname,
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
            product_id: null as unknown as number,
            tax_id: null as unknown as number,
        });

        // Watcher untuk memantau perubahan pada 'products.name'
        watch(() => products.value.name, (newName) => {
            // Cari produk yang cocok berdasarkan nama produk yang dipilih
            const selectedProduct = productOptions.find(product => product.label === newName)

            // Jika produk ditemukan, isi 'products.code' dan 'transaction_items.product_id' secara otomatis
            if (selectedProduct) {
                products.value.code = selectedProduct.code; // Set 'code' produk
                transaction_items.value.product_id = selectedProduct.id ?? 0; // Set 'product_id' dari produk yang dipilih
                transaction_items.value.unit = selectedProduct.unit;
            } else {
                products.value.code = ''; // Reset 'code' jika produk tidak ditemukan
                transaction_items.value.product_id = null as unknown as number; // Reset 'product_id' jika produk tidak ditemukan
                transaction_items.value.unit = "";
            }
        });

        function addProduct() {
            // Validasi input
            if (!products.value.name || !transaction_items.value.quantity || !transaction_items.value.amount || !transaction_items.value.tax_id) {
                notification.error({
                    title: 'Form barang harus diisi',
                    closable: true,
                    keepAliveOnHover: false,
                    duration: 1500,
                });
                return; // Hentikan eksekusi jika ada field yang kosong
            }

            // Ambil persentase PPN dari tax_id yang dipilih
            const selectedTax = ppnOptions.find(tax => tax.value === transaction_items.value.tax_id);

            // Kalkulasi nilai PPN
            const selectedPpnValue = selectedTax ? selectedTax.percentage : 0; // Nilai PPN (0 jika tidak ada PPN)
            const productPrice = transaction_items.value.amount; // Ambil harga produk (amount)
            const roundedProductPrice = Math.round(productPrice);
            const ppnAmount = productPrice * selectedPpnValue; // Kalkulasi PPN
            const totalPrice = roundedProductPrice * transaction_items.value.quantity;

            const roundedTotalPrice = Math.round(totalPrice);


            form.transaction_items.push({
                unit: transaction_items.value.unit,
                quantity: transaction_items.value.quantity,
                product_id: transaction_items.value.product_id,
                tax_amount: selectedTax?.value_tax,
                amount: transaction_items.value.amount,
                tax_id: transaction_items.value.tax_id,
                total_price: roundedTotalPrice,
                product: {
                    code: products.value.code,
                    unit: transaction_items.value.unit,
                    name: products.value.name,
                }
            });

            //clear form 
            products.value.name = "";
            transaction_items.value.quantity = null as unknown as number;
            transaction_items.value.amount = null as unknown as number;
            transaction_items.value.tax_id = null as unknown as number;

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
            const totalWithPPN = subtotal + (subtotal * 0.11);

            // Menyimpan total ke dalam form
            const roundedTotalWithPpn = Math.round(totalWithPPN);

            form.total = roundedTotalWithPpn;

            // Mengembalikan total harga yang diformat
            // return roundedTotalWithPpn;
            return formatRupiah(totalWithPPN);
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
                    title: 'PPN',
                    key: 'tax_amount',
                    width: 100,
                    render(row) {
                        return row.tax_amount;
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
            form.transaction_details = [
                {
                    name: 'Pemasok',
                    category: 'Supplier',
                    value: transaction_details.value.supplier,
                    data_type: 'string',
                },
                {
                    name: 'Gudang',
                    category: 'Storehouse',
                    value: transaction_details.value.storehouse,
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
            ];

            console.log(form.transaction_items);

            form.post(route('procurement.create-po'), {
                onError: (err) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cek kembali form',
                        text: 'Isi form yang terdapat tanda *',
                    });
                },
                onSuccess: () => {
                    // Reset form dengan nilai awal
                    form.document_code = (page.props.po_number as string),
                        form.term_of_payment = '',
                        form.due_date = null,
                        form.description = '',
                        form.sub_total = null as unknown as number,
                        form.total = null as unknown as number,
                        form.tax_amount = null as unknown as number,
                        form.transaction_details = [],
                        form.transaction_items = [],

                        // Kosongkan objek yang menggunakan ref
                        transaction_details.value = {
                            supplier: '',
                            storehouse: '',
                            located: '',
                            purchase_order_date: null,
                            send_date: null,
                            transportation: '',
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
                        title: 'Success submit PO!'
                    });

                },
            });

        }

        //options
        const storeLocationOptions = (page.props.store_locations as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const storehouseOptions = (page.props.storehouses as Storehouse[]).map((data) => ({
            label: data.name,
            value: data.name
        }));

        const termPaymentOptions = (page.props.payment_terms as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const productOptions = (page.props.products as Products[]).map((data) => ({
            id: data.id,
            label: data.name,
            value: data.name,
            code: data.code,
            unit: data.unit,
        }));

        const pemasokOptions = (page.props.suppliers as Parties[]).map((data) => ({
            label: data.name,
            value: data.name,
        }));

        const sendType = [
            { label: "DEPO BEKASI", value: "DEPO BEKASI" },
        ];

        const ppnOptions = (page.props.tax as Tax[]).map((data) => ({
            label: data.name,
            value: data.id,
            value_tax: data.value,
            percentage: data.value / 100 // Tambahkan persentase PPN
        }));

        const units = (page.props.units as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        return {
            form,
            addProduct,
            removeProduct,
            handleSubmit,
            formatRupiah,
            capitalize,
            dayjs,
            products,
            transaction_items,
            units,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            transaction_details,
            storeLocationOptions,
            storehouseOptions,
            termPaymentOptions,
            sendType,
            ppnOptions,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
            loading: loadingRef,
            productOptions: optionsRef,
            pemasokOptions: pemasokOptionsRef,
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
            }
        }
    },
    components: {
        TitlePage,
        Head
    }
});
</script>