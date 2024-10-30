<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Detail Booking" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('warehouse.booking-request'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NOMOR BOOKING</label>
                        <n-input v-model:value="detail.booking_number" readonly size="large" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">TANGGAL BOOKING</label>
                        <n-input v-model:value="detail.booking_date" readonly size="large" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">SALESMAN</label>
                        <n-input v-model:value="detail.salesman" readonly size="large" />
                    </div>
                    <div class="col-6 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">STATUS</label>
                        <n-input v-model:value="detail.status" size="large" :status="statusBorder" readonly />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <span class="fs-5 fw-semibold">Barang yang di booking</span>
                <n-data-table :bordered="false" :columns="columns" :data="$page.props.booking_request_products" />
            </div>
        </div>

        <div class="d-flex">
            <n-button type="primary" size="large" class="ms-auto"
                @click="handleSubmit">APPROVE</n-button>
        </div>

        <n-modal v-model:show="showModal">
            <n-card style="width: 500px" :title="`Reject ${description.product_name}`" :bordered="false" size="huge"
                role="dialog" aria-modal="true">
                <div class="d-flex flex-column gap-1">
                    <label for="number_plate" class="fs-5">Deskripsi</label>
                    <n-input v-model:value="description.value" placeholder="" type="textarea" size="large"
                        @input="(value) => description.value = value.toUpperCase()" />
                </div>
                <template #footer>
                    <div class="d-flex gap-2 justify-content-center">
                        <n-button type="error" size="large" @click="showModal = false">Batal</n-button>
                        <n-button type="info" size="large"
                            @click="rejectItem(description.transaction_id)">Submit</n-button>
                    </div>
                </template>
            </n-card>
        </n-modal>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { ArrowBack } from '@vicons/ionicons5';
import { router, useForm, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia
import { h } from 'vue';
import { NButton, NTag, useNotification } from 'naive-ui';
import { Flash } from '../../../types/model';
import Swal from 'sweetalert2';

dayjs.locale('id'); // Set locale to Indonesian

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
                    title: "NAMA PRODUK",
                    key: "product_name",
                    width: 150,
                    render(row) {
                        return row.product.name;
                    }
                },
                {
                    title: "KODE PRODUK",
                    key: "code",
                    width: 150,
                    render(row) {
                        return row.product.code;
                    }
                },
                {
                    title: "QUANTITY",
                    key: "quantity",
                    width: 150,
                },
                {
                    title: "STATUS",
                    key: "status",
                    width: 150,
                    render(row) {
                        const status = row.status_booking;

                        let type;

                        switch (status) {
                            case "PENDING":
                                type = "warning";
                                break;
                            case "APPROVED":
                                type = "success";
                                break;
                            case "REJECTED":
                                type = "error";
                                break;
                            case "RELEASE_CO":
                                type = "info";
                                break;
                            default:
                                type = "success";
                                break;
                        }

                        return h(
                            NTag, {
                            type,
                            bordered: true,
                            strong: true,
                        },
                            { default: () => status }
                        )
                    }
                },
                {
                    title: "DESKRIPSI",
                    key: "description",
                    width: 200,
                    render(row) {
                        return row.reject_description;
                    }
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 150,
                    render(row, index) {
                        const status = row.status_booking;

                        return h('div', { class: "d-flex gap-2" }, [
                            h(
                                NButton,
                                {
                                    type: "primary",
                                    disabled: status !== "PENDING"
                                },
                                { default: () => "APPROVE" }
                            ),
                            h(
                                NButton,
                                {
                                    type: "error",
                                    disabled: status !== "PENDING",
                                    onClick: () => {
                                        handleSetData(row);
                                        showModal.value = true;
                                    }
                                },
                                { default: () => "REJECT" }
                            )
                        ])
                    }
                }
            ]
        }

        const page = usePage();
        const notification = useNotification();
        const booking_detail = page.props.transactions as any;
        const booking_request_products = page.props.booking_request_products as any[];
        const showModal = ref(false);
        const description = useForm({
            transaction_id: null as unknown as number,
            value: '',
            product_name: '',
        });

        const booking_date = booking_detail.transaction_details.find(data => data.category === "Booking Date")?.value;
        const salesman = booking_detail.transaction_details.find(data => data.category === "Salesman")?.value;
        const status = booking_detail.transaction_details.find(data => data.category === "Check")?.value;

        const borderStatus = ref({ status: status });
        const statusBorder = computed(() => {
            switch (borderStatus.value.status) {
                case "PENDING":
                    return 'warning';
                case "APPROVED":
                    return 'success';
                case "REJECTED":
                    return 'error';
                default:
                    return null;
            }
        });

        function handleSetData(row: any) {
            description.transaction_id = row.transaction.id;
            description.product_name = row.product.name;
        }

        function approveItem(tx_id: number) {
            router.patch(route('warehouse.approve-request', tx_id), {}, {
                onSuccess: (page) => {
                    notification.success({ title: (page.props.flash as Flash).success, duration: 1500, closable: false });
                },
                onError: () => {
                    notification.error({ title: "Gagal memperbarui", duration: 1500, closable: false });
                }
            });
        }

        function rejectItem(tx_id: number) {
            description.patch(route('warehouse.set-reject-description', tx_id), {
                onSuccess: (page) => {
                    notification.success({ title: (page.props.flash as Flash).success, duration: 1500, closable: false });
                    showModal.value = false;
                },
                onError: () => {
                    notification.error({ title: "Gagal memperbarui", duration: 1500, closable: false });
                }
            });
        }

        function handleSubmit() {
            // Mengecek jika ada item dengan status_booking "PENDING"
            const hasPending = booking_request_products.some(data => data.status_booking === "PENDING");

            if (hasPending) {
                // Tampilkan pesan error menggunakan Swal jika ada status PENDING
                Swal.fire('Tidak bisa submit', 'Terdapat item dengan status PENDING yang belum diproses.', 'error');
            } else {
                // Jika tidak ada item yang PENDING, lanjutkan ke request
                router.patch(route('warehouse.approve-request', booking_detail.id), {}, {
                    onSuccess: (page) => {
                        Swal.fire((page.props.flash as Flash).success, '', 'success');
                    },
                    onError: () => {
                        Swal.fire('Gagal memperbarui', '', 'error');
                    }
                });
            }
        }

        const detail = ref({
            booking_date: dayjs(booking_date).format('dddd, D MMMM YYYY HH:mm'),
            booking_number: booking_detail.document_code,
            salesman,
            status,
            products: [] as any[]
        });

        return {
            columns: createColumns(),
            approveItem,
            rejectItem,
            handleSubmit,
            statusBorder,
            router,
            detail,
            description,
            showModal,
            booking_detail,
            ArrowBack
        }
    },
    components: {
        TitlePage
    }
})
</script>