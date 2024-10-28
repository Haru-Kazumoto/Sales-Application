<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Surat Jalan" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('warehouse.travel-document'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <!-- row1 -->
                    <div class="col-6 col-lg-4">
                        <label for="">Nomor CO
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" disabled v-model:value="transaction_details.customer_order_number"
                            size="large" />
                    </div>
                    <div class="col-6 col-lg-4">
                        <label for="">Nomor Surat Jalan
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" disabled v-model:value="form.document_code" size="large" />
                    </div>
                    <div class="col-6 col-lg-4">
                        <label for="">Tanggal Surat Jalan
                            <RequiredMark />
                        </label>
                        <n-date-picker disabled value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder=""
                            class="w-100" id="field8" size="large"
                            v-model:value="transaction_details.travel_document_date" />
                    </div>
                    <!-- row2 -->
                    <div class="col-6 col-lg-3">
                        <label for="">Nama Customer
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" disabled v-model:value="transaction_details.customer" size="large" />
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for="">Gudang Pengiriman
                            <RequiredMark />
                        </label>
                        <n-select placeholder="" v-model:value="transaction_details.shipping_warehouse"
                            :options="sendType" size="large" />
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for="">Pengiriman
                            <RequiredMark />
                        </label>
                        <n-select placeholder="" v-model:value="transaction_details.delivery" :options="senderOptions"
                            size="large" />
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for="">Salesman
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" disabled v-model:value="transaction_details.salesman" size="large" />
                    </div>
                    <!-- row3 -->
                    <div class="col-6 col-lg-4">
                        <label for="">Nomor Polisi
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" v-model:value="transaction_details.number_plate" size="large"
                            @input="(value) => transaction_details.number_plate = value.toUpperCase()" />
                    </div>
                    <div class="col-6 col-lg-4">
                        <label for="">Nama Driver
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" v-model:value="transaction_details.driver" size="large" />
                    </div>
                    <div class="col-12 col-lg-4">
                        <label for="">Keterangan
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" v-model:value="form.description" size="large" type="textarea" />
                    </div>
                </div>
            </div>
        </div>

        <!-- LIST PRODUCTS -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns"
                    :data="($page.props.transactions as any).transaction_items" />
            </div>
        </div>

        <n-button type="primary" size="medium" class="ms-auto mb-5" @click="submitForm">Buat Surat
            Jalan</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { DataTableColumns, FormRules, useMessage } from 'naive-ui';
import TitlePage from '../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import { Transactions, TransactionItems, TransactionDetail } from "../../types/model.ts";
import Swal from 'sweetalert2';
import RequiredMark from '../../Components/RequiredMark.vue';

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
            title: "KODE BARANG",
            key: "item_code",
            width: 150,
            render(row) {
                return row.product?.code;
            }
        },
        {
            title: "NAMA BARANG",
            key: "item_name",
            width: 150,
            render(row) {
                return row.product?.name;
            }
        },
        {
            title: "QTY",
            key: "quantity",
            width: 100,
        },
        {
            title: "SATUAN",
            key: "unit",
            width: 100,
        }
    ];
}

export default defineComponent({
    setup() {
        const page = usePage();
        const customer_order = page.props.transactions as Transactions;

        const form = useForm({
            document_code: page.props.document_code as string,
            description: '',
            total: customer_order.total as number,
            sub_total: customer_order.sub_total as number,
            tax_amount: customer_order.tax_amount as number,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
        });

        const transaction_details = ref({
            customer_order_number: customer_order.document_code,
            travel_document_date: new Date(),
            customer: customer_order.transaction_details.find((data) => data.category === "Customer")?.value,
            customer_address: customer_order.transaction_details.find(data => data.category === "Customer Address")?.value,
            npwp: customer_order.transaction_details.find(data => data.category === "NPWP")?.value,
            shipping_warehouse: '',
            warehouse: customer_order.transaction_details.find((data) => data.category === "Warehouse")?.value,
            delivery: '',
            legality: customer_order.transaction_details.find((data) => data.category === "Legality")?.value,
            number_plate: '',
            driver: '',
            salesman: customer_order.transaction_details.find((data) => data.category === "Salesman")?.value,
        });

        const products = ref({
            code: '',
            unit: '',
            name: '',
            transaction_items: [] as TransactionItems[],
        });

        const transaction_items = ref({
            unit: '',
            quantity: null as unknown as number,
        });
        const senderOptions = [
            { label: "DNP Ekspedisi", value: "DNP_EXPEDITION" },
            { label: "DKU Ekspedisi", value: "DKU_EXPEDITION" }
        ]

        const sendType = [
            { label: "DEPO BEKASI", value: "DEPO BEKASI" },
            { label: "Direct", value: "DIRECT" },
            { label: "Direct Depo", value: "DIRECT_DEPO" },
            { label: "Beli DO", value: "DO" },
        ];

        const driverOptions = [
            { label: 'John Doe', value: 'john_doe' },
            { label: 'Jane Smith', value: 'jane_smith' }
        ];

        const columns = createColumns();

        const formRef = ref(null);
        const message = useMessage();

        function  submitForm() {
            customer_order.transaction_items?.forEach((item) => {
                form.transaction_items.push({
                    unit: item.unit,       // Asumsikan item memiliki properti 'unit'
                    quantity: item.quantity,  // Asumsikan item memiliki properti 'quantity'
                    product_id: item.product?.id,
                    amount: item.amount,
                    product: {
                        name: item.product?.name as string,
                        unit: item.product?.unit as string,
                        code: item.product?.code as string,
                    }
                });
            });

            form.transaction_details = [
                {
                    name: "Nomor CO",
                    category: "CO Number",
                    value: transaction_details.value.customer_order_number,
                    data_type: 'float',
                },
                {
                    name: "Tanggal Surat Jalan",
                    category: 'Travel Document Date',
                    value: transaction_details.value.travel_document_date as any,
                    data_type: 'datetime',
                },
                {
                    name: 'Nama Customer',
                    category: 'Customer',
                    value: transaction_details.value.customer,
                    data_type: 'string',
                },
                {
                    name: "Gudang Pengiriman",
                    category: "Shipping Warehouse",
                    value: transaction_details.value.shipping_warehouse,
                    data_type: 'string',
                },
                {
                    name: "Pengiriman",
                    category: "Delivery",
                    value: transaction_details.value.delivery,
                    data_type: 'string',
                },
                {
                    name: "Nomor Polisi",
                    category: "Number Plate",
                    value: transaction_details.value.number_plate,
                    data_type: 'string',
                },
                {
                    name: "Nama Driver",
                    category: "Driver",
                    value: transaction_details.value.driver,
                    data_type: 'string',
                },
                {
                    name: "Salesman",
                    category: "Salesman",
                    value: transaction_details.value.salesman,
                    data_type: 'string',
                },
                {
                    name: "Legalitas",
                    category: "Legality",
                    value: transaction_details.value.legality,
                    data_type: 'string',
                },
                {
                    name: "Gudang",
                    category: "Warehouse",
                    value: transaction_details.value.warehouse,
                    data_type: 'string',
                },
                {
                    name: "NPWP",
                    category: "NPWP",
                    value: transaction_details.value.npwp || '-',
                    data_type: "string",
                },
                {
                    name: 'Alamat Customer',
                    category: 'Customer Address',
                    value: transaction_details.value.customer_address,
                    data_type: 'string',
                },
            ];

            form.post(route('warehouse.travel-document.post'), {
                onSuccess: () => {
                    form.reset();
                    transaction_details.value = {
                        customer_order_number: customer_order.document_code,
                        travel_document_date: new Date(),
                        customer: customer_order.transaction_details.find((data) => data.category === "Customer")?.value,
                        shipping_warehouse: '',
                        delivery: '',
                        number_plate: '',
                        driver: '',
                        salesman: '',
                        legality: '',
                        npwp: '',
                        customer_address: '',
                        warehouse: customer_order.transaction_details.find((data) => data.category === "Warehouse")?.value,
                    };

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Submit Surat Jalan',
                    });
                },
                onError: (error) => {
                    Swal.fire('Cek kembali form', 'Form dengan tanda <span class="text-danger fw-bold">*</span> Harus diisi!', 'error');
                }
            })
        };

        return {
            form,
            formRef,
            senderOptions,
            sendType,
            driverOptions,
            columns,
            submitForm,
            ArrowBack,
            router,
            transaction_details,
            products,
            transaction_items,
        };
    },
    components: {
        TitlePage,
        RequiredMark
    }
});
</script>
