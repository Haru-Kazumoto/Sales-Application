<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Buat Satuan Unit" />
        <div class="card border-0 shdaow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="">Kode Driver
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.code"
                            @input="(value) => form.code = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="">Nama Driver
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.name" @input="(value) => form.name = value.toUpperCase()"/>
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button class="ms-auto" type="primary" @click="handleSubmitUnit">Tambah data</n-button>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Driver</label>
                <span class="border px-4 py-1 bg-white" style="border-radius: 3px;">
                    {{ ($page.props.drivers as any).total }}
                </span>
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <div class="ms-auto d-flex align-items-lg-center gap-2 justify-content-lg-end w-100">
                    <label>Pencarian Data</label>
                    <n-select class="w-25" v-model:value="filterField" placeholder="Pilih field" :options="[
                        { label: 'Kode', value: 'code' },
                        { label: 'Nama', value: 'name' },
                    ]" />
                    <n-input class="w-50" placeholder="" @input="handleSearch"
                        v-model:value="filterQuery" />
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.drivers as any).data"
                    pagination-behavior-on-filter="first" />
                <div class="d-flex mt-3">
                    <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                        :page-size="pagination.per_page"
                        :item-count="pagination.total" @update:page="handlePageChange"
                        @update:page-count="pagination.last_page = ($page.props.drivers as any).last_page" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, reactive } from 'vue';
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
                    title: "KODE DRIVER",
                    key: 'code',
                    width: 200,
                },
                {
                    title: "NAMA DRIVER",
                    key: "name",
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
                                        router.visit(route('admin.driver.edit', row.id));
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
                                            title: `Hapus driver ${row.name}?`,
                                            text: "Data akan dihapus secara permanen",
                                            icon: 'warning',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.driver.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: "Berhasil menghapus data driver",
                                                            duration: 1500,
                                                            closable: false,
                                                        });
                                                    },
                                                    onError: () => {
                                                        notification.error({
                                                            title: "Gagal menghapus data driver",
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
            code: "",
            name: "",
        });

        // Filter data
        const filterField = ref('name'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        // Fungsi untuk meng-handle pencarian
        const handleSearch = () => {
            router.get(route('admin.create-driver'), {
                page: pagination.current_page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, {
                preserveState: true,
                replace: true
            });
        };

        // Function to handle page change
        function handlePageChange(page: number) {
            router.get(route('admin.create-driver'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, { preserveState: true }); // Request data for the selected page
        }

        function handleSubmitUnit() {
            form.post(route('admin.driver.post'), {
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

        const pagination = reactive({
            current_page: (page.props.drivers as any).current_page,
            per_page: (page.props.drivers as any).per_page,
            total: (page.props.drivers as any).total,
            last_page: (page.props.drivers as any).last_page,
        });

        return {
            columns: createColumns(),
            handleSearch,
            handlePageChange,
            handleSubmitUnit,
            form,
            filterField,
            filterQuery,
            pagination,
        }
    },
    components: {
        TitlePage,
        RequiredMark,
    }
})
</script>

<style scoped></style>