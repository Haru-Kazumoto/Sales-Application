<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Invoice DNP" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('aging-finance.invoice-dnp'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <!-- row 1 -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NO CO</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.customer_order_number"
                            readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Nomor Surat Jalan</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.travel_document_number"
                            readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Nomor Faktur</label>
                        <n-input size="large" placeholder="" v-model:value="form.document_code" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Tanggal Faktur</label>
                        <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" size="large"
                            v-model:value="transaction_details.invoice_date" readonly />
                    </div>

                    <!-- row 2 -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Termin</label>
                        <n-input size="large" placeholder="" v-model:value="form.term_of_payment">
                            <template #suffix>HARI</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Tanggal Jatuh Tempo</label>
                        <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" size="large"
                            v-model:formatted-value="form.due_date" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Nomor Polisi</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.number_plate"
                            readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Salesman</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.salesman" readonly />
                    </div>

                    <!-- row 3 -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Nama Customer</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.customer" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NPWP</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.npwp" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Alamat Customer</label>
                        <n-input size="large" placeholder="" v-model:value="transaction_details.customer_address"
                            readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">PPN</label>
                        <n-select size="large" placeholder="" @update:value="updateTax" :options="ppnOptions"
                            v-model:value="tax.tax_id" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :data="form.transaction_items" :columns="columns" />
            </div>
        </div>

        <div class="card shadow-sm border-0 ">
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
                    <span>Total Harga</span>
                    <span>{{ totalPrice }}</span>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-8 d-flex flex-column text-body-tertiary">
                        <span>Harap ditransfer ke : </span>
                        <span>PT. DANITAMA NIAGAPRIMA</span>
                        <span>NPWP: 01.345.766.7-064.000</span>
                        <span>BANK CENTRAL ASIA CAB HASANUDIN JAKARTA A/C NO. 523.0300.200</span>
                        <span>BANK CENTRAL ASIA CAB JATIASIH JAKARTA A/C NO. 675.5010.255</span>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
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
        </div>

        <n-button type="primary" size="medium" class="ms-auto mb-5" @click="handleSubmitInvoice">Buat Surat
            Invoice</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, watch, computed } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from "@vicons/ionicons5";
import { Transactions, TransactionItems, TransactionDetail, Lookup, Tax, Flash } from "../../../types/model.ts";
import { formatRupiah } from '../../../Utils/options-input.utils.ts';
import Swal from 'sweetalert2';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

function createColumns() {
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
            title: "KODE BARANG",
            key: "code",
            width: 150,
            render(row) {
                return row.product?.code;
            }
        },
        {
            title: "NAMA BARANG",
            key: "name",
            width: 200,
            render(row) {
                return row.product?.name;
            }
        },
        {
            title: "JUMLAH",
            key: "quantity",
            width: 150,
        },
        {
            title: "SATUAN",
            key: "unit",
            width: 100,
        },
        {
            title: "HARGA",
            key: "amount",
            width: 200,
            render(row) {
                return formatRupiah(row.amount);
            }
        },
        {
            title: "TOTAL",
            key: "calculate",
            width: 200,
            render(row) {
                const quantity = row.quantity;
                const amount = row.amount;

                return formatRupiah(quantity * amount);
            }
        },
    ]
}

export default defineComponent({
    setup() {
        const page = usePage();
        const travel_document = page.props.transactions as Transactions;
        console.log(travel_document);
        const form = useForm({
            document_code: page.props.invoice_number as string,
            term_of_payment: travel_document.term_of_payment,
            sub_total: null as unknown as number,
            total: null as unknown as number,
            tax_amount: null as unknown as number,
            due_date: travel_document.due_date,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_items = ref({
            unit: '',
            quantity: null as unknown as number,
            amount: null as unknown as number,
            tax_id: null as unknown as number,
            product_id: null as unknown as number,
            total_price: null as unknown as number,
        });

        const transaction_details = ref({
            customer_order_number: travel_document.transaction_details.find((data) => data.category === "CO Number")?.value,
            travel_document_number: travel_document.document_code,
            invoice_date: new Date(),
            number_plate: travel_document.transaction_details.find((data) => data.category === "Number Plate")?.value,
            salesman: travel_document.transaction_details.find((data) => data.category === "Salesman")?.value,
            customer: travel_document.transaction_details.find((data) => data.category === "Customer")?.value,
            npwp: travel_document.transaction_details.find((data) => data.category === "NPWP")?.value,
            customer_address: travel_document.transaction_details.find((data) => data.category === "Customer Address")?.value,
            warehouse: travel_document.transaction_details.find((data) => data.category === "Warehouse")?.value,
        });

        const tax = ref({
            tax_id: null as unknown as number,
        });

        watch(transaction_items, (data) => {
            travel_document?.transaction_items?.forEach(item => {
                form.transaction_items.push({
                    unit: item.unit,
                    quantity: item.quantity,
                    amount: item.amount,
                    total_price: item.total_price,
                    product_id: item.product_id,
                    tax_amount: item.tax_amount,
                    tax_value: null,
                    product: {
                        code: item.product?.code || '',
                        unit: item.product?.unit || '',
                        name: item.product?.name || '',
                    }
                });
            });
        }, { immediate: true });

        watch(
            [() => transaction_details.value.invoice_date, () => form.term_of_payment],
            ([newPurchaseOrderDate, newTermOfPayment]) => {
                if (newPurchaseOrderDate) {
                    // Jika purchase_order_date sudah ada, set due_date berdasarkan term_of_payment
                    const termDays = newTermOfPayment ; // Gunakan term_of_payment atau default 45
                    form.due_date = handleSetFutureDateTo(termDays, newPurchaseOrderDate);
                } 
            }
        );

        function handleSetFutureDateTo(days: number, start_date: any) {
            const baseDate = new Date(start_date);
            baseDate.setDate(baseDate.getDate() + days);

            return baseDate.toISOString().slice(0, 19).replace('T', ' ');
        }

        function handleSubmitInvoice() {
            form.transaction_details = [
                {
                    name: "Nomor CO",
                    category: "CO Number",
                    value: transaction_details.value.customer_order_number,
                    data_type: "string"
                },
                {
                    name: "Nomor Surat Jalan",
                    category: "Travel Document Number",
                    value: transaction_details.value.travel_document_number,
                    data_type: "string"
                },
                {
                    name: "Gudang",
                    category: "Warehouse",
                    value: transaction_details.value.warehouse,
                    data_type: "string"
                },
                {
                    name: "Tanggal Invoice",
                    category: "Invoice Date",
                    value: dayjs(transaction_details.value.invoice_date as any).format(),
                    data_type: "datetime",
                },
                {
                    name: "Nomor Polisi",
                    category: "Number Plate",
                    value: transaction_details.value.number_plate,
                    data_type: "string"
                },
                {
                    name: "Salesman",
                    category: "Salesman",
                    value: transaction_details.value.salesman,
                    data_type: "string"
                },
                {
                    name: "Customer",
                    category: "Customer",
                    value: transaction_details.value.customer,
                    data_type: "string"
                },
                {
                    name: "NPWP",
                    category: "NPWP",
                    value: transaction_details.value.npwp,
                    data_type: "string"
                },
                {
                    name: "Alamat Customer",
                    category: "Customer Address",
                    value: transaction_details.value.customer_address,
                    data_type: "string",
                }
            ];

            form.post(route('aging-finance.invoice.store'), {
                onSuccess() {
                    Swal.fire({
                        title: (page.props.flash as Flash).success,
                        icon: 'success'
                    });
                },
                onError() {
                    Swal.fire({
                        title: 'Error, chek your log!',
                        icon: 'error'
                    });
                }
            });
        }

        const totalPPN = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = form.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung PPN 11%
            const ppn = subtotal * 0.11;

            // Menyimpan PPN ke dalam form
            form.tax_amount = ppn;

            // Mengembalikan nilai PPN yang diformat
            return formatRupiah(ppn);
        });

        const subtotal = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const total = form.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menyimpan subtotal ke dalam form
            form.sub_total = total;

            // Mengembalikan subtotal yang diformat
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
            form.total = totalWithPPN;

            // Mengembalikan total harga yang diformat
            return formatRupiah(totalWithPPN);
        });

        const termPaymentOptions = (page.props.payment_terms as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const ppnOptions = (page.props.taxes as Tax[]).map((data) => ({
            label: data.name,
            value: data.id,
            percentage: data.value / 100 // Tambahkan persentase PPN
        }));



        // Method untuk update tax_id
        const updateTax = (selectedTaxId: number) => {
            form.transaction_items = form.transaction_items.map((item) => ({
                ...item,
                tax_id: selectedTaxId,
            }));
        };

        return {
            columns: createColumns(),
            handleSubmitInvoice,
            updateTax,
            dayjs,
            router,
            form,
            transaction_details,
            transaction_items,
            ppnOptions,
            termPaymentOptions,
            tax,
            totalPPN,
            subtotal,
            totalPrice,
            ArrowBack
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>