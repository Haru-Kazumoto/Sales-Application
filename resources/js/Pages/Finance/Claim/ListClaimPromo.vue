<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Claim Promo" />

        <!-- Bagian filter: responsive untuk input -->
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <n-select placeholder="BULAN" size="large" />
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <n-input placeholder="NO KLAIM" size="large" />
            </div>
        </div>

        <!-- Bagian tabel: responsive dengan card -->
        <div class="card shadow-sm" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :data="$page.props.result" :columns="columns" size="small" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia
import Swal from 'sweetalert2';

dayjs.locale('id'); // Set locale to Indonesian



export default defineComponent({
    setup() {
        const showModal = ref(false);
        const document_code = ref(null as unknown as string);
        const page = usePage();
        
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
                    title: "TANGGAL CLAIM PROMO",
                    key: 'created_at',
                    width: 250,
                    render(row) {
                        return dayjs(row.claim_date).format('dddd, D MMMM YYYY HH:mm')
                    }
                },
                {
                    title: "NOMOR KLAIM PROMO",
                    key: 'claim_number',
                    width: 250,
                },
                // {
                //     title: "NAMA DISTRIBUTOR",
                //     key: 'distributor',
                //     width: 250,
                // },
                {
                    title: "TOTAL KLAIM",
                    key: 'total_claim',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total_claim);
                    }
                },
                {
                    title: "STATUS",
                    key: 'status_claim',
                    width: 200,
                    render(row) {
                        const status = row.status_payment_claim;
                        let type: any;

                        switch (status) {
                            case 'PAID':
                                type = 'success';
                                break;
                            case 'UNPAID':
                                type = 'error';
                                break;
                            default:
                                type = ''; // Default type
                                break;
                        }

                        return h(
                            NTag,
                            {
                                // bordered: false,
                                type: type,
                                strong: true,
                            },
                            { default: () => status }
                        );
                    }
                },
                {
                    title: 'ACTION',
                    key: 'actions',
                    width: 100,
                    render(row) {
                        const status = row.status_payment_claim;
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
                                NButton,
                                {
                                    type: 'info',
                                    size: 'small',
                                    disabled: status === "PAID",
                                    onClick: () => {
                                        document_code.value = row.document_code;

                                        Swal.fire({
                                            icon: 'warning',
                                            title: document_code.value,
                                            text: "Ubah status menjadi PAID untuk promo ini?",
                                            showCancelButton: true,
                                            confirmButtonText: 'UBAH',
                                            cancelButtonText: 'BATAL',
                                            cancelButtonColor: 'red',
                                            confirmButtonColor: '#00a54f'
                                        }).then(result => {
                                            if (result.isConfirmed) {
                                                router.patch(route('finance.change-status', row.id),{},{
                                                    onSuccess: (page) => {
                                                        Swal.fire(page.props.flash.success,'','success');
                                                    },
                                                    onError: (error) => {
                                                        Swal.fire('Gagal memperbarui status, silahkan lapor pengembang','','error');
                                                    }
                                                })
                                            }
                                        });
                                    }
                                },
                                { default: () => 'Ubah Status' }
                            ),
                            h(
                                NButton,
                                {
                                    type: 'primary',
                                    size: 'small',
                                    onClick: () => {
                                        router.visit(route('finance.claim-promo.detail', row.id), { method: 'get' });
                                    }
                                },
                                { default: () => 'Detail' }
                            )
                        ]);
                    }
                }
            ];
        }

        return {
            columns: createColumns(),
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            showModal,
            document_code
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>