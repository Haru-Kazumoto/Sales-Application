<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Ekspedisi Eksternal" />
        
        <div class="d-flex">
            <n-button type="primary" size="large" class="ms-auto" @click="router.visit(route('admin.create-transports'))">Buat Data Baru</n-button>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Transportasi</label>
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
                    <n-input class="w-50" placeholder="" @keyup.enter="handleSearch" v-model:value="filterQuery" />
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.transports as any).data"
                    pagination-behavior-on-filter="first" />
                <div class="d-flex mt-3">
                    <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                        :page-size="pagination.per_page" @update:page="handlePageChange"
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
                    title: "NAMA PIC 1",
                    key: "pic",
                    width: 200,
                },
                {
                    title: "NOMOR PIC 1",
                    key: "phone",
                    width: 200,
                },
                {
                    title: "NAMA PIC 2",
                    key: "pic_2",
                    width: 200,
                },
                {
                    title: "NOMOR PIC 2",
                    key: "phone_2",
                    width: 200,
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
            type: 'TRANSPORT',
            group_name: 'ANGKUTAN',
            phone: '',
            city: '',
            address: '',
        });

        // Filter data
        const filterField = ref('code'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        // Fungsi untuk meng-handle pencarian
        const handleSearch = () => {
            router.get(route('admin.index-transports'), {
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
            router.get(route('admin.index-transports'), {
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
            router
        }
    },
    components: {
        TitlePage,
        RequiredMark,
    }
})
</script>

<style scoped></style>