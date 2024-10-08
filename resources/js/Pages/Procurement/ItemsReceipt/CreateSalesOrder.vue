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
                            <label for="field1">
                                No PO<span class="text-danger">*</span>
                            </label>
                            <n-input id="field1" size="large" v-model:value="transaction_details.po_number" />
                        </div>
                        <div class="col-lg-2 col-6 d-flex align-items-end">
                            <n-button class="w-100" size="large" strong secondary type="primary"
                                @click="handleGetProducts">
                                Proses</n-button>
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field2">No Bukti<span class="text-danger">*</span></label>
                            <n-input id="field2" size="large" v-model:value="transaction_details.proof_number" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field3">No SO<span class="text-danger">*</span></label>
                            <n-input id="field3" size="large" v-model:value="form.document_code" />
                        </div>

                        <!-- Baris Kedua -->
                        <div class="col-lg-4 col-6">
                            <label for="field4">Tanggal PO<span class="text-danger">*</span></label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="transaction_details.purchase_order_date" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field5">Alokasi<span class="text-danger">*</span></label>
                            <n-input id="field5" size="large" disabled v-model:value="transaction_details.located" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field6">Pemasok<span class="text-danger">*</span></label>
                            <n-input id="field6" size="large" disabled v-model:value="transaction_details.supplier" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field7">Gudang<span class="text-danger">*</span></label>
                            <n-input id="field7" size="large" disabled v-model:value="transaction_details.storehouse" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field8">Tanggal Kirim<span class="text-danger">*</span></label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="transaction_details.send_date" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field9">Transportasi<span class="text-danger">*</span></label>
                            <n-input id="field9" size="large" v-model:value="transaction_details.transportation" />
                        </div>

                        <!-- Baris Ketiga -->
                        <div class="col-lg-4 col-6">
                            <label for="field10">Pengirim<span class="text-danger">*</span></label>
                            <n-input id="field10" size="large" v-model:value="transaction_details.sender" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field11">Jenis Pengiriman<span class="text-danger">*</span></label>
                            <n-input id="field11" size="large" v-model:value="transaction_details.delivery_type" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <label for="field12">Karyawan<span class="text-danger">*</span></label>
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
                            <n-input id="catatan" type="textarea" placeholder="Basic Textarea" style="width: 30rem;"
                                v-model:value="form.description" />
                        </div>
                        <div class="d-flex flex-column w-100 justify-content-between">
                            <div class="d-flex justify-content-between">
                                <span>TERM OF PAYMENT</span>
                                <span class="fw-bold" v-if="form.term_of_payment !== undefined">{{
                                    form.term_of_payment.replace("_", " ") }}</span>
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
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { Flash, PurchaseOrder, SubSalesOrder, SubSalesOrderProducts, TransactionDetail, TransactionItems, Transactions, User } from '../../../types/model';
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
                    render(row) {
                        return row.product?.code;
                    }
                },
                {
                    title: 'Nama Barang',
                    key: 'product_name',
                    render(row) {
                        return row.product?.name;
                    }
                },
                {
                    title: 'Jumlah',
                    key: 'quantity',
                },
                {
                    title: 'Kemasan',
                    key: 'unit',
                    render(row) {
                        return row.unit.replace("_", ' ');
                    }
                },
                {
                    title: 'Harga Barang',
                    key: 'amount',
                    render(row) {
                        return formatRupiah(row.amount ?? 0);
                    }
                },
                {
                    title: 'PPN',
                    key: 'tax_amount',
                    render(row) {
                        return formatRupiah(row.tax_amount ?? 0);
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
                    form.document_code = (page.props.po_number as string),
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
                        product_id: null as unknown as number,
                        tax_id: null as unknown as number,
                    };
                    form.transaction_items.splice(0, form.transaction_items.length);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success submit SSO!'
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
                            title: (page.props.flash as Flash).failed
                        });
                    }

                    const supplier = data.transaction_details.find(data => data.category === "Supplier")?.value || '';
                    const storehouse = data.transaction_details.find(data => data.category === "Storehouse")?.value || '';
                    const located = data.transaction_details.find(data => data.category === "Allocation")?.value || '';

                    form.term_of_payment = data.term_of_payment;
                    form.due_date = data.due_date;

                    transaction_details.value.po_number = data.document_code;
                    transaction_details.value.supplier = supplier;
                    transaction_details.value.storehouse = storehouse;
                    transaction_details.value.located = located;

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
                            product_id: item.product_id,
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
            // Cek apakah ada transaction_items
            if (form.transaction_items && form.transaction_items.length > 0) {
                const subtotal = form.transaction_items.reduce((total, item) => {
                    return total + (item.amount ?? 0); // Menghitung subtotal dari amount
                }, 0);

                // Menghitung PPN 11%
                const ppn = subtotal * 0.11;

                // Simpan PPN ke dalam form
                form.tax_amount = ppn;

                // Mengembalikan PPN yang diformat
                return formatRupiah(ppn);
            }

            // Return 0 jika tidak ada transaction_items
            return formatRupiah(0);
        });


        const subtotal = computed(() => {
            // Cek apakah ada transaction_items
            if (form.transaction_items && form.transaction_items.length > 0) {
                const subtotal = form.transaction_items.reduce((total, item) => {
                    return total + (item.amount ?? 0); // Menghitung subtotal dari amount
                }, 0);

                // Simpan subtotal ke dalam form
                form.sub_total = subtotal;

                // Mengembalikan subtotal yang diformat
                return formatRupiah(subtotal);
            }

            // Return 0 jika tidak ada transaction_items
            return formatRupiah(0);
        });


        const totalPrice = computed(() => {
            // Cek apakah ada transaction_items
            if (form.transaction_items && form.transaction_items.length > 0) {
                const subtotal = form.transaction_items.reduce((total, item) => {
                    return total + (item.amount ?? 0); // Menghitung subtotal dari amount
                }, 0);

                // Menghitung total harga termasuk PPN 11%
                const totalWithPPN = subtotal + (subtotal * 0.11);

                // Simpan total ke dalam form
                form.total = totalWithPPN;

                // Mengembalikan total yang diformat
                return formatRupiah(totalWithPPN);
            }

            // Return 0 jika tidak ada transaction_items
            return formatRupiah(0);
        });


        return {
            handleSubmit,
            handleGetProducts,
            dayjs,
            columns: createColumns(),
            pagination: { pageSize: 10 },
            form,
            transaction_details,
            products,
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
        Head
    }
})
</script>