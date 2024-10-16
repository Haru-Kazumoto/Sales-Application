<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Buat Satuan Unit" />
        <div class="card border-0 shdaow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="">Kode Unit
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.value" @input="(value) => form.value = value.toUpperCase()"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="">Nama Unit
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.label" />
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button class="ms-auto" type="primary" @click="handleSubmitTransport">Tambah data</n-button>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Transport</label>
                <span class="border px-4 py-1 bg-white" style="border-radius: 3px;">
                    {{ $page.props.units.total }}
                </span>
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <div class="ms-auto d-flex align-items-lg-center gap-2 justify-content-lg-end w-100">
                    <label>Pencarian Data</label>
                    <n-input class="w-50" size="large" placeholder=""/>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="($page.props.units as any).data"
                    size="small" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import RequiredMark from '../../Components/RequiredMark.vue';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { Flash, Lookup, Parties } from '../../types/model';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        function createColumns(): DataTableColumns<Lookup> {
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
                    title: "KODE UNIT",
                    key: 'value',
                    width: 200,
                },
                {
                    title: "NAMA",
                    key: "label",
                    width: 200,
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
                                    size: 'small',
                                    onClick() {
                                        router.visit(route('admin.unit.edit', row.id));
                                    }
                                },
                                { default: () => "EDIT" }
                            ),
                            h(
                                NButton,
                                {
                                    type: "error",
                                    size: 'small',
                                    onClick() {
                                        Swal.fire({
                                            title: `Hapus unit ${row.label}?`,
                                            text: "Data akan dihapus secara permanen",
                                            icon: 'warning',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.unit.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: "Berhasil menghapus data unit",
                                                            duration: 1500,
                                                            closable: false,
                                                        });
                                                    },
                                                    onError: () => {
                                                        notification.error({
                                                            title: "Gagal menghapus data transport",
                                                            meta: "Cek koneksi internet anda atau refresh page",
                                                            duration: 1700,
                                                            closable: false,
                                                        });
                                                    }
                                                });

                                            }
                                        })
                                    }
                                },
                                { default: () => "HAPUS" }
                            )
                        ])
                    }
                }
            ]
        }

        const form = useForm({
            label: "",
            value: "",
        });

        function handleSubmitTransport() {
            form.post(route('admin.unit.post'), {
                onError: () => {
                    Swal.fire('Cek kembali form', 'form dengan <span style="color: red;">*</span> harus di isi', 'error');
                },
                onSuccess: () => {
                    form.reset();

                    notification.success({
                        title: (page.props.flash as Flash).success,
                        duration: 1500,
                        closable: false,
                    })
                },
            });
        }

        return {
            columns: createColumns(),
            handleSubmitTransport,
            form,
        }
    },
    components: {
        TitlePage,
        RequiredMark,
    }
})
</script>

<style scoped></style>