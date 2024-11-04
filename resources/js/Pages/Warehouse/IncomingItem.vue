<template>
    <div class="d-flex flex-column gap-4">
        <!-- Judul Halaman -->
        <TitlePage title="Barang Masuk Gudang" />

        <!-- Form Barang Masuk -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <form class="d-flex flex-column">
                    <!-- Grid untuk Input Form -->
                    <div class="row g-3">
                        <!-- Kolom Atas -->
                        <div class="col-lg-2 col-6">
                            <label for="field1">
                                No SSO<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="field1" size="large" v-model:value="form.document_code" />
                        </div>
                        <div class="col-lg-2 col-6 d-flex align-items-end">
                            <n-button class="w-100" size="large" strong secondary type="primary"
                                @click="handleProcessSso">Proses</n-button>
                        </div>
                        <div class="col-md-4 col-6">
                            <label for="no_po">
                                No PO<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" readonly size="large"
                                v-model:value="transaction_details.purchase_order_number" />
                        </div>
                        <div class="col-md-4 col-6">
                            <label for="tanggal_po">
                                Tanggal PO<span class="text-danger">*</span>
                            </label>
                            <n-input readonly placeholder=""
                                class="w-100"  size="large"
                                v-model:value="transaction_details.purchase_order_date" />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="alokasi">
                                Alokasi<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" readonly id="alokasi" v-model:value="transaction_details.alocated"
                                size="large" />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="pemasok">
                                Pemasok<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="pemasok" v-model:value="transaction_details.supplier"
                                :options="gudangOptions" size="large" readonly />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="gudang_pengirim">
                                Gudang Pengirim<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="gudang_pengirim"
                                v-model:value="transaction_details.shipping_warehouse" size="large" readonly />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="tanggal_kirim">
                                Tanggal Kirim Barang<span class="text-danger">*</span>
                            </label>
                            <n-date-picker readonly value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder=""
                                class="w-100" id="field8" size="large"
                                v-model:value="transaction_details.delivery_date" />
                        </div>

                        <!-- Kolom Bawah -->
                        <div class="col-md-3">
                            <label for="tanggal_masuk">
                                Tanggal Masuk Gudang<span class="text-danger">*</span>
                            </label>
                            <n-date-picker placeholder="" id="tanggal_masuk" type="datetime"
                                v-model:formatted-value="transaction_details.warehouse_entry_date"
                                value-format="yyyy-MM-dd HH:mm:ss" size="large" />
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_masuk">
                                Tanggal Expired<span class="text-danger">*</span>
                            </label>
                            <n-date-picker placeholder="" type="datetime" id="tanggal_masuk"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                v-model:formatted-value="transaction_details.expiry_date" size="large" />
                        </div>
                        <div class="col-md-3">
                            <label for="transportasi">
                                Nomor Polisi Ekspedisi<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" readonly id="transportasi"
                                v-model:value="transaction_details.transportation" size="large" />
                        </div>
                        <div class="col-md-3">
                            <label for="pengirim">
                                Pengirim<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" readonly id="pengirim" v-model:value="transaction_details.sender"
                                size="large" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Barang -->
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :pagination="pagination" :data="form.transaction_items" size="small"
                    pagination-behavior-on-filter="first" />
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex justify-content-end mb-4">
            <n-button type="primary" size="large" @click="handleSubmit">
                Submit Stock Barang
            </n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue';
import { NButton, NInput, NDatePicker, NSelect, NDataTable, DataTableColumns, useNotification } from 'naive-ui';
import TitlePage from '../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Flash, TransactionDetail, TransactionItems, Transactions } from '../../types/model';
import Swal from 'sweetalert2';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        const form = useForm({
            document_code: '',
            transaction_items: [] as TransactionItems[],
            transaction_details: [] as TransactionDetail[],
        });

        const transaction_details = ref({
            purchase_order_number: '',
            purchase_order_date: null as unknown as string,
            alocated: '',
            supplier: '',
            expiry_date: null as unknown as string,
            shipping_warehouse: '',
            delivery_date: new Date(),
            warehouse_entry_date: null as unknown as string,
            transportation: '',
            sender: '',
        });

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
                    title: "QTY PEMESANAN",
                    key: 'quantity',
                    width: 200,
                },
                {
                    title: "QTY BARANG DATANG",
                    key: 'quantity',
                    width: 150,
                },
                {
                    title: "SELISIH",
                    key: 'difference',
                    width: 150,
                    render(row) {
                        return row.item_gap;
                    }
                },
                {
                    title: "TANGGAL KADALUARSA",
                    key: 'expiry_date',
                    width: 200,
                    render(row) {
                        return transaction_details.value.expiry_date ? dayjs(transaction_details.value.expiry_date).format('D MMMMYYYY') : '';
                    }
                },
                {
                    title: "KETERANGAN",
                    key: 'item_description',
                    width: 200,
                    render(row) {
                        return row.gap_description || '';
                    }
                },
                {
                    title: "ACTION",
                    key: 'action',
                    width: 150,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: 'info',
                                size: 'small',
                                onClick: () => handleOpenModal(row)
                            },
                            { default: () => 'Detail' }
                        );
                    }
                }
            ]
        }

        function handleOpenModal(row: TransactionItems) {
            Swal.fire({
                title: 'Input Selisih dan Keterangan',
                html:
                    `<input id="item_gap" class="swal2-input" type="number" placeholder="Selisih" value="${row.item_gap || ''}">` +
                    `<input id="gap_description" class="swal2-input" type="text" placeholder="Keterangan" value="${row.gap_description || ''}">`,
                focusConfirm: false,
                preConfirm: () => {
                    const item_gap = (document.getElementById('item_gap') as HTMLInputElement).value;
                    const number_item_gap = Number(item_gap);
                    const gap_description = (document.getElementById('gap_description') as HTMLInputElement).value;

                    if (!item_gap || !gap_description) {
                        Swal.showValidationMessage('Semua field harus diisi');
                        return null;
                    }

                    return {
                        item_gap: number_item_gap,
                        gap_description: gap_description,
                    };
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Jika item_gap lebih dari 0, kurangi quantity pada row yang sesuai
                    if (result.value.item_gap > 0) {
                        row.quantity = (row.quantity ?? 0) - result.value.item_gap;
                    }

                    // Gunakan index untuk memperbarui item yang tepat
                    const index = form.transaction_items.indexOf(row);
                    if (index !== -1) {
                        // Update item pada index yang ditemukan dengan data baru
                        form.transaction_items[index].item_gap = result.value.item_gap;
                        form.transaction_items[index].gap_description = result.value.gap_description;

                        // Jika item_gap lebih dari 0, kurangi quantity dengan item_gap
                        // if (result.value.item_gap > 0) {
                        //     form.transaction_items[index].quantity = (form.transaction_items[index].quantity ?? 0) - result.value.item_gap;
                        // }
                    }

                    // Optionally: Notify success
                    Swal.fire('Berhasil!', 'Data telah diperbarui.', 'success');
                }
            });
        }

        // Fungsi submit
        function handleSubmit() {
            form.transaction_details = [
                {
                    name: "Nomor PO",
                    category: 'PO Number',
                    value: transaction_details.value.purchase_order_number,
                    data_type: 'string',
                },
                {
                    name: "Tanggal PO",
                    category: "PO Date",
                    value: transaction_details.value.purchase_order_date,
                    data_type: 'datetime',
                },
                {
                    name: "Tanggal Expired",
                    category: "Expiry Date",
                    value: transaction_details.value.expiry_date,
                    data_type: 'datetime',
                },
                {
                    name: 'Alokasi',
                    category: 'Allocation',
                    value: transaction_details.value.alocated,
                    data_type: 'string',
                },
                {
                    name: 'Pemasok',
                    category: 'Supplier',
                    value: transaction_details.value.supplier,
                    data_type: 'string',
                },
                {
                    name: 'Gudang Pengirim',
                    category: 'Shipping Warehouse',
                    value: transaction_details.value.shipping_warehouse,
                    data_type: 'string',
                },
                {
                    name: 'Tanggal Kirim Barang',
                    category: 'Delivery Date',
                    value: transaction_details.value.delivery_date as any,
                    data_type: 'datetime',
                },
                {
                    name: 'Tanggal Masuk Gudang',
                    category: 'Warehouse Entry Date',
                    value: transaction_details.value.warehouse_entry_date,
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
            ];

            // Lakukan pengurangan quantity dengan item_gap sebelum melakukan post
            form.transaction_items = form.transaction_items.map(item => {
                if (item.item_gap ?? 0 > 0) {
                    return {
                        ...item,
                        quantity: item.quantity ?? 0 - item.item_gap ?? 0, // Mengurangi quantity dengan item_gap
                    };
                }
                return item;
            });

            form.post(route('warehouse.store-products'), {
                onSuccess() {
                    form.reset()

                    transaction_details.value = {
                        purchase_order_number: '',
                        purchase_order_date: null as unknown as string,
                        alocated: '',
                        supplier: '',
                        expiry_date: null as unknown as string,
                        shipping_warehouse: '',
                        delivery_date: new Date(),
                        warehouse_entry_date: null as unknown as string,
                        transportation: '',
                        sender: '',
                    }

                    Swal.fire({
                        icon: 'success',
                        title: (page.props.flash as Flash).success,
                    });
                },
                onError() {
                    Swal.fire({
                        icon: 'error',
                        title: (page.props.flash as Flash).failed
                    });
                }
            });
        }

        function handleProcessSso() {
            router.visit(route('warehouse.process-sso-data', form.document_code), {
                method: 'get',
                preserveState: true,
                onSuccess: (page) => {
                    const data = page.props.transaction as Transactions;

                    if (!data) {
                        Swal.fire({
                            icon: 'error',
                            title: (page.props.flash as Flash).failed
                        });
                        return;
                    }

                    const po_number = data.transaction_details.find(data => data.category === "PO Number")?.value || '';
                    const po_date = data.transaction_details.find(data => data.category === "PO Date")?.value || '';
                    const allocation = data.transaction_details.find(data => data.category === "Allocation")?.value || '';
                    const supplier = data.transaction_details.find(data => data.category === "Supplier")?.value || '';
                    const storehouse = data.transaction_details.find(data => data.category === "Storehouse")?.value || '';
                    const transportation = data.transaction_details.find(data => data.category === "Transportation")?.value || '';
                    const sender = data.transaction_details.find(data => data.category === "Sender")?.value || '';

                    //set data
                    transaction_details.value.purchase_order_number = po_number;
                    transaction_details.value.purchase_order_date = po_date;
                    transaction_details.value.alocated = allocation;
                    transaction_details.value.supplier = supplier;
                    transaction_details.value.shipping_warehouse = storehouse;
                    transaction_details.value.transportation = transportation;
                    transaction_details.value.sender = sender;

                    // Reset form.transaction_items untuk memasukkan produk baru
                    form.transaction_items = [];

                    if (Array.isArray(data.transaction_items)) {
                        data.transaction_items.forEach(item => {
                            form.transaction_items.push({
                                unit: item.unit,
                                quantity: item.quantity,
                                product_id: item.product_id,
                                amount: item.amount,
                                item_gap: item.item_gap,
                                tax_value: null as unknown as number,
                                gap_description: item.gap_description,
                                product: {
                                    code: item.product?.code || '',
                                    unit: item.product?.unit || '',
                                    name: item.product?.name || '',
                                }
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data produk tidak valid',
                        });
                    }

                    notification.success({
                        title: "Sukses mengambil produk PO",
                        duration: 1500,
                        closable: false,
                    });
                },
                onError: (error) => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat data',
                    });
                }
            })
        }

        // Pilihan dropdown
        const gudangOptions = [
            { label: 'PABRIK SRIBOGA', value: 'pabrik_sriboja' },
            { label: 'GUDANG A', value: 'gudang_a' }
        ];

        const alokasiOptions = [
            { label: 'DNP', value: 'dnp' },
            { label: 'Lainnya', value: 'lainnya' }
        ];

        return {
            columns: createColumns(),
            handleSubmit,
            handleProcessSso,
            gudangOptions,
            alokasiOptions,
            pagination: { pageSize: 10 },
            form,
            transaction_details
        };
    },
    components: {
        TitlePage
    }
});
</script>