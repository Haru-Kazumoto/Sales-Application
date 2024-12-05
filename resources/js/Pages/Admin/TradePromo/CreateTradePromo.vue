<template>
    <div class="d-flex flex-column gap-4">

        <Head title="Promo Beli" />
        <TitlePage title="Program Promo Beli" />
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            Akun Grosir
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder=""  v-model:value="form.grosir_account" @input="(value) => form.grosir_account = value.toUpperCase()"/>
                        <!-- <ErrorInput :error="$page.props.errors['grosir_account']" /> -->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            Harga setelah diskon
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.discount_price" @input="(value) => form.discount_price = value.replace(/\D/g, '')">
                            <template #prefix>Rp</template>
                        </n-input>
                        <!-- <ErrorInput :error="$page.props.errors['discount_price']" /> -->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="grosir_account">
                            Jumlah Kuota
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder=""  v-model:value="form.quota" @input="(value) => form.quota = value.replace(/\D/g, '')"/>
                        <!-- <ErrorInput :error="$page.props.errors['quota']" /> -->
                    </div>
                </div>

                <n-button class="ms-auto" type="primary" size="large" @click="handleSubmitTradePromo">Tambah promo beli</n-button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns :data="$page.props.data.data" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import TitlePage from '../../../Components/TitlePage.vue';
import RequiredMark from '../../../Components/RequiredMark.vue';
import ErrorInput from '../../../Components/ErrorInput.vue';
import { defineComponent } from 'vue';
import { h } from 'vue';
import { NButton, NTag } from 'naive-ui';
import Swal from 'sweetalert2';
import { Flash } from '../../../types/model';
import {formatRupiah} from '../../../Utils/options-input.utils';

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
                                    onClick: () => {
                                        alert('clicked' + row.id);
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
                                    onClick: () => {
                                        handleShutdownPromo(row.id);
                                    }
                                },
                                {
                                    default: () => "NON-AKTIFKAN"
                                }
                            )
                        ]);
                    }
                }
            ]
        }

        const form = useForm({
            grosir_account: null as unknown as string,
            quota: null as unknown as number,
            discount_price: null as unknown as number,
        });

        function handleSubmitTradePromo() {
            form.post(route('admin.trade-promo.store'),{
                onError: () => {
                    Swal.fire('Periksa kembali formnya','Form dengan tanda <span class="text-danger">*</span> harus diisi!', 'error');
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

        function handleShutdownPromo(trade_promo_id: number) {
            Swal.fire({
                title: "Non-aktifkan promo ini?",
                text: "Setelah di non-aktifkan promo ini tidak bisa dipakai lagi",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: 'red',
            }).then((result) => {
                if(result.isConfirmed) {
                    router.patch(route('admin.trade-promo.shutdown', trade_promo_id),{
                        _method: "PATCH",
                    },{
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
            handleSubmitTradePromo,
            form
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