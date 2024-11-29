<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Surat Jalan" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('procurement.do.index'), { method: 'get' })">
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
                        <n-input readonly placeholder="" v-model:value="transaction_details.customer_order_number"
                            size="large" />
                    </div>
                    <div class="col-6 col-lg-4">
                        <label for="">Nomor Surat Jalan
                            <RequiredMark />
                        </label>
                        <n-input readonly placeholder="" v-model:value="form.document_code" size="large"
                            @input="(value) => form.document_code = value.toUpperCase()" />
                    </div>
                    <div class="col-6 col-lg-4">
                        <label for="">Tanggal Surat Jalan
                            <RequiredMark />
                        </label>
                        <n-date-picker readonly value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder=""
                            class="w-100" id="field8" size="large"
                            v-model:value="transaction_details.travel_document_date" />
                    </div>
                    <!-- row2 -->
                    <div class="col-6 col-lg-3">
                        <label for="">Nama Customer
                            <RequiredMark />
                        </label>
                        <n-input readonly placeholder="" v-model:value="transaction_details.customer" size="large" />
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for=""> Pengiriman
                            <RequiredMark />
                        </label>
                        <n-input readonly placeholder="" v-model:value="transaction_details.shipping_warehouse"
                            size="large" />
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for="">Nama Ekspedisi
                            <RequiredMark />
                        </label>
                        <n-select placeholder="" v-model:value="transaction_details.delivery" filterable
                            :options="transportOptions" size="large" />
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for="">Salesman
                            <RequiredMark />
                        </label>
                        <n-input readonly placeholder="" v-model:value="transaction_details.salesman" size="large" />
                    </div>
                    <!-- row3 -->
                    <div class="col-6 col-lg-4">
                        <label for="">Nomor Polisi
                            <RequiredMark />
                        </label>
                        <n-input readonly placeholder="" v-model:value="transaction_details.number_plate" size="large"
                            @input="(value) => transaction_details.number_plate = value.toUpperCase()" />
                    </div>
                    <div class="col-6 col-lg-4">
                        <label for="">Nama Driver
                            <RequiredMark />
                        </label>
                        <n-input readonly placeholder="" v-model:value="transaction_details.driver" size="large" />
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button type="info" size="large" @click="handleOverviewFile">Lihat file surat jalan</n-button>
                </div>
            </div>
        </div>

        <!-- LIST PRODUCTS -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns"
                    :data="($page.props.travel_document as any).transaction_items" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { DataTableColumns, FormRules, useMessage } from 'naive-ui';
import TitlePage from '../../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import { Transactions, TransactionItems, TransactionDetail, Parties } from "../../../types/model.ts";
import Swal from 'sweetalert2';
import RequiredMark from '../../../Components/RequiredMark.vue';
import { formatRupiah } from '../../../Utils/options-input.utils.ts';

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
        },
        {
            title: "HARGA BARANG",
            key: "amount",
            width: 200,
            render(row) {
                return formatRupiah(row.amount ?? 0);
            }
        },
        {
            title: "TOTAL HARGA (INC PPN)",
            key: 'total_price',
            width: 200,
            render(row) {
                return formatRupiah(row.total_price ?? 0);
            }
        }
    ];
}

export default defineComponent({
    setup() {
        const page = usePage();
        const travel_document = page.props.travel_document as Transactions;
        console.log(travel_document)

        const form = useForm({
            document_code: travel_document.document_code,
            term_of_payment: travel_document.term_of_payment,
            due_date: travel_document.due_date,
            total: travel_document.total as number,
            sub_total: travel_document.sub_total as number,
            tax_amount: travel_document.tax_amount as number,
            transaction_details: [] as TransactionDetail[],
            transaction_items: [] as TransactionItems[],
            file_attachment: null as unknown as any
        });

        const transaction_details = ref({
            customer_order_number: travel_document.document_code,
            travel_document_date: new Date(),
            customer: travel_document.transaction_details.find((data) => data.category === "Customer")?.value,
            customer_address: travel_document.transaction_details.find(data => data.category === "Customer Address")?.value,
            npwp: travel_document.transaction_details.find(data => data.category === "NPWP")?.value,
            shipping_warehouse: travel_document.transaction_details.find(data => data.category === "Shipping Warehouse")?.value,
            warehouse: travel_document.transaction_details.find((data) => data.category === "Warehouse")?.value,
            delivery: travel_document.transaction_details.find(data => data.category === "Delivery")?.value,
            legality: travel_document.transaction_details.find((data) => data.category === "Legality")?.value,
            number_plate: travel_document.transaction_details.find(data => data.category === "Number Plate")?.value,
            driver: travel_document.transaction_details.find(data => data.category === "Driver")?.value,
            salesman: travel_document.transaction_details.find((data) => data.category === "Salesman")?.value,
        });

        function handleOverviewFile() {
            if (travel_document.file_attachment) {
                window.open(travel_document.file_attachment, '_blank');
            } else {
                alert('No file attached');
            }
        }

        const products = ref({
            code: '',
            unit: '',
            name: '',
            transaction_items: [] as TransactionItems[],
        });

        const columns = createColumns();

        const formRef = ref(null);

        return {
            form,
            formRef,
            columns,
            ArrowBack,
            router,
            transaction_details,
            products,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            showModal: ref(false),
            handleOverviewFile
        };
    },
    components: {
        TitlePage,
        RequiredMark
    }
});
</script>
