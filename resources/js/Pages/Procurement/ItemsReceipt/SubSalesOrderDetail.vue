<template>

    <Head title="SSO Detail" />
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Detail SSO" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('procurement.sales-order-list'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="d-flex flex-column gap-3">
            <div class="card shadow overflow-hidden" style="border: none">
                <div class="card-body">
                    <div class="row g-2">
                        <!-- Baris Pertama -->
                        <div class="col-lg-3 col-6">
                            <label for="field3">No SO</label>
                            <n-input id="field3" size="large" v-model:value="form.document_code" disabled />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field2">No Bukti</label>
                            <n-input id="field2" size="large" v-model:value="transaction_details.proof_number"
                                disabled />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field1">No PO</label>
                            <n-input id="field1" size="large" v-model:value="transaction_details.po_number" disabled />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field4">Tanggal PO</label>
                            <n-input size="large" v-model:value="transaction_details.purchase_order_date" disabled />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-lg-3 col-6">
                            <label for="field5">Alokasi</label>
                            <n-input id="field5" size="large" disabled v-model:value="transaction_details.located" />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field6">Pemasok</label>
                            <n-input id="field6" size="large" disabled v-model:value="transaction_details.supplier" />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field8">Tanggal Kirim</label>
                            <n-input size="large" v-model:value="transaction_details.send_date" disabled />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field9">Transportasi</label>
                            <n-input id="field9" size="large" v-model:value="transaction_details.transportation"
                                disabled />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-lg-3 col-6">
                            <label for="field10">Pengirim</label>
                            <n-input id="field10" size="large" v-model:value="transaction_details.sender" disabled />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field11">Jenis Pengiriman</label>
                            <n-input id="field11" size="large" v-model:value="transaction_details.delivery_type"
                                disabled />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label for="field12">PIC</label>
                            <n-input id="field12" size="large" disabled
                                v-model:value="transaction_details.employee_name" />
                        </div>
                    </div>

                </div>
            </div>

            <!-- Table here -->
            <div class="card shadow overflow-hidden" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :pagination="pagination" :bordered="false" size="small"
                        pagination-behavior-on-filter="first" :data="form.transaction_items" />
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
                            <n-input id="catatan" type="textarea" placeholder="" style="width: 30rem;"
                                v-model:value="form.description" disabled />
                        </div>
                        <div class="d-flex flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold" v-if="form !== undefined">
                                    {{ form.term_of_payment + " HARI" }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold" v-if="form !== undefined">
                                    {{ dayjs(form.due_date).format('dddd, D MMMM YYYY ') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <n-button type="primary" class="d-flex flex-column ms-auto mb-4" @click="handleGenerateDocument">Preview
                SSO</n-button>
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
import { TransactionItems, Transactions, User } from '../../../types/model';
import { ArrowBack } from '@vicons/ionicons5';
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
                return row.unit?.replace("_", ' ');
            }
        },
        {
            title: 'Harga Barang',
            key: 'amount',
            width: 200,
            render(row) {
                const excludeAmount = Math.round(row.amount / 1.11);
                return formatRupiah(excludeAmount ?? 0);
            }
        },
        {
            title: 'Total harga',
            key: 'total_price',
            width: 200,
            render(row) {
                const excludeAmount = Math.round(row.amount / 1.11);
                const totalPrice = excludeAmount * row.quantity;
                return formatRupiah((totalPrice ?? 0));
            }
        },
    ];
}

export default defineComponent({
    setup() {
        const page = usePage();
        const detailTransaction = page.props.transaction as Transactions;

        const form = useForm({
            document_code: detailTransaction.document_code || (page.props.po_number as string),
            term_of_payment: detailTransaction.term_of_payment || '',
            due_date: detailTransaction.due_date || null,
            description: detailTransaction.description || '',
            sub_total: detailTransaction.sub_total || 0,
            total: detailTransaction.total || 0,
            tax_amount: detailTransaction.tax_amount || 0,
            transaction_details: detailTransaction.transaction_details || [],  // Menyalin data transaction_details jika ada
            transaction_items: detailTransaction.transaction_items || [],      // Menyalin data transaction_items jika ada
        });

        const po_number = form.transaction_details.find(data => data.category === "PO Number")?.value || '';
        const proof_number = form.transaction_details.find(data => data.category === "Proof Number")?.value || '';
        const supplier = form.transaction_details.find(data => data.category === "Supplier")?.value || '';
        const storehouse = form.transaction_details.find(data => data.category === "Storehouse")?.value?.replace("_", ' ') || '';
        const located = form.transaction_details.find(data => data.category === "Allocation")?.value || '';
        const purchase_order_date = dayjs(form.transaction_details.find(data => data.category === "PO Date")?.value || null).format('dddd, D MMMM YYYY HH:mm');
        const send_date = dayjs(form.transaction_details.find(data => data.category === "Delivery Date")?.value || null).format('dddd, D MMMM YYYY HH:mm');
        const transportation = form.transaction_details.find(data => data.category === "Transportation")?.value || '';
        const sender = form.transaction_details.find(data => data.category === "Sender")?.value || '';
        const delivery_type = form.transaction_details.find(data => data.category === "Delivery Type")?.value || '';

        const transaction_details = ref({
            supplier,
            storehouse,
            po_number,
            proof_number,
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
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotalExcludingTax = form.transaction_items.reduce((total, item) => {
                const priceExcludingTax = (Number(item.total_price ?? 0)) / 1.11;
                return total + priceExcludingTax;
            }, 0);

            // Menghitung PPN 11% dari subtotal sebelum PPN
            const ppn = subtotalExcludingTax * 0.11;

            // Membulatkan PPN sesuai aturan yang kamu inginkan
            const roundedPPN = Math.round(ppn);

            // Menyimpan PPN yang sudah dibulatkan ke dalam form
            form.tax_amount = roundedPPN;

            // Mengembalikan nilai PPN yang diformat
            return formatRupiah(roundedPPN);
        });



        const subtotal = computed(() => {
            // Menghitung subtotal dengan harga sebelum PPN
            const total = form.transaction_items.reduce((total, item) => {
                // Harga sebelum PPN = harga termasuk PPN / 1.11
                const priceExcludingTax = (Number(item.total_price ?? 0)) / 1.11;
                return total + priceExcludingTax; // Menjumlahkan harga yang sudah di-exclude PPN
            }, 0);

            // Menyimpan subtotal ke dalam form
            form.sub_total = total;

            // Mengembalikan subtotal yang diformat
            return formatRupiah(total);
        });


        const totalPrice = computed(() => {
            // Menghitung subtotal tanpa PPN, kemudian dikali quantity
            const subtotalExcludingTax = form.transaction_items.reduce((total, item) => {
                const priceExcludingTax = (Number(item.total_price ?? 0)) / 1.11;
                return total + priceExcludingTax;
            }, 0);

            // Menghitung total harga yang termasuk PPN
            let grandTotal = subtotalExcludingTax * 1.11; // Mengembalikan harga total yang termasuk PPN

            // Menyimpan total ke dalam form
            const roundedTotalWithPpn = Math.round(grandTotal);

            form.total = roundedTotalWithPpn;

            // Mengembalikan total harga yang diformat
            return formatRupiah(grandTotal);
        });

        function handleGenerateDocument() {
            window.open(route('procurement.generate-sso-document', detailTransaction.id), '_blank');
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
        ErrorInput,
        Head
    }
})
</script>