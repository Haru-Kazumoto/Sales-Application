<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Buat Transport" />
        <div class="card border-0 shdaow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Kode Transportasi
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Nama Transportasi
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Nomor Polisi
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.number_plate" />
                    </div>

                    <!-- Second row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Tipe
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.type" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Kelompok
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.group_name" disabled />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Nomor Telepon</label>
                        <n-input size="large" placeholder="" v-model:value="form.phone" />
                    </div>

                    <!-- Third row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Kota</label>
                        <n-input size="large" placeholder="" v-model:value="form.city" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-8 d-flex flex-column">
                        <label for="">Alamat</label>
                        <n-input size="large" placeholder="" type="textarea" v-model:value="form.address" />
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button class="ms-auto" type="primary" @click="handleSubmitTransport">Tambah data</n-button>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Satuan</label>
                <span class="border px-4 py-1 bg-white" style="border-radius: 3px;">
                    {{ ($page.props.transports as any).total }}
                </span>
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <div class="ms-auto d-flex align-items-lg-center gap-2 justify-content-lg-end w-100">
                    <label>Pencarian Data</label>
                    <n-select class="w-25" v-model:value="filterField" placeholder="Pilih field" :options="[
                        { label: 'Kode', value: 'code' },
                        { label: 'Nama', value: 'name' },
                        { label: 'Nomor Polisi', value: 'number_plate'}
                    ]" />
                    <n-input class="w-50" placeholder="" @input="handleSearch" v-model:value="filterQuery" />
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.transports as any).data"
                    pagination-behavior-on-filter="first" />
                <div class="d-flex mt-3">
                    <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                        :page-size="pagination.per_page"
                        :item-count="pagination.total" @update:page="handlePageChange"
                        @update:page-count="pagination.last_page = ($page.props.transports as any).last_page" />
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
import { Flash, Parties } from '../../types/model';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';



export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        function createColumns(): DataTableColumns<Parties> {
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
                    title: "KODE TRANSPORTASI",
                    key: "code",
                    width: 200,
                },
                {
                    title: "NAMA TRANSPORTASI",
                    key: "name",
                    width: 200,
                },
                {
                    title: "NOMOR POLISI",
                    key: "number_plate",
                    width: 170,
                },
                {
                    title: "TIPE",
                    key: "type_parties",
                    width: 100,
                },
                {
                    title: "KELOMPOK",
                    key: "group_name",
                    width: 150,
                    render(row) {
                        return row.parties_group.name;
                    }
                },
                {
                    title: "NOMOR TELEPON",
                    key: "phone",
                    width: 200,
                },
                {
                    title: "KOTA",
                    key: "city",
                    width: 150,
                },
                {
                    title: "ALAMAT",
                    key: "address",
                    width: 300,
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
                                        router.visit(route('admin.transport.edit', row.id));
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
                                            title: `Hapus transport ${row.name}?`,
                                            text: "Data akan dihapus secara permanen",
                                            icon: 'warning',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.transport.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: "Berhasil menghapus data transport",
                                                            duration: 1500,
                                                            closable: false,
                                                        });
                                                    },
                                                    onError: () => {
                                                        notification.error({
                                                            title: "Gagal menghapus data transport",
                                                            text: "Cek koneksi internet anda atau refresh page",
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
            code: '',
            name: '',
            number_plate: '',
            type: 'VENDOR',
            group_name: 'ANGKUTAN',
            phone: '',
            city: '',
            address: '',
        });

        // Filter data
        const filterField = ref('label'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        // Fungsi untuk meng-handle pencarian
        const handleSearch = () => {
            router.get(route('admin.create-transports'), {
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
            router.get(route('admin.create-transports'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, { preserveState: true }); // Request data for the selected page
        }

        const pagination = reactive({
            current_page: (page.props.transports as any).current_page,
            per_page: (page.props.transports as any).per_page,
            total: (page.props.transports as any).total,
            last_page: (page.props.transports as any).last_page,
        });

        function handleSubmitTransport() {
            form.post(route('admin.transport.post'), {
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
            handleSearch,
            handlePageChange,
            handleSubmitTransport,
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