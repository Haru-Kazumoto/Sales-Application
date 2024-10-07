<template>
    <Head title="Create CO"/>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="CUSTOMER ORDER" />
        <!-- INPUT CO FORM -->
        <div class="card shadow" style="border: none;">
            <!-- INPUT CO -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NOMOR CO</label>
                            <n-input size="large" disabled v-model:value="model.customer_order_number" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL DIBUAT CO</label>
                            <n-input size="large" disabled v-model:value="model.order_created"/>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">NAMA CUSTOMER</label>
                            <n-input size="large" v-model:value="model.customer_name"/>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">LEGALITAS</label>
                            <n-input size="large" v-model:value="model.legality"/>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">ALAMAT CUSTOMER</label>
                            <n-input size="large" v-model:value="model.customer_address" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TERMIN</label>
                            <n-input size="large" v-model:value="model.term" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">TANGGAL JATUH TEMPO</label>
                            <n-input size="large" v-model:value="model.due_date" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">SALESMAN</label>
                            <n-input size="large" v-model:value="model.salesman" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA ANGKUTAN</label>
                            <n-input size="large" v-model:value="model.term" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">CASHBACK</label>
                            <n-input size="large" v-model:value="model.due_date" />
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                        <div class="d-flex flex-column gap-1">
                            <label for="">BIAYA BONGKAR</label>
                            <n-input size="large" v-model:value="model.salesman" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INPUT PRODUCTS -->
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row g-3">
                    <!-- INPUT PRODUCTS FORM -->
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NAMA PRODUK</label>
                        <n-input size="large" v-model:value="productCustomer.product_name"/>
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">QUANTITY</label>
                        <n-input size="large" v-model:value="productCustomer.quantity" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">KEMASAN</label>
                        <n-input size="large" v-model:value="productCustomer.package" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">HARGA PRODUK</label>
                        <n-input size="large" v-model:value="productCustomer.price" />
                    </div>

                    <!-- INPUT DISCOUNT FORM -->
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">DISCOUNT 1</label>
                        <n-input size="large">
                            <template #suffix>
                                %
                            </template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">HARGA</label>
                        <n-input size="large" disabled/>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">DISCOUNT 2</label>
                        <n-input size="large">
                            <template #suffix>
                                %
                            </template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">HARGA</label>
                        <n-input size="large" disabled/>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">DISCOUNT 3</label>
                        <n-input size="large">
                            <template #suffix>
                                %
                            </template>
                        </n-input>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">HARGA</label>
                        <n-input size="large" disabled/>
                    </div>
                </div>
                <n-button type="primary" class="ms-auto">Tambah Produk</n-button>
            </div>
        </div>

        <!-- PRODUCT CHOOSEN LIST -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" />
            </div>
        </div>

        <!-- CALCULATION RESULT -->
        <div class="card shadow border-0 mb-4">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total harga</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex flex-column w-100 justify-content-between mt-2 gap-3">
                    <div class="d-flex justify-content-between">
                        <span>TERM OF PAYMENT</span>
                        <span class="fw-bold">20 HARI</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>JATUH TEMPO</span>
                        <span class="fw-bold">ON WORKING...</span>
                    </div>
                </div>
                <n-button class="my-3 ms-auto" type="primary" @click="handleSubmitCo">SUBMIT CO</n-button>
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton } from 'naive-ui';
import { useForm, usePage,Head } from '@inertiajs/vue3';
import { ProductCustomerOrder } from '../../../types/dto';
import Swal from 'sweetalert2';

interface RowData {
    product_name: string;
    quantity: number;
    package: string;
    price: number;
    discount_1: number;
    total_price_discount_1: number;
    discount_2: number;
    total_price_discount_2: number;
    discount_3: number;
    total_price_discount_3: number;
}

function createColumns(): DataTableColumns<RowData> {
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
            key: 'product_name',
            width: 200,
            render(row) {
                return row.product_name;
            }
        },
        {
            title: "QTY", 
            key: 'quantity',
            width: 100,
            render(row) {
                return row.quantity;
            }
        },
        {
            title: "SATUAN",
            key: 'package',
            width: 100,
            render(row) {
                return row.package;
            }
        },
        {
            title: "HARGA",
            key: 'price',
            width: 150,
            render(row) {
                return row.price;
            }
        },
        {
            title: "TOTAL",
            key: 'total',
            width: 150,
            render(row) {
                return '-';
            }
        },
        {
            title: "TOTAL DISCOUNT 1",
            key: 'total_price_discount_1',
            width: 200,
            render(row) {
                return row.total_price_discount_1;
            }
        },
        {
            title: "TOTAL DISCOUNT 2",
            key: 'total_price_discount_2',
            width: 200,
            render(row) {
                return row.total_price_discount_2;
            }
        },
        {
            title: "TOTAL DISCOUNT 3",
            key: 'total_price_discount_3',
            width: 200,
            render(row) {
                return row.total_price_discount_3;
            }
        },
        {
            title: "ACTION",
            key: 'action',
            width: 100,
            render(row) {
                return h(
                    NButton,
                    {
                        type: 'error',
                        size: 'small',
                        onClick: () => {
                            alert('DELETED???');
                        }
                    }
                )
            }
        }
    ]
}

export default defineComponent({
    setup() {
        const page = usePage();

        const formCO = useForm({
            customer_order_number: page.props.coNumber,
            order_created: page.props.dateNow,
            customer_name: '',
            legality: '',
            customer_address: '',
            term: '',
            due_date: 0,
            salesman: '',
            amount: 0,
            discount_total: 0,
            sub_total: 0,
            total_after_ppn: 0,
            total: 0,
            status_co: '',
            productCustomerOrders: [] as ProductCustomerOrder[],
        });

        const productCustomerOrders = ref({
            product_name: '',
            quantity: 0,
            package: '',
            price: 0,
            discount_1: 0,
            total_price_discount_1: 0,
            discount_2: 0,
            total_price_discount_2: 0,
            discount_3: 0,
            total_price_discount_3: 0,
        });

        function addProduct() {
            formCO.productCustomerOrders.push({
                product_name: productCustomerOrders.value.product_name,
                quantity: productCustomerOrders.value.quantity,
                package: productCustomerOrders.value.package,
                price: productCustomerOrders.value.price,
                discount_1: 0,
                total_price_discount_1: 0,
                discount_2: 0,
                total_price_discount_2: 0,
                discount_3: 0,
                total_price_discount_3: 0
            });
        }

        function removeProduct(index: number) {
            formCO.productCustomerOrders.splice(index, 1);
        }

        function handleSubmitCo() {
            formCO.post(route('sales.create-co.post'), {
                onError(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message,
                    });
                },
                onSuccess() {
                    formCO.reset();
                    formCO.productCustomerOrders.splice(0, formCO.productCustomerOrders.length);

                    Swal.fire({
                        icon: 'success',
                        title: 'CO berhasil dibuat',
                    });
                }
            })
        }

        return {
            columns: createColumns(),
            model: formCO,
            productCustomer: productCustomerOrders,
            addProduct,
            removeProduct,
            handleSubmitCo,
        }
    },
    components: {
        TitlePage,
        Head
    }
})
</script>

<style scoped></style>