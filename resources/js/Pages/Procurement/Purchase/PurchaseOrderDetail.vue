<template>

    <Head title="Purchase Order Detail" />
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Detail PO" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('procurement.purchase-order-list'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="d-flex flex-column gap-3">
            <!-- Form pertama -->
            <div class="card shadow overflow-hidden" style="border: none">
                <div class="card-body">
                    <form class="row g-3">
                        <!-- Baris Pertama -->
                        <div class="col-md-3">
                            <label for="field1">No PO</label>
                            <n-input :disabled="true" id="field1" size="large" v-model:value="form.document_code" />
                        </div>
                        <div class="col-md-3">
                            <label for="field2">Pemasok</label>
                            <n-input :disabled="true" id="field2" size="large"
                                v-model:value="transaction_details.supplier" />
                        </div>
                        <div class="col-md-3">
                            <label for="field3">Gudang</label>
                            <n-select :disabled="true" id="field3" size="large"
                                v-model:value="transaction_details.storehouse" />
                        </div>
                        <div class="col-md-3">
                            <label for="field4">Alokasi</label>
                            <n-select :disabled="true" id="field4" size="large"
                                v-model:value="transaction_details.located" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-md-3">
                            <label for="field5">Tanggal PO</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" :disabled="true"
                                id="field5" size="large"
                                v-model:formatted-value="transaction_details.purchase_order_date" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">Tanggal Kirim</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" :disabled="true"
                                id="field6" size="large" v-model:formatted-value="transaction_details.send_date" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">Term Pembayaran</label>
                            <n-select :disabled="true" id="field7" size="large" v-model:value="form.term_of_payment" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">Tanggal Jatuh Tempo</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" :disabled="true"
                                id="field8" size="large" v-model:formatted-value="form.due_date" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-md-3">
                            <label for="field5">Transportasi</label>
                            <n-input :disabled="true" id="field5" size="large"
                                v-model:value="transaction_details.transportation" />
                        </div>
                        <div class="col-md-3">
                            <label for="field6">Pengirim</label>
                            <n-input :disabled="true" id="field6" size="large"
                                v-model:value="transaction_details.sender" />
                        </div>
                        <div class="col-md-3">
                            <label for="field7">Jenis Pengiriman</label>
                            <n-select :disabled="true" id="field7" size="large"
                                v-model:value="transaction_details.delivery_type" />
                        </div>
                        <div class="col-md-3">
                            <label for="field8">Karyawan</label>
                            <n-input :disabled="true" id="field8" size="large"
                                v-model:value="transaction_details.employee_name" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Produk -->
            <div class="card shadow overflow-hidden" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="form.transaction_items" :pagination="pagination"
                        :bordered="false" size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>

            <!-- Total Data -->
            <div class="card shadow-sm overflow-hidden border-0 ">
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
                    <div class="row g-3">
                        <div class="col-12 col-lg-7 d-flex flex-column ">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="Basic Textarea" style="width: 30rem;"
                                v-model:value="form.description" :disabled="true" />
                        </div>
                        <div class="col-12 col-lg-5 ">
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
            <div class="d-flex">
                <n-button type="primary" size="large" class="mb-3 ms-auto" @click="handleGenerateDocument">
                    Preview PO
                </n-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed, } from 'vue'
import { PurchaseOrder, POProduct, TransactionDetail, TransactionItems, User, Transactions } from '../../../types/model';
import { DataTableColumns, NButton } from 'naive-ui';
import { ArrowBack } from "@vicons/ionicons5";
import TitlePage from '../../../Components/TitlePage.vue';
import { useForm, usePage, router, Head } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';
import Swal from 'sweetalert2';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

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
            width: 200,
            render(row) {
                return row.product?.code;
            }
        },
        {
            title: 'Nama Barang',
            key: 'product_name',
            width: 250,
            render(row) {
                return row.product?.name;
            }
        },
        {
            title: 'Jumlah',
            key: 'quantity',
            width: 100,
        },
        {
            title: 'Kemasan',
            key: 'unit',
            width: 100,
            render(row) {
                return row.unit.replace("_", ' ');
            }
        },
        {
            title: 'Harga Barang',
            key: 'amount',
            width: 100,
            render(row) {
                return formatRupiah(row.amount ?? 0);
            }
        },
        {
            title: 'PPN',
            key: 'tax_amount',
            width: 100,
        },
        // {
        //     title: 'Total harga',
        //     key: 'total_price',
        //     width: 100,
        //     render(row) {
        //         // return formatRupiah((row.total_price ?? 0));
        //     }
        // },

    ];
}

export default defineComponent({
    setup() {
        const page = usePage();
        const detailTransaction = page.props.transaction as Transactions;

        const form = useForm({
            document_code: detailTransaction.document_code || (page.props.po_number as string),
            term_of_payment: detailTransaction.term_of_payment.replace("_", " ") || '',
            due_date: detailTransaction.due_date || null,
            description: detailTransaction.description || '',
            sub_total: detailTransaction.sub_total || 0,
            total: detailTransaction.total || 0,
            tax_amount: detailTransaction.tax_amount || 0,
            transaction_details: detailTransaction.transaction_details || [],  // Menyalin data transaction_details jika ada
            transaction_items: detailTransaction.transaction_items || [],      // Menyalin data transaction_items jika ada
        });

        const supplier = form.transaction_details.find(data => data.category === "Supplier")?.value || '';
        const storehouse = form.transaction_details.find(data => data.category === "Storehouse")?.value || '';
        const located = form.transaction_details.find(data => data.category === "Allocation")?.value || '';
        const purchase_order_date = form.transaction_details.find(data => data.category === "PO Date")?.value || null;
        const send_date = form.transaction_details.find(data => data.category === "Delivery Date")?.value || null;
        const transportation = form.transaction_details.find(data => data.category === "Transportation")?.value || '';
        const sender = form.transaction_details.find(data => data.category === "Sender")?.value || '';
        const delivery_type = form.transaction_details.find(data => data.category === "Delivery Type")?.value || '';

        const transaction_details = ref({
            supplier,
            storehouse,
            located,
            purchase_order_date,
            send_date,
            transportation,
            sender,
            delivery_type,
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
            quantity: null,
            tax_amount: null,
            amount: null,
        });

        // Menghitung subtotal dari semua produk tanpa PPN
        const totalPPN = computed(() => {
            const data = form.transaction_items.reduce((total, item) => {
                return total + (item.amount ?? 0);
            }, 0);
            return formatRupiah(data * 0.11); // Menggunakan formatRupiah untuk PPN
        });

        const subtotal = computed(() => {
            const data = form.transaction_items.reduce((total, item) => {
                return total + (item.amount ?? 0);
            }, 0);
            return formatRupiah(data);
        });

        const totalPrice = computed(() => {
            const productPrice = form.transaction_items.reduce((total, item) => {
                return total + (item.amount ?? 0);
            }, 0);
            const afterPpnPrice = productPrice * 0.11;
            return formatRupiah(productPrice + afterPpnPrice);
        });

        function handleGenerateDocument() {
            Swal.fire({
                icon: "question",
                text: `Preview PO ${detailTransaction.document_code} ?`,
                confirmButtonText: "Generate",
                showCancelButton: true,
                heightAuto: true,
                toast: true,
                position: 'top',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(route('procurement.generate-po-document', detailTransaction.id), '_blank');
                }
            });
        }

        return {
            columns: createColumns(),
            pagination: { pageSize: 10 },
            form,
            transaction_details,
            products,
            transaction_items,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
            router,
            dayjs,
            handleGenerateDocument,
            ArrowBack
        };
    },
    components: {
        TitlePage,
        Head
    }
});
</script>