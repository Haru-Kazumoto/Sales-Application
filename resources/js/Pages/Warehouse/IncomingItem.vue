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
                            <n-input placeholder="" disabled size="large"
                                v-model:value="transaction_details.purchase_order_number" />
                        </div>
                        <div class="col-md-4 col-6">
                            <label for="tanggal_po">
                                Tanggal PO<span class="text-danger">*</span>
                            </label>
                            <n-input disabled placeholder="" class="w-100" size="large"
                                v-model:value="transaction_details.purchase_order_date" />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="alokasi">
                                Alokasi<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" disabled id="alokasi" v-model:value="transaction_details.alocated"
                                size="large" />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="pemasok">
                                Pemasok<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="pemasok" v-model:value="transaction_details.supplier"
                                :options="gudangOptions" size="large" disabled />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="gudang_pengirim">
                                Gudang Pengirim<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" id="gudang_pengirim"
                                v-model:value="transaction_details.shipping_warehouse" size="large" disabled />
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="tanggal_kirim">
                                Tanggal Kirim Barang<span class="text-danger">*</span>
                            </label>
                            <n-date-picker disabled value-format="yyyy-MM-dd HH:mm:ss" type="datetime" placeholder=""
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
                        <!-- <div class="col-md-3">
                            <label for="tanggal_masuk">
                                Tanggal Expired<span class="text-danger">*</span>
                            </label>
                            <n-date-picker placeholder="" type="datetime" id="tanggal_masuk"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                v-model:formatted-value="transaction_details.expiry_date" size="large" />
                        </div> -->
                        <div class="col-md-3">
                            <label for="pengirim">
                                Nama Ekspedisi<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" disabled id="pengirim" v-model:value="transaction_details.sender"
                                size="large" />
                        </div>
                        <div class="col-md-3">
                            <label for="transportasi">
                                Nomor Polisi Ekspedisi<span class="text-danger">*</span>
                            </label>
                            <n-input placeholder="" disabled id="transportasi"
                                v-model:value="transaction_details.transportation" size="large" />
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

        <n-modal v-model:show="modalOpen" :mask-closable="false" class="d-flex" preset="card" :style="bodyStyle"
            title="Informasi barang" :bordered="false" size="huge" :segmented="segmented">
            <span class="fs-5 fw-semibold">{{ choosen_product }} | {{ current_quantity }}</span>
            <!-- Buatkan saya dinamic input jadi form nya bisa bertambah tambah sesuai kebutuhan -->
            <div v-for="(product, index) in product_journals" :key="index" class="d-flex flex-column gap-3">

                <n-divider>FORM {{ index + 1 }}</n-divider>
                <div class="d-flex flex-column gap-1">
                    <label for="" style="font-size: 16px;">KODE PEMBUATAN BARANG</label>
                    <n-input size="large" placeholder="" v-model:value="product.batch_code"
                        @input="(value) => product.batch_code = value.toUpperCase()" />
                </div>
                <div class="d-flex flex-column gap-1">
                    <label for="" style="font-size: 16px;">TANGGAL EXPIRED</label>
                    <n-date-picker placeholder="" type="datetime" v-model:formatted-value="product.expiry_date"
                        value-format="yyyy-MM-dd HH:mm:ss" size="large" />
                </div>
                <div class="d-flex flex-column gap-1">
                    <label for="" style="font-size: 16px;">QUANTITY</label>
                    <n-input size="large" placeholder="" v-model:value="product.quantity"
                        @input="(value) => product.quantity = value.replace(/\D/g, '')" />
                </div>
                <n-button type="error" ghost size="large" @click="removeFormProductJournal(index)">
                    Hapus Form Kode Batch
                </n-button>
            </div>
            <n-button type="primary" size="large" @click="addFormProductJournal" class="my-4 w-100">
                Tambah Form Kode Batch
            </n-button>

            <template #footer>
                <div class="d-flex">
                    <div class="d-flex gap-2 ms-auto">
                        <n-button type="error" size="large" @click="handleCloseModal">Batal</n-button>
                        <n-button type="info" size="large" @click="handleAddProduct">Simpan</n-button>
                    </div>
                </div>
            </template>
        </n-modal>

        <n-modal v-model:show="modalGapOpen" class="custom-card" preset="card" :style="bodyStyle"
            title="Informasi selisih barang" :bordered="false" size="huge" :segmented="segmented">

            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-1">
                    <label for="" style="font-size: 16px;">Jumlah selisih</label>
                    <n-input size="large" placeholder=""></n-input>
                </div>
                <div class="d-flex flex-column gap-1">
                    <label for="" style="font-size: 16px;">Deskripsi selisih</label>
                    <n-input size="large" placeholder="" type="textarea"></n-input>
                </div>
            </div>

            <template #footer>
                <div class="d-flex">
                    <div class="d-flex gap-2 ms-auto">
                        <n-button type="error" size="large" @click="modalGapOpen = false">Batal</n-button>
                        <n-button type="info" size="large">Simpan</n-button>
                    </div>
                </div>
            </template>
        </n-modal>

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
        const modalGapOpen = ref(false);
        const modalOpen = ref(false);
        const choosen_product = ref('');
        const current_quantity = ref(null as unknown as number);
        const index_choosen = ref(null as unknown as number);

        const form = useForm({
            document_code: '',
            transaction_items: [] as TransactionItems[],
            transaction_details: [] as TransactionDetail[],
        });

        const defaultProductJournal = {
            quantity: null as unknown as number,
            amount: null as unknown as number,
            action: "IN",
            batch_code: '',
            expiry_date: null as unknown as string,
            product_id: null as unknown as number,
        };

        // Product journals dimulai dengan satu item default
        const product_journals = ref([{ ...defaultProductJournal }]);

        // Fungsi untuk menambah item baru ke dalam product_journals
        const addFormProductJournal = () => {
            product_journals.value.push({ ...defaultProductJournal });
        };

        // Fungsi untuk menangani penutupan modal dan reset product_journals
        const handleCloseModal = () => {
            modalOpen.value = false;
            resetFormProductJournal();
        };

        function removeFormProductJournal(index) {
            product_journals.value.splice(index, 1);
        }


        // Fungsi untuk mereset product_journals menjadi satu item
        const resetFormProductJournal = () => {
            product_journals.value = [{ ...defaultProductJournal }];
        };

        const transaction_details = ref({
            purchase_order_number: '',
            purchase_order_date: null as unknown as string,
            alocated: '',
            supplier: '',
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
                    title: 'KODE BARANG',
                    key: 'product_code',
                    width: 150,
                    render(row) {
                        return row.product?.code;
                    }
                },
                {
                    title: 'NAMA BARANG',
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
                    width: 200,
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
                    title: "STATUS SELISIH",
                    key: "gap_status",
                    width: 150,
                    render(row) {
                        return row.gap_status;
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
                    title: "JUMLAH KODE",
                    key: 'shared_items',
                    width: 200,
                    render(row) {
                        return row.product_journals?.length;
                    }
                },
                {
                    title: "ACTION",
                    key: 'action',
                    width: 150,
                    render(row, index) {
                        return h('div', { class: "d-flex gap-2" }, [
                            h(
                                NButton,
                                {
                                    type: 'info',
                                    size: 'medium',
                                    onClick: () => handleOpenModal(row)
                                },
                                { default: () => 'Info Selisih' }
                            ),
                            h(
                                NButton,
                                {
                                    type: 'primary',
                                    size: 'medium',
                                    onClick: () => {
                                        handleOpenModalTable(row, index);
                                        // modalOpen.value = true;
                                    }
                                },
                                { default: () => 'Info Barang' }
                            )
                        ]);
                    }
                }
            ]
        }

        const handleOpenModalTable = (row, index) => {
            choosen_product.value = row.product?.name as string;
            index_choosen.value = index;
            current_quantity.value = row.quantity;

            // // Hitung total quantity yang ada di product_journals saat ini
            // const totalQuantityUsed = product_journals.value.reduce((total, journal) => {
            //     return total + (Number(journal.quantity) || 0); // Pastikan quantity adalah angka
            // }, 0);

            // // Kurangi row.quantity dengan totalQuantityUsed
            // current_quantity.value = row.quantity - totalQuantityUsed;

            // Jika sudah ada data yang disimpan sebelumnya di transaction_items[index], tampilkan di modal
            const existingProductJournals = form.transaction_items[index_choosen.value]?.product_journals;
            if (existingProductJournals && existingProductJournals.length > 0) {
                product_journals.value = existingProductJournals.map(journal => ({ ...journal }));
            } else {
                // Jika tidak ada data sebelumnya, reset ke default
                resetFormProductJournal();
            }

            modalOpen.value = true;
        };


        function handleOpenModal(row: TransactionItems) {
            Swal.fire({
                title: 'Informasi selisih',
                html: `
                    <div class="mb-3">
                        <input id="item_gap" class="form-control" type="number" placeholder="Selisih" value="${row.item_gap || ''}">
                    </div>
                    <div class="mb-3">
                        <input id="gap_description" class="form-control" type="text" placeholder="Keterangan" value="${row.gap_description || ''}">
                    </div>
                    <div class="mb-3">
                        <select id="gap_status" class="form-select">
                            <option value="RUSAK RINGAN" ${row.gap_status === 'RUSAK' ? 'selected' : ''}>RUSAK</option>
                            <option value="HILANG" ${row.gap_status === 'HILANG' ? 'selected' : ''}>HILANG</option>
                            <option value="TIDAK SESUAI" ${row.gap_status === 'TIDAK_SESUAI' ? 'selected' : ''}>TIDAK SESUAI</option>
                            <option value="PENGIRIMAN BERTAHAP" ${row.gap_status === 'PENGIRIMAN_BERTAHAP' ? 'selected' : ''}>PENGIRIMAN BERTAHAP</option>
                            <option value="LAINYA" ${row.gap_status === 'LAINNYA' ? 'selected' : ''}>LAINNYA (mencakup dibawa sales, dll)</option>
                        </select>
                    </div>
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const item_gap = (document.getElementById('item_gap') as HTMLInputElement).value;
                    const number_item_gap = Number(item_gap);
                    const gap_description = (document.getElementById('gap_description') as HTMLInputElement).value;
                    const gap_status = (document.getElementById('gap_status') as HTMLSelectElement).value;

                    if (!item_gap || !gap_description || !gap_status) {
                        Swal.showValidationMessage('Semua field harus diisi');
                        return null;
                    }

                    return {
                        item_gap: number_item_gap,
                        gap_description: gap_description,
                        gap_status: gap_status,
                    };
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Jika item_gap lebih dari 0, kurangi quantity pada row yang sesuai
                    if (result.value.item_gap > row.quantity) {
                        notification.error({
                            title: "Quantity melebihi dari yang ada!",
                            duration: 3000,
                            closable: true,
                        });
                        return;
                    }
                    if (result.value.item_gap > 0) {
                        row.quantity = (row.quantity ?? 0) - result.value.item_gap;
                    }

                    // Gunakan index untuk memperbarui item yang tepat
                    const index = form.transaction_items.indexOf(row);
                    if (index !== -1) {
                        // Update item pada index yang ditemukan dengan data baru
                        form.transaction_items[index].item_gap = result.value.item_gap;
                        form.transaction_items[index].gap_description = result.value.gap_description;
                        form.transaction_items[index].gap_status = result.value.gap_status;
                    }

                    // Notify success
                    Swal.fire('Berhasil!', 'Data telah diperbarui.', 'success');
                }
            });
        }



        // Fungsi submit
        function handleAddProduct(index) {
            // Hitung total quantity dari semua item di product_journals
            const totalQuantity = product_journals.value.reduce((total, journal) => {
                return total + (Number(journal.quantity) || 0); // Konversi ke integer untuk penjumlahan
            }, 0);

            // Validasi: Cek apakah totalQuantity melebihi current_quantity
            if (totalQuantity > current_quantity.value) {
                notification.error({
                    title: "Jumlah total kuantitas melebihi kuantitas yang tersedia!",
                    duration: 2000,
                    closable: false,
                });
                return; // Hentikan fungsi jika total kuantitas melebihi yang tersedia
            }

            const isValid = product_journals.value.every(journal => {
                return journal.quantity !== null &&
                    journal.batch_code !== '' &&
                    journal.expiry_date !== null;
            });

            if (!isValid) {
                // Jika ada field yang kosong, tampilkan notifikasi error dan hentikan proses
                notification.error({
                    title: "Gagal membuat informasi barang!",
                    description: "Pastikan semua field telah diisi dengan benar.",
                    duration: 1500,
                    closable: false,
                });
                return; // Hentikan fungsi jika ada field yang kosong
            }

            try {
                // Perbarui product_journals pada transaksi item yang sesuai berdasarkan index
                if (form.transaction_items[index_choosen.value]) {
                    form.transaction_items[index_choosen.value].product_journals = product_journals.value;
                }

            } catch (err) {
                notification.error({
                    title: "Gagal membuat informasi barang!",
                    meta: err
                });
            } finally {
                modalOpen.value = false;
                resetFormProductJournal();
            }

            Swal.fire({
                icon: "success",
                title: "Berhasil menambahkan informasi barang",
            });
        }


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
                                product_journals: [] as any[],
                                gap_status: null,
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
            handleAddProduct,
            addFormProductJournal,
            handleCloseModal,
            removeFormProductJournal,
            gudangOptions,
            alokasiOptions,
            pagination: { pageSize: 10 },
            form,
            transaction_details,
            product_journals,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            modalOpen,
            modalGapOpen,
            choosen_product,
            current_quantity,
        };
    },
    components: {
        TitlePage
    }
});
</script>