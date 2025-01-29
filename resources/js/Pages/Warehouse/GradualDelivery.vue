<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Pengiriman Barang Bertahap" />
        <div class="d-flex">
            <div class="d-flex ms-auto gap-3">
                <n-input placeholder="Cari Nama Produk" size="large" />
                <n-button type="primary" size="large" @click="handleSendMessage">Report Ke Supplier</n-button>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="($page.props.gradual_products as any).data" />
            </div>
        </div>

        <n-modal v-model:show="modalOpen" :mask-closable="false" class="d-flex" preset="card" :style="bodyStyle"
            title="Tambah Barang" :bordered="false" size="huge" :segmented="segmented">
            <span class="fs-5 fw-semibold">{{ dataChoosen.product_name }} | {{ dataChoosen.quantity }}</span>
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
                        <n-button type="info" size="large" @click="handleSubmitProduct">Tambah Produk</n-button>
                    </div>
                </div>
            </template>
        </n-modal>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { h } from 'vue';
import { NButton, useNotification } from 'naive-ui';
import { router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        function createColumns() {
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
                    title: "KEKURANGAN PRODUK",
                    key: "gap",
                    width: 150,
                    render(row) {
                        return row.quantity;
                    }
                },
                {
                    title: "DESKRIPSI KEKURANGAN PRODUK",
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
                    title: "ACTION",
                    key: "action",
                    width: 150,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "info",
                                size: 'medium',
                                strong: true,
                                onClick: () => {
                                    handleOpenModal(row);
                                }
                            }, { default: () => "Tambah Barang" }
                        );
                    }
                }
            ]
        }

        const page = usePage();
        const notification = useNotification();
        const modalOpen = ref(false);
        const dataChoosen = ref({
            product_name: '',
            quantity: '',
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
                    notification.success({
                        title: (page.props.flash as any).failed,
                        duration: 2000,
                        closable: false,
                    });
                }
            });
        }

        function handleSendMessage() {
            // Swal.fire({
            //     title: 'Kirim Pesan Ke Supplier',
            //     input: 'textarea',
            //     inputLabel: 'Pesan',
            //     inputPlaceholder: 'Tulis pesan disini...',
            //     inputAttributes: {
            //         'aria-label': 'Type your message here'
            //     },
            //     showCancelButton: true,
            //     confirmButtonText: 'Kirim',
            //     cancelButtonText: 'Batal',
            //     showLoaderOnConfirm: true,
            //     preConfirm: (message) => {
            //         return router.post(route('warehouse.send-message-supplier'), {
            //             message
            //         });
            //     },
            //     allowOutsideClick: () => !Swal.isLoading()
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         Swal.fire('Pesan Terkirim!', '', 'success');
            //     }
            // });
            Swal.fire({
                title: 'Kirim Pesan Ke Supplier?',
                icon: "question",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    router.post(route('warehouse.send-message-supplier'),{},{
                        onSuccess: (page) => {
                            Swal.fire(page.props.flash.success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire('Oops, server sedang sibuk :(', 'Tunggu sebentar atau lapor developer segera', 'error');
                        }
                    });
                }
            });
            
        }

        return {
            columns: createColumns(),
            handleSendMessage,
            handleOpenModal,
            addFormProductJournal,
            handleCloseModal,
            removeFormProductJournal,
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
            handleSubmitProduct
        }
    },
    components: {
        TitlePage
    }
})
</script>