<template>

    <Head title="Update PO" />
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Update PO" />
        <div class="d-flex gap-2 align-items-center">
            <n-select :options="dateOptions" class="w-25" size="large" placeholder="Filter data per waktu" />
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="$page.props.purchase_orders.data" />
            </div>
        </div>
    </div>
    <n-modal v-model:show="showModal">
        <n-card style="width: 500px" :title="selectedPurchaseOrderNumber" :bordered="false" size="huge" role="dialog"
            aria-modal="true">
            <div class="d-flex flex-column gap-1">
                <label for="number_plate" class="fs-5">Nomor Polisi
                    <RequiredMark />
                </label>
                <n-input v-model:value="numberPlateForm.number_plate" placeholder="" size="large"
                    @input="(value) => numberPlateForm.number_plate = value.toUpperCase()" />
            </div>
            <template #footer>
                <div class="d-flex gap-2 justify-content-center">
                    <n-button type="error" size="large" @click="showModal = false">Batal</n-button>
                    <n-button type="info" size="large" @click="handleUpdateNumberPlate">Submit</n-button>
                </div>
            </template>
        </n-card>
    </n-modal>

</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import RequiredMark from '../../../Components/RequiredMark.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Refresh } from "@vicons/ionicons5";
import { DataTableColumns, NButton, NTag, useNotification } from 'naive-ui';
import { formatRupiah, formatDate } from '../../../Utils/options-input.utils';
import { Transactions } from '../../../types/model';

export default defineComponent({
    setup() {
        const selectedPurchaseOrderNumber = ref<string | null>(null);
        const notification = useNotification();
        const showModal = ref(false);

        const numberPlateForm = useForm({
            transactions_id: null as unknown as number,
            number_plate: null as unknown as string,
        });

        function createColumns(): DataTableColumns<Transactions> {
            return [
                {
                    title: '#',
                    key: 'index',
                    width: 60,
                    render(rowData, rowIndex) {
                        return rowIndex + 1;  // Menghitung nomor urut
                    },
                },
                {
                    title: 'No PO',
                    key: 'purchase_order_number',
                    width: 200,
                    render(rowData) {
                        return rowData.document_code;  // Menampilkan nomor faktur
                    },
                },
                {
                    title: 'Pemasok',
                    key: 'supplier',
                    width: 200,
                    render(rowData) {
                        const supplierData = rowData.transaction_details.find((data) => {
                            return data.category === "Supplier"
                        })

                        return supplierData?.value;  // Menampilkan nama salesman
                    },
                },
                {
                    title: 'Pengirim',
                    key: 'sender',
                    width: 200,
                    render(rowData) {
                        const senderData = rowData.transaction_details.find((data) => {
                            return data.category === "Sender";
                        })

                        return senderData?.value?.replace("_", " ");
                    },
                },
                {
                    title: 'Gudang',
                    key: 'storehouse',
                    width: 200,
                    render(rowData) {
                        const storehouseData = rowData.transaction_details.find((data) => {
                            return data.category === "Storehouse";
                        })

                        return storehouseData?.value;
                    },
                },
                {
                    title: 'Harga',
                    key: 'total_price',
                    width: 200,
                    render(rowData) {
                        return formatRupiah(rowData.total ?? 0);
                    },
                },
                {
                    title: "Tanggal PO",
                    key: "po_date",
                    width: 250,
                    render(rowData) {
                        const purchase_order_date = rowData.transaction_details.find((data) => {
                            return data.category === "PO Date";
                        })

                        return formatDate(purchase_order_date?.value);
                    }
                },
                {
                    title: 'Action',
                    key: 'action',
                    width: 200,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "info",
                                onClick: () => {
                                    handleSetData(row);
                                    showModal.value = true;
                                }
                            },
                            { default: () => "Set Nomor Polisi" }
                        );
                    }
                }
            ];
        }

        // Fungsi untuk menangani ketika tombol "Set Nomor Polisi" diklik
        function handleSetData(row) {
            selectedPurchaseOrderNumber.value = row.document_code;
            numberPlateForm.transactions_id = row.id;
        };

        function handleUpdateNumberPlate() {
            numberPlateForm.patch(route('procurement.update-number-plate'), {
                onSuccess: (page) => {
                    numberPlateForm.reset();
                    showModal.value = false;

                    notification.success({
                        title: page.props.flash.success,
                        closable: false,
                        duration: 1500,
                    });
                },
                onError: () => {
                    notification.error({
                        title: "Gagal memperbarui",
                        closable: false,
                        duration: 1500,
                    });
                }
            });
        }

        //options
        const dateOptions = [
            { label: "Bulan Ini", value: 'this_month' },
            { label: "Minggu Ini", value: 'this_week' },
            { label: "Tahun Ini", value: 'this_year' }
        ]

        return {
            columns: createColumns(),
            handleUpdateNumberPlate,
            dateOptions,
            selectedPurchaseOrderNumber,
            numberPlateForm,
            showModal,
            Refresh
        }
    },
    components: {
        TitlePage,
        Head,
        RequiredMark
    }
})
</script>