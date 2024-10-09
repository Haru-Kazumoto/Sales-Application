<template>

    <Head title="Create CO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="CUSTOMER ORDER" />
        <!-- INPUT CO FORM -->
        <div class="card shadow" style="border: none;">
            <!-- INPUT CO -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR CO <span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="form.document_code" placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL DIBUAT CO<span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="transaction_details.customer_order_date"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NAMA CUSTOMER<span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="transaction_details.customer" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">LEGALITAS<span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="transaction_details.legality"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">ALAMAT CUSTOMER<span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="transaction_details.customer_address"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TERMIN<span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="form.term_of_payment" placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL JATUH TEMPO<span class="text-danger">*</span></label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field8"
                                size="large" v-model:formatted-value="form.due_date" disabled />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">SALESMAN<span class="text-danger">*</span></label>
                            <n-input disabled size="large" v-model:value="transaction_details.salesman"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA ANGKUTAN<span class="text-danger">*</span></label>
                            <n-input disabled size="large" placeholder=""
                                v-model:value="transaction_details.transportation_cost"
                                @input="(value) => transaction_details.transportation_cost = value.replace(/\D/g, '')">
                                <template #prefix>
                                    Rp
                                </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">CASHBACK<span class="text-danger">*</span></label>
                            <n-input size="large" disabled v-model:value="transaction_details.cashback" placeholder=""
                                @input="(value) => transaction_details.cashback = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA BONGKAR<span class="text-danger">*</span></label>
                            <n-input size="large" disabled v-model:value="transaction_details.unloading_cost"
                                placeholder=""
                                @input="(value) => transaction_details.unloading_cost = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCT CHOOSEN LIST -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns"
                    :data="enhancedTransactionItems" />
            </div>
        </div>

        <!-- CALCULATION RESULT -->
        <div class="card shadow border-0 mb-4">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>{{ formatRupiah(($page.props.customer_order as Transactions).sub_total ?? 0) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>{{ formatRupiah(($page.props.customer_order as Transactions).tax_amount ?? 0) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total harga</span>
                    <span>{{ formatRupiah(($page.props.customer_order as Transactions).total ?? 0) }}</span>
                </div>
                <div class="d-flex flex-column w-100 justify-content-between mt-2 gap-3">
                    <div class="d-flex justify-content-between">
                        <span>TERM OF PAYMENT</span>
                        <span class="fw-bold">{{ form.term_of_payment.replace("_", ' ') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>JATUH TEMPO</span>
                        <span class="fw-bold">{{ form.due_date ? dayjs(form.due_date).format('dddd, D MMMM YYYY') : ''
                            }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import { computed, defineComponent, h, ref, watch } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, SelectOption, useNotification } from 'naive-ui';
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { ProductCustomerOrder } from '../../../types/dto';
import Swal from 'sweetalert2';
import { Lookup, Parties, Products, TransactionDetail, TransactionItems, Transactions, User } from '../../../types/model';
import { formatRupiah } from '../../../Utils/options-input.utils';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();
        const customer_order = page.props.customer_order as Transactions;

        function createColumns(): DataTableColumns<TransactionItems> {
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
                    title: "NAMA PRODUK",
                    key: 'name',
                    width: 200,
                    render(row) {
                        return row.product?.name;
                    }
                },
                {
                    title: "QTY",
                    key: 'quantity',
                    width: 100,
                },
                {
                    title: "SATUAN",
                    key: 'unit',
                    width: 150,
                },
                {
                    title: "HARGA",
                    key: 'amount',
                    width: 150,
                    render(row) {
                        return formatRupiah(row.amount ?? 0);
                    }
                },
                {
                    title: "TOTAL HARGA",
                    key: 'total',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total_price ?? 0);
                    }
                },
                {
                    title: "TOTAL HARGA (INC PPN)",
                    key: 'tax_amount',
                    width: 250,
                    render(row) {
                        return formatRupiah(row.tax_amount ?? 0);
                    }
                },
                {
                    title: "DISCOUNT 1",
                    key: 'discount_1',
                    width: 200,
                    render(row) {
                        return `${row.discount_1} %`
                    }
                },
                {
                    title: "TOTAL DISCOUNT 1",
                    key: 'total_price_discount_1',
                    width: 250,
                    render(row) {
                        return formatRupiah(discounts.value.total_discount_2);
                    }
                },
                {
                    title: "DISCOUNT 2",
                    key: 'discount_2',
                    width: 200,
                    render(row) {
                    }
                },
                {
                    title: "TOTAL DISCOUNT 2",
                    key: 'total_price_discount_2',
                    width: 250,
                    render(row) {
                        return formatRupiah(discounts.value.total_discount_1);
                    }
                },
                {
                    title: "DISCOUNT 3",
                    key: 'discount_3',
                    width: 200,
                    render(row) {
                        return `${row.discount_3} %`
                    }
                },
                {
                    title: "TOTAL DISCOUNT 3",
                    key: 'total_price_discount_3',
                    width: 250,
                    // render(row) {
                    //     return formatRupiah(discounts.value.total_discount_3 ?? 0);
                    // }
                },
                {
                    title: "ACTION",
                    key: 'action',
                    width: 100,
                    render(row, index) {
                        return h(
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
                            { default: () => "Hapus" }
                        )
                    }
                }
            ]
        }

        const form = useForm({
            document_code: customer_order.document_code,
            due_date: customer_order.due_date,
            sub_total: customer_order.sub_total,
            total: customer_order.total,
            term_of_payment: customer_order.term_of_payment,
            tax_amount: customer_order.tax_amount,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_details = ref({
            customer_order_date: customer_order.transaction_details.find((data) => { return data.category === "CO Date" })?.value,
            customer: customer_order.transaction_details.find((data) => { return data.category === "Customer" })?.value,
            legality: customer_order.transaction_details.find((data) => { return data.category === "Legality" })?.value,
            customer_address: customer_order.transaction_details.find((data) => { return data.category === "Customer Address" })?.value,
            salesman: customer_order.transaction_details.find((data) => { return data.category === "Salesman" })?.value,
            transportation_cost: customer_order.transaction_details.find((data) => { return data.category === "Transportation Cost" })?.value,
            cashback: customer_order.transaction_details.find((data) => { return data.category === "Cashback" })?.value,
            unloading_cost: customer_order.transaction_details.find((data) => { return data.category === "Unloading Cost" })?.value,
        });

        const discounts = ref({
            total_discount_1: 0,
            total_discount_2: 0,
            total_discount_3: 0,
        });

        // Reactive reference untuk item transaksi
        const transaction_items = ref(customer_order.transaction_items);

        // Menghitung harga setelah diskon untuk setiap item
        const calculateDiscounts = (item) => {
            let originalPrice = item.amount || 0;
            let total_discount_1 = 0;
            let total_discount_2 = 0;
            let total_discount_3 = 0;

            // Diskon 1
            if (item.discount_1) {
                total_discount_1 = originalPrice * item.discount_1 / 100;
                originalPrice -= total_discount_1; // Kurangi dengan diskon 1
            }

            // Diskon 2
            if (item.discount_2) {
                total_discount_2 = originalPrice * item.discount_2 / 100;
                originalPrice -= total_discount_2; // Kurangi dengan diskon 2
            }

            // Diskon 3
            if (item.discount_3) {
                total_discount_3 = originalPrice * item.discount_3 / 100;
            }

            return originalPrice - total_discount_3; // Harga akhir setelah semua diskon
        };

        // Menggunakan computed untuk mendapatkan data transaksi yang ditambahkan diskon
        const enhancedTransactionItems = computed(() => {
            return transaction_items.value?.map(item => ({
                ...item,
                total_price_discount: calculateDiscounts(item) // Menyimpan total harga setelah diskon
            }));
        });

        const totalPPN = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = form.transaction_items.reduce((total, item) => {
                return total + Number(item.amount ?? 0); // Konversi amount ke number
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
                return total + Number(item.amount ?? 0); // Konversi amount ke number
            }, 0);

            // Menyimpan subtotal ke dalam form
            form.sub_total = total;

            // Mengembalikan subtotal yang diformat
            return formatRupiah(total);
        });
        const totalPrice = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = form.transaction_items.reduce((total, item) => {
                return total + Number(item.amount ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung total harga termasuk PPN 11%
            const totalWithPPN = subtotal + (subtotal * 0.11);

            // Menyimpan total ke dalam form
            form.total = totalWithPPN;

            // Mengembalikan total harga yang diformat
            return formatRupiah(totalWithPPN);
        });

        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        return {
            columns: createColumns(),
            removeProduct,
            formatRupiah,
            enhancedTransactionItems,
            dayjs,
            form,
            transaction_details,
            totalPPN,
            subtotal,
            totalPrice,
            discounts,
        }
    },
    components: {
        TitlePage,
        Head
    }
})
</script>