<template>

    <Head title="Sales Order" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Sales Order" />
        <div class="d-flex flex-column gap-3">
            <div class="card shadow overflow-hidden" style="border: none">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Baris Pertama -->
                        <div class="col-lg-2 col-6">
                            <label for="field1">
                                No PO
                                <RequiredMark />
                            </label>
                            <n-input id="field1" size="large" placeholder=""
                                v-model:value="transaction_details.po_number" />
                        </div>
                        <div class="col-lg-2 col-6 d-flex align-items-end">
                            <n-button class="w-100" size="large" strong secondary type="primary"
                                @click="handleGetProducts">
                                Proses</n-button>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field2">No Bukti
                                <RequiredMark />
                            </label>
                            <n-input id="field2" size="large" placeholder=""
                                v-model:value="transaction_details.proof_number"
                                @input="(value) => transaction_details.proof_number = value.toUpperCase()" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field3">No SO
                                <RequiredMark />
                            </label>
                            <n-input id="field3" size="large" placeholder="" v-model:value="form.document_code"
                                @input="(value) => form.document_code = value.toUpperCase()" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-lg-4 col-6">
                            <label for="field4">Tanggal PO </label>
                            <n-input size="large" v-model:value="transaction_details.purchase_order_date" disabled
                                placeholder="" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field5">Alokasi </label>
                            <n-input id="field5" size="large" disabled placeholder=""
                                v-model:value="transaction_details.located" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field6">Pemasok </label>
                            <n-input id="field6" size="large" disabled placeholder=""
                                v-model:value="transaction_details.supplier" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field8">Tanggal Kirim
                                <RequiredMark />
                            </label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="transaction_details.send_date" placeholder="" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field9">Nomor Polisi Ekspedisi </label>
                            <n-input id="field9" size="large" placeholder=""
                                v-model:value="transaction_details.transportation" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-lg-4 col-6">
                            <label for="field10">Nama Ekspedisi </label>
                            <n-input id="field10" size="large" placeholder="" filterable :options="angkutanOptions"
                                disabled v-model:value="transaction_details.sender" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field11">Jenis Pengiriman </label>
                            <n-input id="field11" size="large" placeholder="" :options="sendType" disabled
                                v-model:value="transaction_details.delivery_type" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field12">PIC </label>
                            <n-input id="field12" size="large" disabled placeholder=""
                                v-model:value="transaction_details.employee_name" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="catatan">Catatan</label>
                            <n-input id="catatan" type="textarea" placeholder="" v-model:value="form.description" />
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
                    <div class="d-flex justify-content-between py-2" v-if="transaction_details.use_tax">
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
                                <span class="fw-bold" v-if="form.term_of_payment !== undefined">{{
                                    form.term_of_payment }} HARI</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>JATUH TEMPO</span>
                                <span class="fw-bold" v-if="form.due_date !== null">
                                    {{
                                        dayjs(form.due_date)
                                            .format('dddd, D MMMMYYYY ')
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <n-button type="primary" class="d-flex flex-column ms-auto mb-4" @click="handleSubmit">SUBMIT</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, computed, watch } from 'vue'
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import TitlePage from '../../../Components/TitlePage.vue';
import ErrorInput from '../../../Components/ErrorInput.vue';
import RequiredMark from '../../../Components/RequiredMark.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { Flash, Parties, PurchaseOrder, SubSalesOrder, SubSalesOrderProducts, TransactionDetail, TransactionItems, Transactions, User } from '../../../types/model';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        const form = useForm({
            document_code: '',
            term_of_payment: '',
            due_date: null as unknown as string,
            description: '',
            sub_total: null as unknown as number,
            total: null as unknown as number,
            tax_amount: null as unknown as number,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_details = ref({
            po_number: '',
            proof_number: '',
            supplier: '',
            storehouse: '',
            located: '',
            delivery_type: '',
            purchase_order_date: null as string | null,
            send_date: null as string | null,
            transportation: '',
            sender: '',
            employee_name: (page.props.auth.user as User).fullname,
            use_tax: '',
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
            tax_amount: 0,
            total_price: null as unknown as number,
            amount: 0,
            product_id: null as unknown as number,
            tax_id: null as unknown as number,
        });

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
                    width: 150,
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

        // Fungsi untuk menghapus produk dari array
        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        watch(() => products.value, (product) => {
            product.transaction_items.forEach((data) => {
                transaction_items.value.product_id = data.product_id ?? 0;
            });
        });

        function handleSubmit() {
            Swal.fire({
                title: 'Sedang Memproses SSO...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.transaction_details = [
                {
                    name: "Nomor PO",
                    category: 'PO Number',
                    value: transaction_details.value.po_number,
                    data_type: 'string',
                },
                {
                    name: "Nomor Bukti",
                    category: "Proof Number",
                    value: transaction_details.value.proof_number,
                    data_type: 'string',
                },
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
                    name: "Status Generate",
                    category: "Generate Status",
                    value: "false",
                    data_type: "boolean",
                }
            ];

            form.post(route('procurement.sales-order.post'), {
                onError: (err) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cek kembali form',
                        text: 'Isi form yang terdapat tanda *',
                    });
                },
                onSuccess: () => {


                    // Reset form dengan nilai awal
                    form.document_code = '',
                        form.term_of_payment = '',
                        form.description = '',
                        form.sub_total = null as unknown as number,
                        form.total = null as unknown as number,
                        form.tax_amount = null as unknown as number,
                        form.transaction_details = [],
                        form.transaction_items = [],

                        // Kosongkan objek yang menggunakan ref
                        transaction_details.value = {
                            po_number: '',
                            proof_number: '',
                            supplier: '',
                            storehouse: '',
                            located: '',
                            delivery_type: '',
                            purchase_order_date: null as string | null,
                            send_date: null as string | null,
                            transportation: '',
                            sender: '',
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
                        quantity: null,
                        tax_amount: null as unknown as number,
                        amount: null as unknown as number,
                        total_price: null as unknown as number,
                        product_id: null as unknown as number,
                        tax_id: null as unknown as number,
                    };
                    form.transaction_items.splice(0, form.transaction_items.length);

                    Swal.fire({
                        icon: 'success',
                        title: page.props.flash.success,
                        showConfirmButton: false,
                        timer: 1800,
                        timerProgressBar: true,
                    });

                },
            });
        }

        async function handleGetProducts() {
            router.visit(route('procurement.get-data-po', transaction_details.value.po_number), {
                method: 'get',
                preserveState: true, // This preserves the form state
                onSuccess: (page) => {
                    //received data from backend
                    const data = (page.props.transaction as Transactions);

                    if (!data) {
                        Swal.fire({
                            icon: 'error',
                            title: "Nomor PO tidak valid atau tidak ada."
                        });
                    }

                    const supplier = data.transaction_details.find(data => data.category === "Supplier")?.value || '';
                    const storehouse = data.transaction_details.find(data => data.category === "Storehouse")?.value || '';
                    const located = data.transaction_details.find(data => data.category === "Allocation")?.value || '';
                    const purchase_order_date = data.transaction_details.find(data => data.category === "PO Date")?.value || '';
                    const sender = data.transaction_details.find(data => data.category === "Sender")?.value || '';
                    const delivery_type = data.transaction_details.find(data => data.category === "Delivery Type")?.value || '';
                    const number_plate = data.transaction_details.find(data => data.category === "Transportation")?.value || '';
                    const use_tax = data.transaction_details.find(data => data.category === "Use Tax")?.value || '';

                    form.term_of_payment = data.term_of_payment;
                    form.due_date = data.due_date;

                    transaction_details.value.po_number = data.document_code;
                    transaction_details.value.supplier = supplier;
                    transaction_details.value.storehouse = storehouse;
                    transaction_details.value.located = located;
                    transaction_details.value.purchase_order_date = purchase_order_date;
                    transaction_details.value.sender = sender.replace("_", ' ');
                    transaction_details.value.delivery_type = delivery_type.replace("_", " ");
                    transaction_details.value.transportation = number_plate;
                    transaction_details.value.use_tax = use_tax;

                    // Reset form.transaction_items untuk memasukkan produk baru
                    form.transaction_items = [];
                    // Loop data dari backend dan masukkan ke form.transaction_items
                    data.transaction_items?.forEach(item => {
                        form.transaction_items.push({
                            unit: item.unit,
                            quantity: item.quantity,
                            tax_amount: item.tax_amount,
                            amount: item.amount,
                            tax_id: item.tax_id,
                            tax_value: item.tax?.value || 0,
                            product_id: item.product_id,
                            total_price: item.total_price,
                            product: {
                                code: item.product?.code || '',
                                unit: item.product?.unit || '',
                                name: item.product?.name || '',
                            }
                        });
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
            let total = null as unknown as number;

            if (transaction_details.value.use_tax) {
                total = subtotal + (subtotal * 0.11);
            } else {
                total = subtotal;
            }

            // Menyimpan total ke dalam form
            const roundedTotalWithPpn = Math.round(total);

            form.total = roundedTotalWithPpn;

            // Mengembalikan total harga yang diformat
            // return roundedTotalWithPpn;
            return formatRupiah(total);
        });

        const angkutanOptions = (page.props.parties as Parties[]).map(data => ({
            label: data.name,
            value: data.name
        }));

        const sendType = [
            { label: "DEPO BEKASI", value: "DEPO BEKASI" },
            { label: "DIRECT", value: "DIRECT" },
            { label: "DIRECT DEPO", value: "DIRECT_DEPO" },
            { label: "BELI DO", value: "DO" },
        ];

        return {
            handleSubmit,
            handleGetProducts,
            dayjs,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            form,
            angkutanOptions,
            transaction_details,
            products,
            sendType,
            transaction_items,
            page,
            subTotal: subtotal,
            resultPpn: totalPPN,
            total: totalPrice,
        }
    },
    components: {
        TitlePage,
        ErrorInput,
        Head,
        RequiredMark
    }
})
</script>