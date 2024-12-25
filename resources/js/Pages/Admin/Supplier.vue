<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Supplier" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4 d-flex flex-column">
                        <h4>Tambah data dari excel</h4>
                        <label for="file-upload" class="form-label">Upload File Excel</label>
                        <input type="file" id="file-upload" accept=".xlsx" class="form-control" @change="handleChangeFile"/>
                        <small class="text-muted">
                            Hanya file Excel (.xlsx) yang diperbolehkan.
                        </small>
                        <n-button type="primary" class="mt-3 w-25" @click="handleImportProducts">Import</n-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitCustomer">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kode Pemasok
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" size="large" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Nama Pemasok
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" size="large" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Badan Usaha
                            <RequiredMark />
                        </label>
                        <n-input placeholder="" size="large" v-model:value="form.legality" />
                    </div>

                    <!-- Second row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Tipe
                            <RequiredMark />
                        </label>
                        <n-select size="large" v-model:value="form.type_parties" :options="supplier_type" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kelompok
                            <RequiredMark />
                        </label>
                        <n-select size="large" v-model:value="form.parties_group_id" placeholder="" :options="groups" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">NPWP</label>
                        <n-input placeholder="" size="large" v-model:value="form.npwp" />
                    </div>

                    <!-- Third row -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">No. Telpon</label>
                        <n-input placeholder="" size="large" v-model:value="form.phone" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kota</label>
                        <n-input placeholder="" size="large" v-model:value="form.city" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Alamat</label>
                        <n-input placeholder="" size="large" v-model:value="form.address" />
                    </div>

                    <div class="d-flex">
                        <n-button type="primary" class="ms-auto" attr-type="submit">Tambah Data</n-button>
                    </div>
                </form>

            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3">
                <label>Total Jumlah Customer</label>
                <span class="border px-4 py-1 bg-white" style="border-radius: 3px;">
                    {{ ($page.props.parties as any).total }}
                </span>
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <div class="ms-auto d-flex align-items-lg-center gap-2 justify-content-lg-end w-100">
                    <label>Pencarian Data</label>
                    <n-select class="w-25" v-model:value="filterField" placeholder="Pilih field" :options="[
                        { label: 'Nama', value: 'name' },
                        { label: 'Tipe', value: 'type_parties' },
                        { label: 'Nomor Telepon', value: 'phone' }
                    ]" />
                    <n-input class="w-50" placeholder="" @input="handleSearch"
                        v-model:value="filterQuery" />
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.parties as any).data"
                    pagination-behavior-on-filter="first" />
                <div class="d-flex mt-3">
                    <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                        :page-count="pagination.last_page" :page-size="pagination.per_page"  @update:page="handlePageChange"
                        @update:page-count="pagination.last_page = ($page.props.parties as any).last_page" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h, watch, reactive } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import RequiredMark from '../../Components/RequiredMark.vue';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { Flash, Lookup, Parties, PartiesGroup } from '../../types/model';
import Swal from 'sweetalert2';


export default defineComponent({
    setup() {
        const page = usePage();
        const currentPage = ref(1);
        const notification = useNotification();

        function createColumns(): DataTableColumns<Parties> {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "KODE PEMASOK",
                    key: 'code',
                    width: 260,
                },
                {
                    title: "NAMA PEMASOK",
                    key: 'name',
                    width: 250,
                },
                {
                    title: "BADAN USAHA",
                    key: 'legality',
                    width: 150,
                },
                {
                    title: "TIPE",
                    key: 'type_parties',
                    width: 150,
                },
                {
                    title: "KELOMPOK",
                    key: "parties_group",
                    width: 150,
                    render(row) {
                        return row.parties_group?.name;
                    }
                },
                {
                    title: "NO TELP",
                    key: 'phone',
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
                    width: 250,
                },
                {
                    title: "NPWP",
                    key: "npwp",
                    width: 200,
                },
                {
                    title: "ACTION",
                    key: 'action',
                    width: 100,
                    render(row) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
                                NButton,
                                {
                                    type: 'info',
                                    onClick() {
                                        router.get(route('admin.parties.supplier.edit', row.id));
                                    }
                                },
                                { default: () => "UPDATE" }
                            ),
                            h(
                                NButton,
                                {
                                    type: "error",
                                    onClick() {
                                        Swal.fire({
                                            icon: 'question',
                                            title: `Hapus supplier ${row.name}?`,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.parties.supplier.delete', row.id), {
                                                    onSuccess() {
                                                        notification.success({
                                                            title: "Supplier berhasil dihapus",
                                                            duration: 1500,
                                                            closable: false,
                                                        });
                                                    }
                                                })
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

        const form = useForm<Parties>({
            code: '',
            legality: '',
            name: '',
            address: "",
            city: '',
            country: '',
            contact_person: '',
            description: '',
            npwp: '',
            email: "",
            fax: '',
            handphone: '',
            phone: "",
            website: "",
            postal_code: '',
            province: '',
            // term_payment: null as unknown as number,
            type_parties: '',
            parties_group_id: null as unknown as number,
        });

        // Filter data
        const filterField = ref('name'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        // Fungsi untuk meng-handle pencarian
        const handleSearch = () => {
            router.get(route('admin.parties.supplier'), {
                page: pagination.current_page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, {
                preserveState: true,
                replace: true
            });
        };

        const file = useForm({
            attachment: null as unknown as any,
        });

        function handleChangeFile(event) {
            file.attachment = event.target.files[0];
        }

        function handleImportProducts() {
            file.post(route('admin.vendors.import'), {
                onSuccess: (page) => {
                    Swal.fire({
                        title: 'Berhasil memasukan data!',
                        text: 'Silahkan cek list data',
                        icon: 'success'
                    });
                },
                onError: () => {
                    Swal.fire('Gagal memasukan data!', 'Silahkan lapor developer', 'error');
                }
            });
        }

        // Function to handle page change
        function handlePageChange(page: number) {
            router.get(route('admin.parties.supplier'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, { preserveState: true }); // Request data for the selected page
        }

        function handleSubmitCustomer() {
            form.post(route('admin.parties.supplier.post'), {
                preserveScroll: false,
                onSuccess: () => {
                    form.reset();
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        closable: false,
                        duration: 1500,
                    });
                },
                onError: () => {
                    Swal.fire('Cek form kembali!', 'Form dengan <span class="text-danger">*</span> harus di isi!', 'error');
                }
            });
        }

        const pagination = reactive({
            current_page: (page.props.parties as any).current_page,
            per_page: (page.props.parties as any).per_page,
            total: (page.props.parties as any).total,
            last_page: (page.props.parties as any).last_page,
        });

        const groups = (page.props.groups as PartiesGroup[]).map((data) => ({
            id: data.id,
            label: data.name,
            value: data.id,
        }));

        const supplier_type = (page.props.supplier_type as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        return {
            handleSubmitCustomer,
            handlePageChange,
            handleSearch,
            columns: createColumns(),
            handleChangeFile,
            handleImportProducts,
            currentPage,
            form,
            groups,
            supplier_type,
            pagination,
            filterField,
            filterQuery,
        }
    },
    components: {
        TitlePage,
        RequiredMark
    }
})
</script>

<style scoped></style>