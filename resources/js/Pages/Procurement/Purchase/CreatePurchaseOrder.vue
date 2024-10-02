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
                            <label for="field1">No PO</label>
                            <n-input id="field1" size="large" v-model:value="form.purchase_order_number"
                                :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field2">Pemasok</label>
                            <n-input id="field2" size="large" v-model:value="form.supplier" />
                        </div>
                        <div class="col-md-3">
                            <label for="field3">Gudang</label>
                            <n-select id="field3" size="large" :options="storehouseOptions"
                                v-model:value="form.storehouse" />
                        </div>
                        <div class="col-md-3">
                            <label for="field4">Alokasi</label>
                            <n-select id="field4" size="large" :options="storeLocationOptions"
                                v-model:value="form.located" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-3">
                            <label for="field5">Tanggal PO</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field5" size="large"
                                v-model:formatted-value="form.purchase_order_date" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">Tanggal Kirim</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field6" size="large"
                                v-model:formatted-value="form.send_date" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">Term Pembayaran</label>
                            <n-select id="field7" size="large" :options="termPaymentOptions"
                                v-model:value="form.payment_term" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">Tanggal Jatuh Tempo</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="form.due_date" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-md-3">
                            <label for="field5">Transportasi</label>
                            <n-input id="field5" size="large" v-model:value="form.transportation" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">Pengirim</label>
                            <n-input id="field6" size="large" v-model:value="form.sender" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">Jenis Pengiriman</label>
                            <n-select id="field7" size="large" :options="sendType" v-model:value="form.delivery_type" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">Karyawan</label>
                            <n-input id="field8" size="large" v-model:value="form.employee_name" :disabled="true" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Produk -->
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <form @submit.prevent="addProduct" class="row g-3">
                        <!-- Baris Pertama -->
                        <div class="col-md-4">
                            <label for="product_code">Kode Barang</label>
                            <n-input id="product_code" v-model:value="newProduct.product_code"
                                placeholder="Kode Barang" />
                        </div>
                        <div class="col-md-4">
                            <label for="product_name">Nama Barang</label>
                            <n-input id="product_name" v-model:value="newProduct.product_name"
                                placeholder="Nama Barang" />
                        </div>
                        <div class="col-md-4">
                            <label for="amount">Jumlah</label>
                            <n-input id="amount" v-model:value="newProduct.amount" placeholder="Jumlah" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-4">
                            <label for="package">Kemasan</label>
                            <n-input id="package" v-model:value="newProduct.package" placeholder="Kemasan" />
                        </div>
                        <div class="col-md-4">
                            <label for="product_price">Harga Barang</label>
                            <n-input id="product_price" v-model:value="newProduct.product_price"
                                placeholder="Harga Barang" />
                        </div>
                        <div class="col-md-4">
                            <label for="ppn">PPN</label>
                            <n-select id="ppn" v-model:value="newProduct.ppn" :options="ppnOptions"
                                placeholder="Pilih PPN" />
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
                    <n-data-table :columns="columns" :data="form.purchase_order_products" :pagination="pagination"
                        :bordered="false" size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>

            <!-- Total Data -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2">
                        <span>Sub Total</span>
                        <span>{{ subTotal.valueOf() }}</span>
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
                            <n-input id="catatan" type="textarea" placeholder="Basic Textarea" style="width: 30rem;"
                                v-model:value="form.notes" />
                        </div>
                        <div class="flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold">{{ form.payment_term }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold">{{ form.due_date }}</span>
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
import { defineComponent, h, ref, computed, } from 'vue'
import { Lookup, POProduct, PurchaseOrder, Storehouse } from '../../../types/model';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import TitlePage from '../../../Components/TitlePage.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        const form = useForm<PurchaseOrder>({
            purchase_order_number: (page.props.po_number as string),
            supplier: '',
            storehouse: '',
            located: '',
            purchase_order_date: null as string | null,
            send_date: null as string | null,
            payment_term: '',
            due_date: null as string | null,
            transportation: '',
            sender: '',
            delivery_type: '',
            employee_name: (page.props.auth as any).user.fullname,
            notes: '',
            sub_total: 0,
            total_price: 0,
            total_ppn: 0,
            purchase_order_products: [] as POProduct[]
        });

        const newProduct = ref<POProduct>({
            product_code: '',
            product_name: '',
            amount: null,
            package: '',
            product_price: null,
            total_price: null,
            ppn: null,
        });

        function addProduct() {
            const productPrice = Number(newProduct.value.product_price) || 0;
            const amount = newProduct.value.amount ?? 0;
            const selectedPpnValue = newProduct.value.ppn ?? 0; // Nilai PPN dalam format desimal seperti 0.11 (11%)

            // Hitung PPN berdasarkan opsi yang dipilih
            const ppnAmount = (productPrice * selectedPpnValue);

            // Hitung total harga termasuk PPN
            const totalPrice = productPrice + ppnAmount;

            // Tambahkan produk ke dalam array
            form.purchase_order_products.push({
                product_code: newProduct.value.product_code ?? '',
                product_name: newProduct.value.product_name ?? '',
                amount: amount,
                package: newProduct.value.package ?? '',
                product_price: productPrice,
                total_price: totalPrice,
                ppn: ppnAmount,
            });

            // Reset produk baru setelah ditambahkan
            newProduct.value = {
                product_code: '',
                product_name: '',
                amount: null,
                package: '',
                product_price: null,
                total_price: null,
                ppn: null,
            }

            notification.success({
                title: 'Berhasil',
                meta: 'Produk berhasil ditambahkan',
                closable: true,
                keepAliveOnHover: false,
                duration: 2000,
            });
        }

        // Menghitung subtotal dari semua produk tanpa PPN
        const totalPPN = computed(() => {
            // Menghitung subtotal dari semua produk
            const data = form.purchase_order_products.reduce((total, product) => {
                // Mengalikan harga produk dengan jumlahnya dan menjumlahkan ke total
                return form.purchase_order_products.length * (product.product_price ?? 0);
            }, 0); // Inisialisasi total dengan 0

            form.total_ppn = data * 0.11;

            // Menghitung PPN
            return formatRupiah(data * 0.11); // Menggunakan formatRupiah untuk PPN
        });

        const subtotal = computed(() => {
            const data = form.purchase_order_products.reduce((total, product) => {
                // Mengalikan harga produk dengan jumlahnya dan menjumlahkan ke total
                return form.purchase_order_products.length * (product.product_price ?? 0);
            }, 0); // Inisialisasi total dengan 0
            form.sub_total = data.valueOf();

            return formatRupiah(data);
        });

        const totalPrice = computed(() => {
            const productPrice = form.purchase_order_products.reduce((total, product) => {
                // Mengalikan harga produk dengan jumlahnya dan menjumlahkan ke total
                return form.purchase_order_products.length * (product.product_price ?? 0);
            }, 0); // Inisialisasi total dengan 0

            const afterPpnPrice = productPrice * 0.11;
            const total = productPrice + afterPpnPrice;
            form.total_price = total;

            return formatRupiah(productPrice + afterPpnPrice);
        });

        // Fungsi untuk menghapus produk dari array
        function removeProduct(index: number) {
            form.purchase_order_products.splice(index, 1);
        }

        function createColumns(): DataTableColumns<POProduct> {
            return [
                {
                    title: 'No',
                    key: 'no',
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: 'Kode produk',
                    key: 'product_code',
                    width: 200,
                },
                {
                    title: 'Nama produk',
                    key: 'product_name',
                    width: 200,
                },
                {
                    title: 'Jumlah',
                    key: 'amount',
                    width: 200,
                },
                {
                    title: 'Satuan',
                    key: 'package',
                    width: 200,
                },
                {
                    title: 'Harga',
                    key: 'product_price',
                    width: 200,
                    render(row) {
                        // Format product_price as Rupiah for display
                        return formatRupiah((row.product_price ?? 0));
                    }
                },
                {
                    title: 'PPN',
                    key: 'ppn',
                    width: 200,
                    render(row) {
                        // Format ppn as Rupiah for display
                        return formatRupiah((row.ppn ?? 0));
                    }
                },
                {
                    title: 'Total harga',
                    key: 'total_price',
                    width: 200,
                    render(row) {
                        // Format total_price as Rupiah for display
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
                                            text: `Delete ${row.product_name}?`,
                                            showCancelButton: true,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                removeProduct(index);

                                                notification.success({
                                                    title: `${row.product_name} has been deleted!`,
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

        function handleCreatePO() {
            notification.success({
                title: 'PO created successfully!',
                closable: true,
                keepAliveOnHover: false,
                duration: 2000,
            })
        }

        function handleSubmit() {
            form.post(route('procurement.create-po'), {
                onError: (err) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message,
                    });
                },
                onSuccess: () => {
                    form.reset();
                    form.purchase_order_products.splice(0, form.purchase_order_products.length);
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

        const sendType = [
            { label: "DEPO", value: "DEPO" },
        ];

        const ppnOptions = [
            { label: 'PPN', value: 0.11 },
            { label: 'NON PPN', value: 0.0 },
        ];

        return {
            form,
            addProduct,
            removeProduct,
            handleSubmit,
            handleCreatePO,
            formatRupiah,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            newProduct,
            storeLocationOptions,
            storehouseOptions,
            termPaymentOptions,
            sendType,
            ppnOptions,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
        }
    },
    components: {
        TitlePage,
        Head
    }
});
</script>