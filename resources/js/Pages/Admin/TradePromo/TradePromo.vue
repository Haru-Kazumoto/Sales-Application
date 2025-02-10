<template>
    <div class="d-flex flex-column gap-4">

        <Head title="Promo Beli" />
        <TitlePage title="Program Alokasi SFM" />
        <!-- <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            NAMA PELANGGAN
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.grosir_account"
                            @input="(value) => form.grosir_account = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            HARGA TRADE PROMO
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.discount_price"
                            @input="(value) => form.discount_price = value.replace(/\D/g, '')">
                            <template #prefix>Rp</template>
</n-input>
</div>
<div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
    <label for="grosir_account">
        JUMLAH KUOTA
        <RequiredMark />
    </label>
    <n-input size="large" placeholder="" v-model:value="form.quota"
        @input="(value) => form.quota = value.replace(/\D/g, '')" />
</div>
</div>

<n-button class="ms-auto" type="primary" size="large" @click="handleSubmitTradePromo">Tambah promo
    beli</n-button>
</div>
</div> -->

        <n-modal v-model:show="modalOpen" :mask-closable="false" class="d-flex" preset="card" :style="bodyStyle"
            title="UPDATE PROMO BELI" :bordered="false" size="huge" :segmented="segmented">

            <div class="row g-4">
                <div class="col-12 d-flex flex-column gap-1">
                    <label for="">NAMA AKUN GROSIR
                        <RequiredMark />
                    </label>
                    <n-input placeholder="" size="large" v-model:value="updateForm.grosir_account"></n-input>
                </div>
                <div class="col-12 d-flex flex-column gap-1">
                    <label for="grosir_account">
                        HARGA SETELAH DISKON
                        <RequiredMark />
                    </label>
                    <n-input size="large" placeholder="" v-model:value="updateForm.discount_price"
                        @input="(value) => updateForm.discount_price = value.replace(/\D/g, '')">
                        <template #prefix>Rp</template>
                    </n-input>
                </div>
                <div class="col-12 d-flex flex-column gap-1">
                    <label for="grosir_account">
                        JUMLAH KUOTA
                        <RequiredMark />
                    </label>
                    <n-input size="large" placeholder="" v-model:value="updateForm.quota"
                        @input="(value) => updateForm.quota = value.replace(/\D/g, '')" />
                </div>
            </div>

            <template #footer>
                <div class="d-flex">
                    <div class="d-flex gap-2 ms-auto">
                        <n-button type="error" size="large" @click="modalOpen = false">Batal</n-button>
                        <n-button type="info" size="large" @click="handleUpdateTradePromo">Simpan</n-button>
                    </div>
                </div>
            </template>
        </n-modal>

        <n-button class="ms-auto" type="primary" size="large"
            @click="router.visit(route('admin.trade-promo.create'))">BUAT
            TRADE PROMO BARU</n-button>
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex">
                <n-data-table :bordered="false" :columns :data="$page.props.data" />
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Trade Promo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>NAMA PELANGGAN</strong></td>
                                    <td>:</td>
                                    <td>{{ selectedData.grosir_account }}</td>
                                </tr>
                                <tr>
                                    <td><strong>HARGA SETELAH DISKON</strong></td>
                                    <td>:</td>
                                    <td>{{ formatRupiah(selectedData.discount_price) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>JUMLAH KUOTA</strong></td>
                                    <td>:</td>
                                    <td>{{ selectedData.quota }}</td>
                                </tr>
                                <tr>
                                    <td><strong>STATUS AKTIF</strong></td>
                                    <td>:</td>
                                    <td>{{ selectedData.is_active ? "AKTIF" : "NON AKTIF"  }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <n-divider>BARANG BARANG</n-divider>
                        <table class="table table-borderless">
                            <tbody v-for="(item, index) in selectedData.products" :key="item.id">
                                <tr>
                                    <td><strong>NAMA BARANG</strong></td>
                                    <td>:</td>
                                    <td>{{ item.name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>KODE BARANG</strong></td>
                                    <td>:</td>
                                    <td>{{ item.code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>UNIT BARANG</strong></td>
                                    <td>:</td>
                                    <td>{{ item.unit }}</td>
                                </tr>
                                <tr v-if="index < selectedData.products.length - 1">
                                    <td colspan="3">
                                        <hr>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">TUTUP</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import TitlePage from '../../../Components/TitlePage.vue';
import RequiredMark from '../../../Components/RequiredMark.vue';
import ErrorInput from '../../../Components/ErrorInput.vue';
import { defineComponent, ref, h } from 'vue';
import { NButton, NTag, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import { Flash } from '../../../types/model';
import { formatRupiah } from '../../../Utils/options-input.utils';

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
                    title: "DETAIL",
                    key: "detail",
                    width: 70,
                    render(row) {
                        return h(NButton, {
                            type: 'info',
                            size: 'medium',
                            onClick: () => {
                                openDetail(row);
                            }
                        }, { default: () => "DETAIL" });
                    }
                },
                {
                    title: "NAMA AKUN GROSIR",
                    key: "grosir_account",
                    width: 200
                },
                {
                    title: "HARGA SETELAH DISKON",
                    key: "discount_price",
                    width: 150,
                    render(row) {
                        return formatRupiah(row.discount_price);
                    }
                },
                {
                    title: "JUMLAH KUOTA",
                    key: "quota",
                    width: 150,
                },
                {
                    title: "STATUS PROMO",
                    key: "is_active",
                    width: 150,
                    render(row) {
                        return h(
                            NTag,
                            {
                                type: row.is_active ? "success" : "error",
                                size: "large",
                                strong: true,
                                bordered: true,
                            },
                            {
                                default: () => row.is_active ? "AKTIF" : "TIDAK AKTIF",
                            }
                        )
                    }
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 150,
                    render(row) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
                                NButton,
                                {
                                    type: "info",
                                    size: "medium",
                                    disabled: row.is_active === 0,
                                    onClick: () => {
                                        router.visit(route('admin.trade-promo.edit', row.id));
                                    }
                                },
                                {
                                    default: () => "UPDATE"
                                }
                            ),
                            h(
                                NButton,
                                {
                                    type: "error",
                                    size: "medium",
                                    disabled: row.is_active === 0,
                                    onClick: () => {
                                        handleDeleteTradePromo(row.id);
                                    }
                                },
                                {
                                    default: () => "HAPUS"
                                }
                            )
                        ]);
                    }
                }
            ]
        }

        const selectedData = ref({});
        const form = useForm({
            grosir_account: null as unknown as string,
            quota: null as unknown as number,
            discount_price: null as unknown as number,
        });
        const updateForm = useForm({
            grosir_account: null as unknown as string,
            quota: null as unknown as number,
            discount_price: null as unknown as number,
        });
        const modalOpen = ref(false);
        const idTradePromo = ref(null as unknown as number);
        const notification = useNotification();

        function handleUpdate(row) {
            idTradePromo.value = row.id;
            updateForm.grosir_account = row.grosir_account;
            updateForm.quota = row.quota;
            updateForm.discount_price = row.discount_price;
            modalOpen.value = true;
        }

        function openDetail(row) {
            selectedData.value = row;

            const modal = new bootstrap.Modal(document.getElementById('exampleModal'));

            modal.show();

            (selectedData.value);
        }

        function handleDeleteTradePromo(trade_promo_id) {
            form.delete(route('admin.trade-promo.delete', trade_promo_id), {
                onSuccess: (page) => {
                    notification.success({
                        title: page.props.flash.success,
                        duration: 2000,
                        closable: false,
                    });
                },
                onError: () => {
                    notification.error({
                        title: "Oops, server sibuk :(",
                        meta: "Silahkan lapor developer",
                        closable: false,
                        duration: 3000
                    });
                }
            });
        }

        function handleSubmitTradePromo() {
            form.post(route('admin.trade-promo.store'), {
                onError: () => {
                    Swal.fire('Periksa kembali formnya', 'Form dengan tanda <span class="text-danger">*</span> harus diisi!', 'error');
                },
                onSuccess: (page) => {
                    form.reset();
                    Swal.fire({
                        title: (page.props.flash as Flash).success,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                },
            })
        }

        function handleUpdateTradePromo() {
            updateForm.put(route('admin.trade-promo.update', idTradePromo.value), {
                onError: () => {
                    notification.error({
                        title: "Oops, server sibuk :(",
                        meta: "Silahkan lapor developer",
                        closable: true,
                    });
                },
                onSuccess: (page) => {
                    modalOpen.value = false;

                    notification.success({
                        title: page.props.flash.success,
                        duration: 2000,
                        closable: false,
                    });

                    updateForm.reset();
                    idTradePromo.value = null as unknown as number;
                }
            })
        }

        function handleShutdownPromo(trade_promo_id: number) {
            Swal.fire({
                title: "Non-aktifkan promo ini?",
                text: "Setelah di non-aktifkan promo ini tidak bisa dipakai lagi",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: 'red',
            }).then((result) => {
                if (result.isConfirmed) {
                    router.patch(route('admin.trade-promo.shutdown', trade_promo_id), {
                        _method: "PATCH",
                    }, {
                        onSuccess: (page) => {
                            Swal.fire({
                                title: page.props.flash.success,
                                icon: "success",
                                timer: 1700,
                                timerProgressBar: true
                            });
                        }
                    });
                }
            })

        }

        return {
            columns: createColumns(),
            handleUpdateTradePromo,
            handleSubmitTradePromo,
            handleDeleteTradePromo,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
            form,
            modalOpen,
            idTradePromo,
            updateForm,
            router,
            selectedData,
            formatRupiah
        }
    },
    components: {
        Head,
        TitlePage,
        RequiredMark,
        ErrorInput,
    }
})
</script>

<style scoped></style>