<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Detail Faktur" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('aging-finance.list-invoice'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NOMOR CO</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.customer_order_number" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NOMOR SURAT JALAN</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.travel_document_number" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NOMOR FAKTUR</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.invoice_number" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">TANGGAL FAKTUR</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.invoice_date" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">TERMIN</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.term_of_payment" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">TANGGAL JATUH TEMPO</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.due_date" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NOMOR POLISI</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.number_plate" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">SALESMAN</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.salesman" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NAMA CUSTOMER</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.customer_name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NPWP</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.npwp" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">ALAMAT CUSTOMER</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.customer_address" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">PPN</label>
                        <n-input placeholder="" size="large" v-model:value="dataInvoice.ppn" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <span class="fs-5 fw-semibold">Data Barang</span>
                <n-data-table :bordered="false" :columns :data="($page.props.data as any).transaction_items" />
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-2">
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
                    <span>{{ formatRupiah(totalPrice) }}</span>
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
                                <span class="fw-bold">{{ ($page.props.data as any).term_of_payment }} HARI</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold">
                                    {{ ($page.props.data as any).due_date ? dayjs(($page.props.data as
                                        any).due_date).format('dddd, D MMMM YYYY') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-5" v-if="$page.props.auth.user.division.division_name === 'FINANCE'">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <span class="fs-5 fw-semibold">Log Pembayaran</span>
                    <n-button type="info" class="ms-auto" @click="showModal = true"
                        :disabled="$page.props.statusPayment === 'PAID' ? true : false">BUAT PEMBAYARAN</n-button>
                </div>
                <n-divider />
                <table class="w-50">
                    <tr class="py-2">
                        <td class="fw-semibold" style="width: 50%;">Total tagihan</td>
                        <td class="fw-semibold text-center" style="width: 1%;">:</td>
                        <td class="fw-semibold">{{ formatRupiah(totalPrice) }}</td>
                    </tr>
                    <tr class="py-2">
                        <td class="fw-semibold" style="width: 50%;">Pembayaran yang dibayar</td>
                        <td class="fw-semibold text-center" style="width: 1%;">:</td>
                        <td class="fw-semibold">{{ formatRupiah($page.props.totalPaid) }}</td>
                    </tr>
                    <tr class="py-2">
                        <td class="fw-semibold" style="width: 50%;">Sisa total tagihan</td>
                        <td class="fw-semibold text-center" style="width: 1%;">:</td>
                        <td class="fw-semibold">{{ formatRupiah($page.props.totalLeft) }}</td>
                    </tr>
                    <tr class="py-2">
                        <td class="fw-semibold" style="width: 50%;">Status pembayaran</td>
                        <td class="fw-semibold text-center" style="width: 1%;">:</td>
                        <td class="fw-semibold">
                            <n-tag strong :type="$page.props.statusPayment === 'INSTALMENT' ? 'warning' : 'success'">
                                {{ $page.props.statusPayment }}
                            </n-tag>
                        </td>
                    </tr>
                </table>


                <div style="display: flex; border: 1px solid black;" class="my-3" />
                <div v-if="($page.props.data as any).invoice_payments.length === 0">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <n-image src="/images/empty.png" preview-disabled width="300" />
                        <span class="fs-3 fw-semibold">Belum Ada Pembayaran</span>
                    </div>
                </div>
                <div v-else>
                    <div v-for="(payment, index) in ($page.props.data as any).invoice_payments" :key="index">
                        <div class="card shadow-sm float-card mb-3">
                            <div class="card-body d-flex gap-4">
                                <div class="d-flex gap-3 align-items-center">
                                    <n-icon-wrapper :size="70" :border-radius="10" color="#00a54f" class="d-flex">
                                        <n-icon :size="40" :component="ReceiptMoney24Filled" color="white"
                                            class="text-center" />
                                    </n-icon-wrapper>
                                    <Link :href="route('admin.user-management')" class="ms-auto">
                                    <n-button type="default" ghost>Direct</n-button>
                                    </Link>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-reguler fs-5">Pembayaran hari {{
                                        dayjs(payment.invoice_date).format('dddd, D MMMM YYYY HH:mm') }}</span>
                                    <div class="d-flex align-items-center gap-4">
                                        <span class="fw-bold fs-4">{{ formatRupiah(payment.total_paid) }}</span>
                                        <n-tag type="info" :strong="true" :bordered="true">{{ payment.payment_method }}</n-tag>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <n-tag strong size="large"
                                        :type="payment.status_payment === 'INSTALMENT' ? 'warning' : 'success'">
                                        {{ payment.status_payment }}
                                    </n-tag>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <n-modal v-model:show="showModal" class="custom-card" preset="card" :style="bodyStyle"
                title="Pembayaran" :bordered="false" size="huge" :segmented="segmented">

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-column gap-1">
                        <label for="">NOMINAL PEMBAYARAN</label>
                        <n-input placeholder="" size="large" v-model:value="payForm.total_paid"
                            @input="(value) => payForm.total_paid = value.replace(/\D/g, '')">
                            <template #prefix>Rp </template>
                        </n-input>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <label for="">METODE PEMBAYARAN</label>
                        <n-select placeholder="" size="large" v-model:value="payForm.payment_method" 
                            :options="paymentMethod" />
                    </div>
                </div>

                <template #footer>
                    <div class="d-flex">
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <n-button type="error" @click="showModal = false">Batal</n-button>
                            <n-button type="primary" @click="handlePayInvoice(($page.props.data as any).id)">Bayar</n-button>
                        </div>
                    </div>
                </template>
            </n-modal>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, h, watch } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { ArrowBack } from '@vicons/ionicons5';
import { ReceiptMoney24Filled } from '@vicons/fluent';
import { router, useForm, usePage } from "@inertiajs/vue3";
import { formatRupiah } from '../../../Utils/options-input.utils';
import dayjs from 'dayjs';
import Swal from "sweetalert2";
import 'dayjs/locale/id'; // Import locale Indonesia
import { Flash } from '../../../types/model';
import { NTag, useNotification } from 'naive-ui';

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {

        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "KODE BARANG",
                    key: "code",
                    width: 200,
                    render(row) {
                        return row.product?.code;
                    }
                },
                {
                    title: "NAMA BARANG",
                    key: 'name',
                    width: 200,
                    render(row) {
                        return row.product?.name;
                    }
                },
                {
                    title: "QUANTITY",
                    key: "quantity",
                    width: 150,
                },
                {
                    title: "SATUAN",
                    key: "unit",
                    width: 150,
                    render(row) {
                        return row.product?.unit;
                    }
                },
                {
                    title: "HARGA",
                    key: "amount",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.amount ?? 0);
                    }
                },
                {
                    title: "TOTAL",
                    key: "total",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total_price);
                    }
                }
            ]
        }

        const totalPPN = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = data.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung PPN 11%
            const ppn = subtotal * 0.11;

            // Menyimpan PPN ke dalam form
            data.tax_amount = ppn;

            // Mengembalikan nilai PPN yang diformat
            return formatRupiah(ppn);
        });

        const subtotal = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const total = data.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menyimpan subtotal ke dalam form
            data.sub_total = total;

            // Mengembalikan subtotal yang diformat
            return formatRupiah(total);
        });

        const totalPrice = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = data.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung total harga termasuk PPN 11%
            const totalWithPPN = subtotal + (subtotal * 0.11);

            // Menyimpan total ke dalam form
            data.total = totalWithPPN;

            // Mengembalikan total harga yang diformat
            return totalWithPPN;
        });

        const page = usePage();
        const data = page.props.data as any;
        const dataDetails = data.transaction_details as any[];
        const showModal = ref(false);
        const notification = useNotification();

        const dataInvoice = ref({
            customer_order_number: dataDetails.find(data => data.category === "CO Number")?.value,
            travel_document_number: dataDetails.find(data => data.category === "Travel Document Number")?.value,
            invoice_number: data.document_code,
            invoice_date: dayjs(dataDetails.find(data => data.category === "Invoice Date")?.value).format('dddd, D MMMM YYYY HH:mm'),
            term_of_payment: data.term_of_payment + " HARI",
            due_date: dayjs(data.due_date).format('dddd, D MMMM YYYY HH:mm'),
            number_plate: dataDetails.find(data => data.category === "Number Plate")?.value,
            salesman: dataDetails.find(data => data.category === "Salesman")?.value,
            customer_name: dataDetails.find(data => data.category === "Customer")?.value,
            npwp: dataDetails.find(data => data.category === "NPWP")?.value,
            customer_address: dataDetails.find(data => data.category === "Customer Address")?.value,
            ppn: 11,
        });

        const payForm = useForm({
            invoice_date: dataDetails.find(data => data.category === "Invoice Date")?.value,
            total_paid: null as unknown as number,
            payment_method: "CASH",
        });

        function handleSetStatus() {
            const currentPaymentStatus = page.props.statusPayment;

            return h(
                NTag,
                {
                    type: currentPaymentStatus === "INSTALMENT" ? 'warning' : 'success',
                    strong: true,
                    size: 'medium',
                }, { default: () => currentPaymentStatus }
            )
        }

        watch(() => payForm.total_paid, (data) => {
            const total_left = page.props.totalLeft as number;
            if (data > total_left) {
                // showModal.value = false;
                payForm.reset();
                notification.error({
                    title: "Pembayaran lebih dari sisa tagihan!",
                    meta: "Silakan cek kembali tagihan Anda",
                    duration: 2500,
                    closable: true,
                });
            }
        });

        function handlePayInvoice(transaction_id: number) {
            payForm.post(route('aging-finance.invoice.pay', transaction_id), {
                onSuccess: (page) => {
                    payForm.reset();
                    showModal.value = false;
                    Swal.fire({
                        title:(page.props.flash as Flash).success,
                        icon: 'success',
                        timer: 2500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                },
                onError: () => {
                    Swal.fire((page.props.flash as Flash).failed, '', 'error');
                }
            });
        }

        const paymentMethod = [
            { label: "CASH", value: "CASH" },
            { label: "TRANSFER", value: "TRANSFER" },
            { label: "GYRO", value: "GYRO" }
        ]

        return {
            columns: createColumns(),
            handlePayInvoice,
            formatRupiah,
            handleSetStatus,
            dayjs,
            dataInvoice,
            router,
            totalPPN,
            subtotal,
            totalPrice,
            payForm,
            ArrowBack,
            ReceiptMoney24Filled,
            paymentMethod,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            showModal,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>
.float-card {
    border-color: rgb(50, 255, 4);
    transition: transform 0.3s ease-in-out, border 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.float-card:hover {
    transform: scale(1.01);
    border: 1px solid #28a745;
    /* Warna hijau untuk border */
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.5);
    /* Warna hijau untuk shadow */
}
</style>