<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Klaim Promo" />
        <div class="d-flex flex-column">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                        Buat {{ checkedRowKeys.length }} data </span>
                    <n-data-table :columns="columns" :data="($page.props.claims as any).data" :bordered="false"
                        :pagination="pagination" :row-key="rowKey" @update:checked-row-keys="handleCheck"
                        size="small" />
                    <n-button type="primary" class="ms-auto my-3" @click="handleSendReminder">Buat Claim</n-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { NButton, useNotification, type DataTableColumns, type DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';
import { router, useForm, usePage } from "@inertiajs/vue3";
import { formatRupiah } from '../../../Utils/options-input.utils';
import { Flash } from '../../../types/model';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

function createColumns() {
    return [
        {
            type: 'selection',
        },
        {
            title: '#',
            key: 'index',
            width: 60,
            render(rowData, rowIndex) {
                return rowIndex + 1;
            },
        },
        {
            title: "TANGGAL CO",
            key: 'co_date',
            width: 250,
            render(row){
                return dayjs(row.co_date).format('dddd, D MMMM YYYY HH:mm')
            }
        },
        {
            title: "NOMOR CO",
            key: 'document_code',
            width: 200,
        },
        {
            title: "NAMA CUSTOMER",
            key: 'customer',
            width: 200,
        },
        {
            title: "NAMA BARANG",
            key: 'product_name',
            width: 250,
        },
        {
            title: "SATUAN",
            key: 'unit',
            width: 100,
        },
        {
            title: "QUANTITY",
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
                return formatRupiah(row.total);
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
                const discountAmount1 = row.total * (row.discount_1 / 100);
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
                const discountAmount1 = row.total * (row.discount_1 / 100);
                const remainingAfterDiscount1 = row.total - discountAmount1;
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
            key: 'total_discount_3',
            width: 200,
            render(row) {
                const discountAmount1 = row.total * (row.discount_1 / 100);
                const remainingAfterDiscount1 = row.total - discountAmount1;
                const discountAmount2 = remainingAfterDiscount1 * (row.discount_2 / 100);
                const remainingAfterDiscount2 = remainingAfterDiscount1 - discountAmount2;
                const discountAmount3 = remainingAfterDiscount2 * (row.discount_3 / 100);
                return formatRupiah(discountAmount3);
            }
        },
        {
            title: "TOTAL AKHIR",
            key: 'total_all',
            width: 200,
            render(row) {
                const discountAmount1 = row.total * (row.discount_1 / 100);
                const remainingAfterDiscount1 = row.total - discountAmount1;
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
        //                     type: 'info',
        //                     size: 'small',
        //                     onClick: () => {
        //                         router.visit(route('finance.claim-promo.detail'), { method: 'get' });
        //                     }
        //                 },
        //                 { default: () => 'Detail' }
        //             )
        //         ]);
        //     }
        // }
    ];
}

export default defineComponent({
    setup() {
        const form = useForm({ co_id: [] as any[] });
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);
        const page = usePage();
        const notification = useNotification();

        function handleSendReminder() {
            if (checkedRowKeysRef.value.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak ada data yang dipilih!',
                    text: 'Minimal 1 data harus dipilih',
                });
            } else {
                form.post(route('finance.form-claim-promo'), {
                    onSuccess: (page) => {
                        notification.success({
                            title: "Berhasil memilih data claim",
                            duration: 2500,
                            closable: false,
                        });
                    }
                });
            }
        }

        return {
            columns: createColumns(),
            handleSendReminder,
            checkedRowKeys: checkedRowKeysRef,
            pagination: {
                pageSize: 10
            },
            handleCheck(rowKeys: DataTableRowKey[]) {
                checkedRowKeysRef.value = rowKeys;

                // Update form.aging_id dengan id dari baris yang dipilih
                form.co_id = rowKeys;
            },
            rowKey: (row) => row.id,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>