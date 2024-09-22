<template>
    <TitlePage title="Detail PO" />
    <div class="d-flex flex-column gap-4">
        <n-button text style="width: 10px; margin: 20px 0;"
            @click="router.visit(route('procurement.purchase-order-list'))">Kembali</n-button>
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
                            <n-input id="field2" size="large" v-model:value="form.supplier" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field3">Gudang</label>
                            <n-select id="field3" size="large" v-model:value="form.storehouse" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field4">Alokasi</label>
                            <n-select id="field4" size="large" v-model:value="form.located" :disabled="true" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-3">
                            <label for="field5">Tanggal PO</label>
                            <n-input id="field5" size="large" v-model:value="form.purchase_order_date"
                                :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">Tanggal Kirim</label>
                            <n-input id="field6" size="large" v-model:value="form.send_date" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">Term Pembayaran</label>
                            <n-select id="field7" size="large" v-model:value="form.payment_term" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">Tanggal Jatuh Tempo</label>
                            <n-input id="field8" size="large" v-model:value="form.due_date" :disabled="true" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-md-3">
                            <label for="field5">Transportasi</label>
                            <n-input id="field5" size="large" v-model:value="form.transportation" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">Pengirim</label>
                            <n-input id="field6" size="large" v-model:value="form.sender" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">Jenis Pengiriman</label>
                            <n-select id="field7" size="large" v-model:value="form.delivery_type" :disabled="true" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">Karyawan</label>
                            <n-input id="field8" size="large" v-model:value="form.employee_name" :disabled="true" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Produk -->
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="form.products" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>

            <!-- Total Data -->
            <div class="card shadow-sm border-0 mb-3">
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
                                v-model:value="form.notes" :disabled="true" />
                        </div>
                        <div class="flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold">{{ form.payment_term }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold">ON WORKING...</span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-center">
                            <span class="fs-1">SURAT JALAN IS HERE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed, } from 'vue'
import { PurchaseOrder, POProduct } from '../../../types/model';
import { DataTableColumns, NButton } from 'naive-ui';
import Swal from 'sweetalert2';
import TitlePage from '../../../Components/TitlePage.vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';

export default defineComponent({
    setup() {
        const page = usePage();
        const detailPurchaseOrder = (page.props.purchaseOrder as PurchaseOrder);

        const form = useForm<PurchaseOrder>({
            purchase_order_number: detailPurchaseOrder.purchase_order_number,
            supplier: detailPurchaseOrder.supplier,
            storehouse: detailPurchaseOrder.storehouse,
            located: detailPurchaseOrder.located,
            purchase_order_date: detailPurchaseOrder.purchase_order_date,
            send_date: detailPurchaseOrder.send_date,
            payment_term: detailPurchaseOrder.payment_term,
            due_date: detailPurchaseOrder.due_date,
            transportation: detailPurchaseOrder.transportation,
            sender: detailPurchaseOrder.sender,
            delivery_type: detailPurchaseOrder.delivery_type,
            employee_name: detailPurchaseOrder.employee_name,
            notes: detailPurchaseOrder.notes,
            sub_total: 0,
            total_price: 0,
            products: detailPurchaseOrder.products
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

        // Menghitung subtotal dari semua produk tanpa PPN
        const totalPPN = computed(() => {
            // Menghitung subtotal dari semua produk
            const data = form.products.reduce((total, product) => {
                // Mengalikan harga produk dengan jumlahnya dan menjumlahkan ke total
                return form.products.length * (product.product_price ?? 0);
            }, 0); // Inisialisasi total dengan 0

            // Menghitung PPN
            return formatRupiah(data * 0.11); // Menggunakan formatRupiah untuk PPN
        });

        const subtotal = computed(() => {
            const data = form.products.reduce((total, product) => {
                // Mengalikan harga produk dengan jumlahnya dan menjumlahkan ke total
                return form.products.length * (product.product_price ?? 0);
            }, 0); // Inisialisasi total dengan 0
            form.sub_total = data.valueOf();

            return formatRupiah(data);
        });

        const totalPrice = computed(() => {
            const productPrice = form.products.reduce((total, product) => {
                // Mengalikan harga produk dengan jumlahnya dan menjumlahkan ke total
                return form.products.length * (product.product_price ?? 0);
            }, 0); // Inisialisasi total dengan 0

            const afterPpnPrice = productPrice * 0.11;
            form.total_price = afterPpnPrice.valueOf();

            return formatRupiah(productPrice + afterPpnPrice);
        });


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
                        return formatRupiah((row.product_price ?? 0));
                    }
                },
                {
                    title: 'PPN',
                    key: 'ppn',
                    width: 200,
                    render(row) {
                        return formatRupiah((row.ppn ?? 0));
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

            ];
        }

        return {
            form,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            newProduct,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
            router,
        }
    },
    components: {
        TitlePage
    }
});
</script>