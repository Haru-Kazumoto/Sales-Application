<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-1">
            <TitlePage title="Form Klaim" />
            <n-button text class="justify-content-start" @click="router.visit(route('finance.claim.index'))">
                <template #icon>
                    <n-icon :component="ArrowBackOutline" />
                </template>
                Kembali
            </n-button>
        </div>
        <div class="card shadow" style="border: none">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="claim promo number">Nomor Klaim Promo</label>
                        <n-input placeholder="" size="large" id="claim promo number"
                            v-model:value="form.document_code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="month">Bulan</label>
                        <n-select placeholder="" size="large" id="month" v-model:value="form.month" :options="months" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="distributor name">Program</label>
                        <n-input placeholder="" size="large" id="distributor name" v-model:value="form.program" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                        <label for="distributor name">Area</label>
                        <n-input placeholder="" size="large" id="distributor name" v-model:value="form.area" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-8 d-flex flex-column gap-2">
                        <label for="distributor name">Nama Distributor</label>
                        <n-input placeholder="" size="large" id="distributor name" v-model:value="form.distributor" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow " style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :data="form.transaction_items" :columns="columns" size="small" />
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex">
                    <span class="fw-bold">SUB TOTAL</span>
                    <span class="ms-auto">{{ subTotal }}</span>
                </div>
                <div style="width: 100%; border: 1px solid grey;" />
                <div class="d-flex">
                    <span class="fw-bold">GRAND TOTAL</span>
                    <span class="ms-auto">{{ totalPrice }}</span>
                </div>
            </div>
        </div>
        <n-button type="primary" class="mb-4 ms-auto w-25" @click="handleSubmitClaim">Submit</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { DataTableColumns, NButton } from 'naive-ui';
import { ArrowBackOutline } from "@vicons/ionicons5";
import { router, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

function createColumns() {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1; // Untuk memberikan nomor urut baris
            },
        },
        {
            title: "TANGGAL CO",
            key: 'co_date',
            width: 200,
            render(row) {
                const date = row.transaction.transaction_details.find(data => data.category === "CO Date")?.value;
                return dayjs(date).format('dddd, D MMMM YYYY HH:mm')
            }
        },
        {
            title: "NOMOR CO",
            key: 'document_code',
            width: 160,
            render(row) {
                return row.transaction.document_code;
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: 'customer_name',
            width: 200,
            render(row) {
                return row.transaction.transaction_details.find(data => data.category === "Customer")?.value;
            }
        },
        {
            title: "NAMA BARANG",
            key: 'item_name',
            width: 250,
            render(row) {
                return row.product.name;
            }
        },
        {
            title: "SATUAN",
            key: 'package',
            width: 100,
            render(row) {
                return row.product.unit;
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
            title: "HARGA",
            key: 'price',
            width: 200,
            render(row) {
                return formatRupiah(row.amount);
            }
        },
        {
            title: "TOTAL",
            key: 'total',
            width: 200,
            render(row) {
                return formatRupiah(row.total_price);
            }
        },
        {
            title: "DISKON LOKAL",
            key: 'discount_1',
            width: 150,
            render(row) {
                return `${row.discount_1}%`;
            }
        },
        {
            title: "TOTAL DISKON LOKAL",
            key: 'total_discount_1',
            width: 200,
            render(row) {
                const discountAmount1 = row.total_price * (row.discount_1 / 100);
                return formatRupiah(discountAmount1);
            }
        },
        {
            title: "DISKON WS",
            key: 'discount_2',
            width: 150,
            render(row) {
                return `${row.discount_2}%`;
            }
        },
        {
            title: "TOTAL DISKON 2",
            key: 'total_discount_2',
            width: 200,
            render(row) {
                const discountAmount1 = row.total_price * (row.discount_1 / 100);
                const remainingAfterDiscount1 = row.total_price - discountAmount1;
                const discountAmount2 = remainingAfterDiscount1 * (row.discount_2 / 100);
                return formatRupiah(discountAmount2);
            }
        },
        {
            title: "DISKON ED",
            key: 'discount_3',
            width: 150,
            render(row) {
                return `${row.discount_3}%`;
            }
        },
        {
            title: "TOTAL DISKON 3",
            key: 'total_price_discount_3',
            width: 200,
            render(row) {
                const discountAmount1 = row.total_price * (row.discount_1 / 100);
                const remainingAfterDiscount1 = row.total_price - discountAmount1;
                const discountAmount2 = remainingAfterDiscount1 * (row.discount_2 / 100);
                const remainingAfterDiscount2 = remainingAfterDiscount1 - discountAmount2;
                const discountAmount3 = remainingAfterDiscount2 * (row.discount_3 / 100);
                return formatRupiah(discountAmount3);
            }
        },
        {
            title: "TOTAL AKHIR",
            key: 'total_price_all',
            width: 200,
            render(row) {
                const discountAmount1 = row.total_price * (row.discount_1 / 100);
                const remainingAfterDiscount1 = row.total_price - discountAmount1;
                const discountAmount2 = remainingAfterDiscount1 * (row.discount_2 / 100);
                const remainingAfterDiscount2 = remainingAfterDiscount1 - discountAmount2;
                const discountAmount3 = remainingAfterDiscount2 * (row.discount_3 / 100);
                const finalTotal = remainingAfterDiscount2 - discountAmount3;
                return formatRupiah(finalTotal);
            }
        },
        // {
        //     title: 'ACTION',
        //     key: 'actions',
        //     width: 150,
        //     render(row) {
        //         return h('div', { class: 'd-flex gap-2' }, [
        //             h(
        //                 NButton,
        //                 {
        //                     type: 'error',
        //                     size: 'small',
        //                     onClick: () => {
        //                         alert(`${row.key} is selected!`);
        //                     }
        //                 },
        //                 { default: () => 'Delete' }
        //             )
        //         ]);
        //     }
        // }
    ];
}

export default defineComponent({
    setup() {
        const page = usePage();

        const form = useForm({
            document_code: page.props.document_code,
            month: '',
            program: '',
            area: '',
            distributor: '',
            sub_total: null as unknown as number,
            total: null as unknown as number,
            transaction_items: page.props.choosen_datas as any[],
            transaction_details: [] as any[]
        });

        const subTotal = computed(() => {
            const total = form.transaction_items.reduce((total, item) => {
                return total + Number(item.total_price ?? 0); // Konversi amount ke number
            }, 0);

            form.sub_total = total;

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
            const roundedTotalWithPpn = Math.round(totalWithPPN);

            form.total = roundedTotalWithPpn;

            // Mengembalikan total harga yang diformat
            // return roundedTotalWithPpn;
            return formatRupiah(totalWithPPN);
        });

        function handleSubmitClaim() {
            form.transaction_details = [
                {
                    name: "Pembayaran Promo",
                    category: "Claim Payment",
                    value: "false",
                    data_type: 'boolean',
                },
                {
                    name: "Bulan Klaim",
                    category: "Month",
                    value: form.month,
                    data_type: 'string',
                },
                {
                    name: "Program Klaim",
                    category: "Program",
                    value: form.program,
                    data_type: "string",
                },
                {
                    name: "Area",
                    category: "Area",
                    value: form.area,
                    data_type: 'string',
                },
                {
                    name: "Nama Distributor",
                    category: "Distributor",
                    value: form.distributor,
                    data_type: 'string',
                },
            ];

            form.post(route('finance.claim.post'), {
                onSuccess: (page) => {
                    Swal.fire((page.props.flash as any).success, '', 'success');
                }
            });
        }

        const months = [
            { label: "JANUARI", value: "JANUARI" },
            { label: "FEBRUARI", value: "FEBRUARI" },
            { label: "MARET", value: "MARET" },
            { label: "APRIL", value: "APRIL" },
            { label: "MEI", value: "MEI" },
            { label: "JUNI", value: "JUNI" },
            { label: "JULI", value: "JULI" },
            { label: "AGUSTUS", value: "AGUSTUS" },
            { label: "SEPTEMBER", value: "SEPTEMBER" },
            { label: "OKTOBER", value: "OKTOBER" },
            { label: "NOVEMBER", value: "NOVEMBER" },
            { label: "DESEMBER", value: "DESEMBER" }
        ];

        return {
            columns: createColumns(),
            handleSubmitClaim,
            ArrowBackOutline,
            router,
            months,
            form,
            subTotal,
            totalPrice,
        }
    },
    components: {
        TitlePage,
    }
})
</script>