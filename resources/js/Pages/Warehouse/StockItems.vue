<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="STOK BARANG GUDANG " />
        <div class="d-flex flex-column gap-2">
            <n-button class="w-25" type="primary" @click="handleExport">Export data barang</n-button>
            <n-tabs type="line" animated>
                <n-tab-pane name="all" tab="Semua Produk">
                    <div class="d-flex flex-column gap-2">
                        <div class="row g-3 ">
                            <div class="col-12 col-lg-6 ">
                                <span class="fs-4">Daftar Semua Produk</span>
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-select size="large" placeholder="Status" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                        </div>
                        <div class="card shadow" style="border: none;">
                            <div class="card-body">
                                <n-data-table :columns="columns" :data="($page.props.products as any).data"
                                    :bordered="false" size="small" pagination-behavior-on-filter="first" />
                            </div>
                        </div>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="by-batch" tab="Produk dengan batch code">
                    <div class="d-flex flex-column gap-2">
                        <div class="row g-3 ">
                            <div class="col-12 col-lg-6 ">
                                <span class="fs-4">Daftar Produk Dengan Batch</span>
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-select size="large" placeholder="Status" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                        </div>
                        <div class="card shadow" style="border: none;">
                            <div class="card-body">
                                <n-data-table :columns="batchColumns" :data="($page.props.products_batch as any).data"
                                    :bordered="false" size="small" pagination-behavior-on-filter="first" />
                            </div>
                        </div>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="gap-products" tab="Kekurangan produk">
                    <div class="d-flex flex-column gap-2">
                        <div class="row ">
                            <div class="col-12 col-lg-7">
                                <span class="fs-4">Daftar Kekurangan Produk</span>
                            </div>
                            <div class="col-3 col-lg-2" v-if="$page.props.auth.user.division.division_name === 'PROCUREMENT'">
                                <n-button type="primary" size="large" @click="handleSendMessage">Report Ke Supplier</n-button>
                            </div>
                            <div class="col-12 col-lg-3 d-flex gap-3 ">
                                <n-input placeholder="Cari Nama Produk" size="large" />
                            </div>
                        </div>
                        <div class="card shadow" style="border: none;">
                            <div class="card-body">
                                <n-data-table :columns="gapColumns" :data="($page.props.products_gap as any).data"
                                    :bordered="false" size="small" pagination-behavior-on-filter="first" />
                            </div>
                        </div>
                    </div>

                    <!-- MODAL -->
                    <n-modal v-model:show="modalOpen" :mask-closable="false" class="d-flex" preset="card"
                        :style="bodyStyle" title="Tambah Barang" :bordered="false" size="huge" :segmented="segmented">
                        <span class="fs-5 fw-semibold">{{ dataChoosen.product_name }} | {{ dataChoosen.quantity
                            }}</span>
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
                                <n-date-picker placeholder="" type="datetime"
                                    v-model:formatted-value="product.expiry_date" value-format="yyyy-MM-dd HH:mm:ss"
                                    size="large" />
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
                                    <n-button type="info" size="large" @click="handleSubmitProduct">Tambah
                                        Produk</n-button>
                                </div>
                            </div>
                        </template>
                    </n-modal>
                </n-tab-pane>
                <n-tab-pane name="stagnation_date" tab="Produk stagnasi">
                    <div class="d-flex flex-column gap-2">
                        <div class="row g-3 ">
                            <div class="col-12 col-lg-6 ">
                                <span class="fs-4">Daftar Produk stagnasi di gudang</span>
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-select size="large" placeholder="Status" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-input placeholder="Cari Produk" />
                            </div>
                            <div class="col-12 col-lg-2 d-flex gap-3 ">
                                <n-button type="primary" size="large" @click="handleNoticeProduk">Report Notice Product</n-button>
                            </div>
                        </div>
                        <div class="card shadow" style="border: none;">
                            <div class="card-body">
                                <n-data-table :columns="batchColumns"
                                    :data="($page.props.product_stagnations as any)" :bordered="false" size="small"
                                    pagination-behavior-on-filter="first" />
                            </div>
                        </div>
                    </div>
                </n-tab-pane>
            </n-tabs>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h, ref } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import CountCard from '../../Components/CountCard.vue';
import { DataTableColumns, NButton, NTag, useNotification } from 'naive-ui';
import { router, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia
import Swal from 'sweetalert2';

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        function createColumns() {
            return [
                {
                    title: '#',
                    key: 'index',
                    width: 50,
                    render(rowData, rowIndex) {
                        return rowIndex + 1;  // Menghitung nomor urut
                    },
                },
                {
                    title: 'NAMA BARANG',
                    key: 'name',
                    width: 300,
                },
                {
                    title: 'KODE BARANG',
                    key: 'code',
                    width: 100,
                },
                {
                    title: 'KEMASAN',
                    key: 'unit',
                    width: 100,
                },
                {
                    title: 'STOK',
                    key: 'last_stock',
                    width: 100,
                },
                {
                    title: 'ALOKASI',
                    key: 'warehouse',
                    width: 100,
                },
                {
                    title: 'Status',
                    key: 'item_status',
                    width: 100,
                    render(rowData) {
                        // Tentukan warna dan tipe tag berdasarkan item_status pembayaran
                        let type: any;

                        switch (rowData.status) {
                            case 'HABIS':
                                type = 'error';
                                break;
                            case 'PERLU TAMBAH':
                                type = 'warning';
                                break;
                            case 'TERSEDIA':
                                type = 'success';
                                break;
                            default:
                                type = '';
                        }

                        return h(
                            NTag,
                            {
                                style: {
                                    marginRight: '6px',
                                },
                                type,
                                strong: true,
                            },
                            { default: () => rowData.status }
                        );
                    },
                },

            ];
        }

        function createColumnsBatch() {
            return [
                {
                    title: '#',
                    key: 'index',
                    width: 50,
                    render(rowData, rowIndex) {
                        return rowIndex + 1;  // Menghitung nomor urut
                    },
                },
                {
                    title: 'NAMA BARANG',
                    key: 'name',
                    width: 300,
                },
                {
                    title: 'KODE BARANG',
                    key: 'code',
                    width: 150,
                },
                {
                    title: "KODE PRODUKSI BARANG",
                    key: "batch_code",
                    width: 250,
                },
                {
                    title: 'KEMASAN',
                    key: 'unit',
                    width: 100,
                },
                {
                    title: 'STOK GUDANG',
                    key: 'last_stock',
                    width: 100,
                },
                {
                    title: 'ALOKASI',
                    key: 'warehouse',
                    width: 100,
                },
                {
                    title: 'STATUS',
                    key: 'item_status',
                    width: 100,
                    render(rowData) {
                        // Tentukan warna dan tipe tag berdasarkan item_status pembayaran
                        let type: any;

                        switch (rowData.status) {
                            case 'HABIS':
                                type = 'error';
                                break;
                            case 'PERLU TAMBAH':
                                type = 'warning';
                                break;
                            case 'TERSEDIA':
                                type = 'success';
                                break;
                            default:
                                type = '';
                        }

                        return h(
                            NTag,
                            {
                                style: {
                                    marginRight: '6px',
                                },
                                type,
                                strong: true,
                            },
                            { default: () => rowData.status }
                        );
                    },
                },

            ];
        }

        function createColumnGapProducts() {
            return [
                {
                    title: "#",
                    key: "index",
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "NAMA BARANG",
                    key: "name",
                    width: 250,
                    render(row) {
                        return row.product?.name;
                    }
                },
                {
                    title: "KODE BARANG",
                    key: "code",
                    width: 250,
                    render(row) {
                        return row.product.code;
                    }
                },
                {
                    title: "KEMASAN",
                    key: "unit",
                    width: 150,
                    render(row) {
                        return row.product.unit;
                    }
                },
                {
                    title: "KEKURANGAN BARANG",
                    key: "gap",
                    width: 150,
                    render(row) {
                        return row.quantity;
                    }
                },
                {
                    title: "STATUS KEKURANGAN BARANG",
                    key: "gap_status",
                    width: 250,
                    render(row) {
                        const status = row.gap_status.replace("_", " ");
                        let type;

                        switch (status) {
                            case 'PENGIRIMAN BERTAHAP':
                                type = 'info';
                                break;
                            case 'RUSAK':
                                type = 'error';
                                break;
                            default:
                                type = 'warning';
                                break;
                        }

                        return h(
                            NTag,
                            {
                                type,
                                strong: true,
                                bordered: true,
                                size: 'large'
                            }, { default: () => status }
                        )
                    }
                },
                {
                    title: "DESKRIPSI KEKURANGAN BARANG",
                    key: "description",
                    width: 250,
                    render(row) {
                        return row.description;
                    }
                },
                {
                    title: "NOMOR SSO",
                    key: "sso_number",
                    width: 200,
                },
                {
                    title: "NOMOR PO",
                    key: "po_number",
                    width: 200,
                },
                {
                    title: "DIBUAT PADA",
                    key: "created_at",
                    width: 250,
                    render(row) {
                        return dayjs(row.created_at).format('DD MMMM YYYY HH:mm')
                    }
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 150,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "primary",
                                size: "large",
                                strong: true,
                                bordered: true,
                                disabled: row.gap_status !== "PENGIRIMAN BERTAHAP",
                                onClick: () => {
                                    handleOpenModal(row);
                                }
                            }, { default: () => "PROSES" }
                        )
                    }
                }
            ]
        }

        const page = usePage();

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
        });

        const notification = useNotification();
        const modalOpen = ref(false);
        const dataChoosen = ref({
            product_name: '',
            quantity: null as unknown as number,
        });

        const defaultProductJournal = {
            journal_id: null as unknown as number,
            quantity: null as unknown as number,
            amount: null as unknown as number,
            action: "IN",
            batch_code: '',
            expiry_date: null as unknown as string,
            sso_number: '',
            po_number: '',
            product_id: null as unknown as number,
            warehouse_id: null as unknown as number,
            transactions_id: null as unknown as number,
        };

        // Product journals dimulai dengan satu item default
        const product_journals = ref([{ ...defaultProductJournal }]);

        function handleOpenModal(row) {
            dataChoosen.value.product_name = row.product?.name;
            dataChoosen.value.quantity = row.quantity;

            // Set default product_id dan amount untuk semua item di product_journals
            product_journals.value.forEach((journal) => {
                journal.journal_id = row.id;
                journal.amount = row.amount || 0; // Atau gunakan nilai default amount
                journal.sso_number = row.sso_number;
                journal.po_number = row.po_number;
                journal.product_id = row.product?.id;
                journal.warehouse_id = row.warehouse_id;
                journal.transactions_id = row.transactions_id;
            });

            modalOpen.value = true;
        }

        const addFormProductJournal = () => {
            product_journals.value.push({
                ...defaultProductJournal,
                journal_id: product_journals.value[0]?.journal_id,
                product_id: product_journals.value[0]?.product_id, // Ambil product_id dari item pertama
                amount: product_journals.value[0]?.amount || 0, // Ambil amount dari item pertama
                sso_number: product_journals.value[0]?.sso_number,
                po_number: product_journals.value[0]?.po_number,
                warehouse_id: product_journals.value[0]?.warehouse_id,
                transactions_id: product_journals.value[0]?.transactions_id,
            });
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

        function handleSubmitProduct() {
            const totalQuantity = product_journals.value.reduce((sum, item) => sum + (item.quantity || 0), 0);
            if (totalQuantity > dataChoosen.value.quantity) {
                notification.error({
                    title: `Quantity melebihi dari sisa quantity! [${dataChoosen.value.quantity}]`,
                    duration: 3000,
                    closable: true,
                })
                return;
            }
            // Kirim data ke backend menggunakan Laravel Inertia
            router.post(route('warehouse.gradual-delivery.post'), {
                product_journals: product_journals.value, // Kirim data
            }, {
                onSuccess: (page) => {
                    // Reset form setelah berhasil submit
                    modalOpen.value = false;
                    resetFormProductJournal();

                    Swal.fire((page.props.flash as any).success, 'Quantity telah berubah', 'success');
                },
                onError: (errors) => {
                    notification.error({
                        title: (page.props.flash as any).failed,
                        duration: 2000,
                        closable: false,
                    });
                }
            });
        }

        function handleSendMessage() {
            router.post(route('warehouse.send-message-supplier'),{},{
                onSuccess: (page) => {
                    Swal.fire(page.props.flash.success, '', 'success');
                },
                onError: () => {
                    Swal.fire('Oops, server sedang sibuk :(', 'Tunggu sebentar atau lapor developer segera', 'error');
                }
            });
        }

        function handleNoticeProduk() {
            Swal.fire({
                title: "Report Barang Stagnasi?",
                text: "Teks whatsapp akan terkirim ke principal terkait dan seluruh salesman",
                icon: "warning",
                showCancelButton: true,
            }).then((result) => {

                if(result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim pesan...',
                        text: 'Tunggu sekejap',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    router.post(route('warehouse.send-notice-products-report'),{},{
                        onSuccess: (page) => {
                            Swal.fire(page.props.flash.success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire('Pesan gagal tekirim','Silahkan lapor developer','error');
                        }
                    });
                }
            });
        }

        function handleExport(){
            const url = route('warehouse.product.export');

            window.location.href = url;
        }

        return {
            pagination,
            columns: createColumns(),
            batchColumns: createColumnsBatch(),
            gapColumns: createColumnGapProducts(),
            handleNoticeProduk,
            handleOpenModal,
            addFormProductJournal,
            handleCloseModal,
            handleSendMessage,
            removeFormProductJournal,
            handleSubmitProduct,
            handleExport,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            modalOpen,
            dataChoosen,
            product_journals,
        };
    },
    components: {
        TitlePage,
        CountCard
    }
});
</script>

<style scoped></style>