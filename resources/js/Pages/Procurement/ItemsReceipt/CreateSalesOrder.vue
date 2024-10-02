<template>
    <Head title="Sales Order" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Sales Order" />
        <div class="d-flex flex-column gap-3">
            <div class="card shadow overflow-hidden" style="border: none">
                <div class="card-body">
                    <div class="row g-2">
                        <!-- Baris Pertama -->
                        <div class="col-lg-2 col-6">
                            <label for="field1">No PO</label>
                            <n-input id="field1" size="large" v-model:value="formSso.purchase_order_number" />
                        </div>
                        <div class="col-lg-2 col-6 d-flex align-items-end">
                            <n-button class="w-100" size="large" strong secondary type="primary"
                                @click="handleGetProducts">Proses</n-button>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field2">No Bukti</label>
                            <n-input id="field2" size="large" v-model:value="formSso.proof_number" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field3">No SO</label>
                            <n-input id="field3" size="large" v-model:value="formSso.sales_order_number" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-lg-4 col-6">
                            <label for="field4">Tanggal PO</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="formSso.order_date" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field5">Alokasi</label>
                            <n-input id="field5" size="large" disabled v-model:value="formSso.located" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field6">Pemasok</label>
                            <n-input id="field6" size="large" disabled v-model:value="formSso.supplier" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field7">Gudang</label>
                            <n-input id="field7" size="large" disabled v-model:value="formSso.storehouse" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field8">Tanggal Kirim</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="formSso.send_date" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field9">Transportasi</label>
                            <n-input id="field9" size="large" v-model:value="formSso.transportation" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-lg-4 col-6">
                            <label for="field10">Pengirim</label>
                            <n-input id="field10" size="large" v-model:value="formSso.sender" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field11">Jenis Pengiriman</label>
                            <n-input id="field11" size="large" v-model:value="formSso.delivery_type" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field12">Karyawan</label>
                            <n-input id="field12" size="large" disabled v-model:value="formSso.employee_name" />
                        </div>
                    </div>

                </div>
            </div>

            <!-- Table here -->
            <div class="card shadow overflow-hidden" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :pagination="pagination" :bordered="false" size="small"
                        pagination-behavior-on-filter="first" :data="formSso.sub_sales_order_products" />
                </div>
            </div>

            <!-- Total data here -->
            <div class="card shadow-sm border-0 overflow-hidden">
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
                        <div class="d-flex flex-column pe-3">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="Basic Textarea" style="width: 30rem;"
                                v-model:value="formSso.note" />
                        </div>
                        <div class="d-flex flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold" v-if="$page.props.purchaseOrder !== undefined">{{
                                    (page.props.purchaseOrder as PurchaseOrder).payment_term.replace("_", " ") }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold" v-if="$page.props.purchaseOrder !== undefined">
                                    {{ dayjs((page.props.purchaseOrder as PurchaseOrder).due_date).format('dddd, D MMMM YYYY ') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <n-button type="primary" class="d-flex flex-column ms-auto w-25 mb-4"
                @click="handleSubmit">SUBMIT</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed } from 'vue'
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import TitlePage from '../../../Components/TitlePage.vue';
import ErrorInput from '../../../Components/ErrorInput.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { Flash, PurchaseOrder, SubSalesOrder, SubSalesOrderProducts, User } from '../../../types/model';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

interface RowData {
    product_code: '',
    product_name: '',
    amount: 0,
    package: '',
    product_price: 0,
    total_price: 0,
    ppn: 0,
}

function createColumns(): DataTableColumns<RowData> {
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
            title: 'KODE PRODUK',
            key: 'product_code',
            width: 200,
            render(row) {
                return row.product_code;
            }
        },
        {
            title: 'NAMA PRODUK',
            key: 'product_name',
            width: 200,
            render(row) {
                return row.product_name;
            }
        },
        {
            title: 'JUMLAH',
            key: 'amount',
            width: 200,
            render(row) {
                return row.amount;
            }
        },
        {
            title: 'SATUAN',
            key: 'package',
            width: 200,
            render(row) {
                return row.package;
            }
        },
        {
            title: 'HARGA',
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
            title: 'TOTAL HARGA',
            key: 'total_price',
            width: 200,
            render(row) {
                // Format total_price as Rupiah for display
                return formatRupiah((row.total_price ?? 0));
            }
        },
    ];
}

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        const formSubSalesOrder = useForm<SubSalesOrder>({
            purchase_order_number: '',
            proof_number: '',
            sales_order_number: '',
            order_date: null as string | null,
            located: '',
            supplier: '',
            storehouse: '',
            send_date: null as string | null,
            transportation: '',
            sender: '',
            delivery_type: '',
            employee_name: (page.props.auth as any).user.fullname,
            sub_total: 0,
            total_after_ppn: 0,
            total_price: 0,
            note: '',
            sub_sales_order_products: [] as SubSalesOrderProducts[]
        });

        const subSalesOrderProducts = ref<SubSalesOrderProducts>({
            product_code: '',
            product_name: '',
            amount: null,
            package: '',
            product_price: null,
            total_price: null,
            ppn: null,
        });

        function handleCreatePO() {
            notification.success({
                title: 'PO created successfully!',
                closable: true,
                keepAliveOnHover: false,
                duration: 2000,
            })
        }

        function handleSubmit() {
            formSubSalesOrder.post(route('procurement.sales-order.post'), {
                onSuccess() {
                    formSubSalesOrder.reset();
                    formSubSalesOrder.sub_sales_order_products.splice(0, formSubSalesOrder.sub_sales_order_products.length);
                    Swal.fire({
                        icon: 'success',
                        title: (page.props.flash as Flash).success
                    });
                },
                onError() {
                    Swal.fire({
                        icon: 'error',
                        title: (page.props.flash as Flash).failed
                    });
                },
            })
        }

        async function handleGetProducts() {
            router.visit(route('procurement.get-po-products', formSubSalesOrder.purchase_order_number), {
                method: 'get',
                preserveState: true, // This preserves the form state
                onSuccess: (page) => {
                    const data = (page.props.purchaseOrder as PurchaseOrder);

                    if (!data) {
                        Swal.fire({
                            icon: 'error',
                            title: (page.props.flash as Flash).failed
                        });
                    }

                    // Assigning other formSubSalesOrder fields
                    formSubSalesOrder.purchase_order_number = data.purchase_order_number;
                    formSubSalesOrder.located = data.located;
                    formSubSalesOrder.supplier = data.supplier;
                    formSubSalesOrder.storehouse = data.storehouse;

                    // Map and push each product into formSubSalesOrder.subSalesOrderProducts
                    const products = data.purchase_order_products.map(product => ({
                        product_code: product.product_code,
                        product_name: product.product_name,
                        amount: product.amount,
                        package: product.package,
                        product_price: product.product_price,
                        total_price: product.total_price,
                        ppn: product.ppn,
                    }));

                    // Ensure subSalesOrderProducts array is empty before pushing new products
                    formSubSalesOrder.sub_sales_order_products = [];

                    // Push each product into the subSalesOrderProducts array
                    products.forEach(product => {
                        formSubSalesOrder.sub_sales_order_products.push(product);
                    });

                    notification.success({
                        title: "Sukses mengambil produk PO",
                        duration: 1500,
                        closable: false,
                    });
                }
            });

        }

        const totalPPN = computed(() => {
            const purchaseOrderData = page.props.purchaseOrder as PurchaseOrder;

            // Cek apakah purchaseOrderData dan purchase_order_products ada
            if (purchaseOrderData && purchaseOrderData.purchase_order_products && purchaseOrderData.purchase_order_products.length > 0) {
                const data = purchaseOrderData.purchase_order_products.reduce((total, product) => {
                    return total + (product.product_price ?? 0);
                }, 0);

                formSubSalesOrder.total_after_ppn = data;

                // Menghitung PPN
                return formatRupiah(data * 0.11); // Menggunakan formatRupiah untuk PPN
            }

            return 0; // Atau return formatRupiah(0) jika Anda ingin menampilkan 0 dalam format rupiah
        });

        const subtotal = computed(() => {
            const purchaseOrderData = page.props.purchaseOrder as PurchaseOrder;

            // Cek apakah purchaseOrderData dan purchase_order_products ada
            if (purchaseOrderData && purchaseOrderData.purchase_order_products && purchaseOrderData.purchase_order_products.length > 0) {
                const data = purchaseOrderData.purchase_order_products.reduce((total, product) => {
                    return total + (product.product_price ?? 0);
                }, 0);

                formSubSalesOrder.sub_total = data;

                return formatRupiah(data);
            }

            return formatRupiah(0); // Atau return formatRupiah(0) jika Anda ingin menampilkan 0 dalam format rupiah
        });

        const totalPrice = computed(() => {
            const purchaseOrderData = page.props.purchaseOrder as PurchaseOrder;

            // Cek apakah purchaseOrderData dan purchase_order_products ada
            if (purchaseOrderData && purchaseOrderData.purchase_order_products && purchaseOrderData.purchase_order_products.length > 0) {
                const productPrice = purchaseOrderData.purchase_order_products.reduce((total, product) => {
                    return total + (product.product_price ?? 0);
                }, 0);

                const afterPpnPrice = productPrice * 0.11;

                formSubSalesOrder.total_price = (afterPpnPrice + productPrice);

                return formatRupiah(productPrice + afterPpnPrice);
            }

            return formatRupiah(0); // Atau return formatRupiah(0) jika Anda ingin menampilkan 0 dalam format rupiah
        });

        return {
            handleSubmit,
            handleCreatePO,
            handleGetProducts,
            dayjs,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            formSso: formSubSalesOrder,
            page,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
        }
    },
    components: {
        TitlePage,
        ErrorInput,
        Head
    }
})
</script>