<template>

    <Head title="Create CO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="CUSTOMER ORDER" />
        <!-- INPUT CO FORM -->
        <div class="card shadow" style="border: none;">
            <!-- INPUT CO -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR CO</label>
                            <n-input disabled size="large" v-model:value="form.document_code" placeholder="" />
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL DIBUAT CO</label>
                            <n-input disabled size="large" v-model:value="transaction_details.customer_order_date"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NAMA CUSTOMER</label>
                            <n-input disabled filterable size="large" placeholder=""
                                v-model:value="transaction_details.customer" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BADAN USAHA</label>
                            <n-input disabled size="large" v-model:value="transaction_details.legality"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">ALAMAT CUSTOMER</label>
                            <n-input disabled size="large" v-model:value="transaction_details.customer_address"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TERMIN</label>
                            <n-input disabled size="large" v-model:value="form.term_of_payment" placeholder="">
                                <template #suffix>HARI</template>
                            </n-input disabled>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL JATUH TEMPO</label>
                            <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder="" id="field8"
                                size="large" v-model:formatted-value="form.due_date" disabled />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">PENGIRIMAN
                                <RequiredMark />
                            </label>
                            <n-input disabled size="large" v-model:value="transaction_details.delivery"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">SALESMAN</label>
                            <n-input disabled size="large" v-model:value="transaction_details.salesman"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">SEGMEN CUSTOMER</label>
                        <n-input disabled size="large" v-model:value="transaction_details.segment_customer"
                            placeholder="" />
                    </div>

                    <!-- <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA ANGKUTAN<RequiredMark /></label>
                            <n-input disabled size="large" placeholder="" v-model:value="transaction_details.transportation_cost"
                                @input="(value) => transaction_details.transportation_cost = value.replace(/\D/g, '')">
                                <template #prefix>
                                    Rp
                                </template>
                            </n-input disabled>
                        </div>
                    </div> -->
                    <n-divider title-placement="left">OPSIONAL</n-divider>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR PO CUSTOMER </label>
                            <n-input disabled size="large" v-model:value="transaction_details.po_customer"
                                placeholder="" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">CASHBACK + PPH 4%</label>
                            <n-input disabled size="large" v-model:value="transaction_details.cashback" placeholder=""
                                @input="(value) => transaction_details.cashback = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input disabled>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA BONGKAR</label>
                            <n-input disabled size="large" v-model:value="transaction_details.unloading_cost"
                                placeholder=""
                                @input="(value) => transaction_details.unloading_cost = value.replace(/\D/g, '')">
                                <template #prefix>Rp </template>
                            </n-input disabled>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="ppn">PPN</label>
                            <n-input disabled size="large" placeholder="" v-model:value="transaction_details.use_tax" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="ppn">Pengajuan Diskon</label>
                            <n-input disabled size="large" placeholder=""
                                v-model:value="transaction_details.submission_discount" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCT CHOOSEN LIST -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="transaction_items" />
            </div>
        </div>

        <n-modal v-model:show="showModal" :mask-closable="false" class="d-flex" preset="card" :style="bodyStyle"
            title="HARGA DISKON BARANG" :bordered="false" size="huge" :segmented="segmented">
            
            <div class="row g-3">
                <div class="col-12 d-flex flex-column gap-1">
                    <label for="newDiscount">QUANTITY BARANG</label>
                    <n-input size="large" placeholder="" v-model:value="setDiscounts.quantity" disabled/>
                </div>
                <div class="col-12 d-flex flex-column gap-1">
                    <label for="newDiscount">HARGA DISKON</label>
                    <n-input size="large" placeholder="" v-model:value="setDiscounts.newAmount" @input="(value) => setDiscounts.newAmount = value.replace(/\D/g, '')">
                        <template #prefix>
                            Rp
                        </template>
                    </n-input>
                </div>
                <div class="col-12 d-flex flex-column gap-1">
                    <label for="newDiscount">HARGA DISKON</label>
                    <n-input size="large" placeholder="" v-model:value="setDiscounts.newTotalPrice" disabled>
                        <template #prefix>
                            Rp
                        </template>
                    </n-input>
                </div>
            </div>

            <template #footer>
                <div class="d-flex">
                    <div class="d-flex gap-2 ms-auto">
                        <n-button type="error" size="large" @click="showModal = false">Batal</n-button>
                        <n-button type="info" size="large" @click="handleSubmitDiscount(setDiscounts.customer_order_id)">Simpan</n-button>
                    </div>
                </div>
            </template>
        </n-modal>

        <!-- CALCULATION RESULT -->
        <div class="card shadow border-0 mb-3">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>{{ formatRupiah(($page.props.customer_order as Transactions).sub_total ?? 0) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2" v-if="transaction_details.use_tax === 'PPN'">
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
                        <span class="fw-bold">{{ form.term_of_payment }} HARI</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>JATUH TEMPO</span>
                        <span class="fw-bold">{{ form.due_date ? dayjs(form.due_date).format('dddd, D MMMM YYYY') : ''
                            }}</span>
                    </div>
                    <div class="d-flex justify-content-between" v-if="customer_order.status !== null">
                        <span>STATUS PENGAJUAN CO</span>
                        <div v-if="customer_order.status === 'APPROVE'">
                            <n-tag type="success" size="large" bordered="true" strong="true">DIAPPROVE</n-tag>
                        </div>
                        <div v-else-if="customer_order.status === 'REJECT'">
                            <n-tag type="error" size="large" bordered="true" strong="true">DITOLAK</n-tag>
                        </div>
                        <div v-else-if="customer_order.status === 'HOLD'">
                            <n-tag type="warning" size="large" bordered="true" strong="true">DITAHAN</n-tag>
                        </div>
                        <div v-else>
                            <n-tag type="info" size="large" bordered="true" strong="true">BELUM DIPROSES</n-tag>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between" v-if="transaction_details.submission_discount === 'SUBMIT'">
                        <span>STATUS PENGAJUAN DISKON</span>
                        <div v-if="transaction_details.submission_status === 'true'">
                            <n-tag type="success" size="large" bordered="true" strong="true">DIAPPROVE</n-tag>
                        </div>
                        <div v-else-if="transaction_details.submission_status === 'false'">
                            <n-tag type="error" size="large" bordered="true" strong="true">DITOLAK</n-tag>
                        </div>
                        <div v-else>
                            <n-tag type="warning" size="large" bordered="true" strong="true">BELUM DIPROSES</n-tag>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 ms-auto mb-4"
            v-if="($page.props.auth as any).user.division.division_name === 'AGING_FINANCE' && customer_order.status === 'PENDING'">
            <n-button type="error" size="large" @click="handleApprovingCO('REJECT')">REJECT</n-button>
            <n-button type="warning" size="large" @click="handleApprovingCO('HOLD')">HOLD</n-button>
            <n-button type="primary" size="large" @click="handleApprovingCO('APPROVE')">APPROVE</n-button>
        </div>
        <div class="d-flex gap-3 ms-auto mb-4"
            v-if="($page.props.auth as any).user.division.division_name === 'MARKETING'">
            <n-button type="error" size="large" @click="handleProcessCO('false')">REJECT</n-button>
            <n-button type="primary" size="large" @click="handleProcessCO('true')">APPROVE</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, h, ref, watch } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, SelectOption, useNotification } from 'naive-ui';
import { useForm, usePage, Head, router } from '@inertiajs/vue3';
import { ProductCustomerOrder } from '../../../types/dto';
import Swal from 'sweetalert2';
import { Flash, Lookup, Parties, Products, TransactionDetail, TransactionItems, Transactions, User } from '../../../types/model';
import { formatRupiah } from '../../../Utils/options-input.utils';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();
        const customer_order = page.props.customer_order as Transactions;
        const showModal = ref(false);
        const selectedItem = ref(null);
        const selectedProduct = ref({}); // Menyimpan data produk yang dipilih
        const newQuantity = ref(null);

        function openModal(item) {
            selectedItem.value = item; // Simpan data item ke selectedItem
            showModal.value = true;    // Tampilkan modal
        }

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
                    width: 300,
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

                // {
                //     title: "DISCOUNT 1",
                //     key: 'discount_1',
                //     width: 200,
                //     render(row) {
                //         return `${row.discount_1} %`
                //     }
                // },
                // {
                //     title: "HARGA DISCOUNT 1",
                //     key: 'total_price_discount_1',
                //     width: 250,
                //     render(row) {
                //         // Menghitung harga setelah diskon 1
                //         const amount = row.amount ?? 0;
                //         const discount_1 = row.discount_1 ?? 0;
                //         const priceAfterDiscount1 = amount - (amount * discount_1 / 100);

                //         // Mengembalikan harga setelah diskon 1
                //         return formatRupiah(priceAfterDiscount1 ?? 0);
                //     }
                // },

                // {
                //     title: "DISCOUNT 2",
                //     key: 'discount_2',
                //     width: 200,
                //     render(row) {
                //         return `${row.discount_2} %`
                //     }
                // },
                // {
                //     title: "HARGA DISCOUNT 2",
                //     key: 'total_price_discount_2',
                //     width: 250,
                //     render(row) {
                //         // Menghitung harga setelah diskon 2
                //         const amount = row.amount ?? 0;
                //         const discount_1 = row.discount_1 ?? 0;
                //         const discount_2 = row.discount_2 ?? 0;
                //         const priceAfterDiscount1 = amount - (amount * discount_1 / 100);
                //         const priceAfterDiscount2 = priceAfterDiscount1 - (priceAfterDiscount1 * discount_2 / 100);

                //         // Mengembalikan harga setelah diskon 2
                //         return formatRupiah(priceAfterDiscount2 ?? 0);
                //     }
                // },
                // {
                //     title: "DISCOUNT 3",
                //     key: 'discount_3',
                //     width: 200,
                //     render(row) {
                //         return `${row.discount_3} %`
                //     }
                // },

                // {
                //     title: "HARGA DISCOUNT 3",
                //     key: 'total_price_discount_3',
                //     width: 250,
                //     render(row) {
                //         // Menghitung harga setelah diskon 3
                //         const amount = row.amount ?? 0;
                //         const discount_1 = row.discount_1 ?? 0;
                //         const discount_2 = row.discount_2 ?? 0;
                //         const discount_3 = row.discount_3 ?? 0;
                //         const priceAfterDiscount1 = amount - (amount * discount_1 / 100);
                //         const priceAfterDiscount2 = priceAfterDiscount1 - (priceAfterDiscount1 * discount_2 / 100);
                //         const priceAfterDiscount3 = priceAfterDiscount2 - (priceAfterDiscount2 * discount_3 / 100);

                //         // Mengembalikan harga setelah diskon 3
                //         return formatRupiah(priceAfterDiscount3 ?? 0);
                //     }
                // },
                {
                    title: "TOTAL HARGA",
                    key: 'total',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total_price ?? 0);
                    }
                },
                {
                    title: "ACTION",
                    key: 'action',
                    width: 100,
                    render(row, index) {
                        if (transaction_details.value.submission_status !== 'true') {
                            return null;
                        } else {
                            return h(
                                NButton,
                                {
                                    type: 'info',
                                    size: 'small',
                                    onClick: () => {
                                        handleOpenDiscountModal(row);
                                    }
                                },
                                { default: () => "ISI HARGA DISKON" }
                            )
                        }
                    }
                }
            ]
        }



        function reStoreStockProduct() {
            // router.post(route('sales.restore-products', (selectedProduct.value as any).id), {
            //     quantity: newQuantity.value,
            //     amount: (selectedProduct.value as any).amount,
            //     allocation: customer_order.transaction_details.find((data) => { return data.category === "Warehouse" })?.value, // Ganti dengan nama gudang yang sesuai
            // }, {
            //     onSuccess: (page) => {
            //         showModal.value = false; // Tutup modal
            //         Swal.fire((page.props.flash as Flash).success, '', 'success');
            //     },
            //     onError: (errors) => {
            //         notification.error({
            //             title: 'Error',
            //             content: 'Gagal mengembalikan barang ke gudang'
            //         });
            //         console.error(errors);
            //     }
            // });
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
            use_tax: customer_order.transaction_details.find((data) => { return data.category === "Taxes" })?.value === "true"
                ? "PPN"
                : "NON-PPN",
            segment_customer: customer_order.transaction_details.find((data) => { return data.category === "SEGMENT" })?.value,
            submission_discount: customer_order.transaction_details.find((data) => { return data.category === "Discount Submission" })?.value,
            submission_status: customer_order.transaction_details.find((data) => { return data.category === "Submission Status" })?.value,
            delivery: customer_order.transaction_details.find((data) => { return data.category === "Delivery" })?.value,
            po_customer: customer_order.transaction_details.find((data) => { return data.category === "PO Customer" })?.value
        });

        const discounts = ref({
            total_discount_1: 0,
            total_discount_2: 0,
            total_discount_3: 0,
        });

        const setDiscounts = useForm({
            customer_order_id: null as unknown as number,
            newAmount: null as unknown as number,
            newTotalPrice: null as unknown as number,
            quantity: null as unknown as number,
        });

        // Reactive reference untuk item transaksi
        const transaction_items = ref(customer_order.transaction_items);

        // // Menghitung harga setelah diskon untuk setiap item
        // const calculateDiscounts = (item) => {
        //     let originalPrice = item.amount || 0;
        //     let total_discount_1 = 0;
        //     let total_discount_2 = 0;
        //     let total_discount_3 = 0;

        //     // Diskon 1
        //     if (item.discount_1) {
        //         total_discount_1 = originalPrice * item.discount_1 / 100;
        //         originalPrice -= total_discount_1; // Kurangi dengan diskon 1
        //     }

        //     // Diskon 2
        //     if (item.discount_2) {
        //         total_discount_2 = originalPrice * item.discount_2 / 100;
        //         originalPrice -= total_discount_2; // Kurangi dengan diskon 2
        //     }

        //     // Diskon 3
        //     if (item.discount_3) {
        //         total_discount_3 = originalPrice * item.discount_3 / 100;
        //     }

        //     return originalPrice - total_discount_3; // Harga akhir setelah semua diskon
        // };

        // // Menggunakan computed untuk mendapatkan data transaksi yang ditambahkan diskon
        // const enhancedTransactionItems = computed(() => {
        //     return transaction_items.value?.map(item => ({
        //         ...item,
        //         total_price_discount: calculateDiscounts(item) // Menyimpan total harga setelah diskon
        //     }));
        // });

        function handleOpenDiscountModal(row: any) {
            showModal.value = true;

            setDiscounts.customer_order_id = row.id;
            setDiscounts.newAmount = row.amount;
            setDiscounts.newTotalPrice = row.total_price;
            setDiscounts.quantity = row.quantity;
        }

        //create watch for new discounts
        watch(() => setDiscounts.newAmount, (amount) => {
            setDiscounts.newTotalPrice = amount * setDiscounts.quantity;
        }, {deep: true});

        function handleSubmitDiscount(co_id: number) {
            setDiscounts.patch(route('sales.new-discount', co_id), {
                onSuccess: (page) => {
                    router.reload();
                    showModal.value = false;
                    Swal.fire({
                        icon: "success",
                        title: page.props.flash.success,
                        timer: 2000,
                    });
                    setDiscounts.reset();
                },
                onError: () => {
                    showModal.value = false;
                    Swal.fire({
                        icon: 'error',
                        title: "Oops, server sedang sibuk :(",
                        text: "Silahkan realod page atau hubungi developer segera",
                        timer: 3000,
                    });
                }
            });
        }

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
            return ppn;
        });

        const subtotal = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const total = form.transaction_items.reduce((total, item) => {
                return total + Number(item.amount ?? 0); // Konversi amount ke number
            }, 0);

            // Menyimpan subtotal ke dalam form
            form.sub_total = total;

            // Mengembalikan subtotal yang diformat
            return total;
        });
        const totalPrice = computed(() => {
            // Menghitung subtotal dari semua produk tanpa mengalikan quantity
            const subtotal = form.transaction_items.reduce((total, item) => {
                return total + Number(item.amount ?? 0); // Konversi amount ke number
            }, 0);

            // Menghitung total harga termasuk PPN 11%
            let total = null as unknown as number;

            if (transaction_details.value.use_tax === "PPN") {
                total = subtotal + (subtotal * 0.11);
            } else {
                total = subtotal;
            }

            // Menyimpan total ke dalam form
            form.total = total;

            // Mengembalikan total harga yang diformat
            return total;
        });

        function removeProduct(index: number) {
            form.transaction_items.splice(index, 1);
        }

        function handleApprovingCO(valueRequest: string) {
            router.patch(route('aging-finance.co.process.patch', customer_order.id), {
                valueRequest
            }, {
                onSuccess: (page) => {
                    Swal.fire({
                        icon: "success",
                        title: page.props.flash.success,
                        text: "Data CO telah diperbarui",
                        timer: 2500,
                    });
                },
                onError: () => {
                    Swal.fire({
                        icon: "error",
                        title: "Oops, server sedang sibuk :(",
                        text: "Tunggu beberapa saat atau hubungi developer",
                        timer: 3000,
                    });
                }
            })
        }

        //for marketing processing draf co
        function handleProcessCO(valueRequest: string) {
            Swal.fire({
                icon: "question",
                title: "Approve pengajuan diskon di CO ini?",
                showCancelButton: true,
                cancelButtonColor: "red",
            }).then((result) => {
                if (result.isConfirmed) {
                    router.patch(route('marketing.draf-co.process', customer_order.id), {
                        valueRequest
                    }, {
                        onSuccess: (page) => {
                            Swal.fire(page.props.flash.success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire({
                                icon: "error",
                                title: "Oops, server sibuk :(",
                                text: "Silahkan lapor admin atau developer",
                            });
                        }
                    });
                }
            });
        }

        return {
            columns: createColumns(),
            handleOpenDiscountModal,
            handleApprovingCO,
            handleSubmitDiscount,
            handleProcessCO,
            removeProduct,
            formatRupiah,
            reStoreStockProduct,
            transaction_items,
            dayjs,
            form,
            transaction_details,
            totalPPN,
            subtotal,
            totalPrice,
            discounts,
            showModal,
            selectedItem,
            customer_order,
            selectedProduct,
            setDiscounts,
            newQuantity,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
        }
    },
    components: {
        TitlePage,
        Head
    }
})
</script>