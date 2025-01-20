<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Customer" />

        <n-tabs type="line" animated size="large">
            <n-tab-pane name="list" tab="Daftar Customer">
                <div class="row g-3 mb-3">
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
                                :page-count="pagination.last_page" :page-size="pagination.per_page"
                                @update:page="handlePageChange"
                                @update:page-count="pagination.last_page = $page.props.parties.last_page" />
                        </div>
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="create" tab="Buat Customer Baru">
                <div class="d-flex flex-column gap-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-4 d-flex flex-column">
                                    <h4>Tambah data dari excel</h4>
                                    <label for="file-upload" class="form-label">Upload File Excel</label>
                                    <input type="file" id="file-upload" accept=".xlsx" class="form-control"
                                        @change="handleChangeFile" />
                                    <small class="text-muted">
                                        Hanya file Excel (.xlsx) yang diperbolehkan.
                                    </small>
                                    <div class="d-flex mt-2 gap-3">
                                        <n-button type="primary" class="" @click="handleImportProducts">Import Data</n-button>
                                        <n-button type="info" class="" @click="handleDownloadTemplateExcel">Download Template</n-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <form class="row g-3" @submit.prevent="handleSubmitCustomer">
                                <!-- First row -->
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Kode Customer</label>
                                    <n-input placeholder="" size="large" v-model:value="form.code" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Nama Customer</label>
                                    <n-input placeholder="" size="large" v-model:value="form.name"
                                        :on-input="(value) => form.name = value.toUpperCase()" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Badan Usaha</label>
                                    <n-select placeholder="" size="large" v-model:value="form.legality" :options="[
                                        { label: 'PT', value: 'PT' },
                                        { label: 'UD', value: 'UD' },
                                        { label: 'CV', value: 'CV' },
                                        { label: 'PERORANGAN', value: 'PERORANGAN' },
                                    ]" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Segmen Customer</label>
                                    <n-select placeholder="" size="large" v-model:value="form.segment_customer"
                                        :on-input="(value) => form.segment_customer = value.toUpperCase()"
                                        :options="segmentCustomer" />
                                </div>
            
                                <!-- Second row -->
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Tipe</label>
                                    <n-select placeholder="" size="large" v-model:value="form.type_parties"
                                        :options="customer_type" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Channel Customer</label>
                                    <n-select placeholder="" size="large" v-model:value="form.parties_group_id" :options="groups" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Termin Pembayaran</label>
                                    <n-input placeholder="" size="large" v-model:value="form.term_payment"
                                        :on-input="(value) => form.term_payment = value.replace(/\D/g, '')">
                                        <template #suffix>
                                            HARI
                                        </template>
                                    </n-input>
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">NPWP</label>
                                    <n-input placeholder="" size="large" v-model:value="form.npwp" />
                                </div>
            
                                <!-- Third row -->
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">No. Telpon</label>
                                    <n-input placeholder="" size="large" v-model:value="form.phone"
                                        :on-input="(value) => form.phone = value.replace(/\D/g, '')" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Kota</label>
                                    <n-input placeholder="" size="large" v-model:value="form.city"
                                        :on-input="(value) => form.city = value.toUpperCase()" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Alamat</label>
                                    <n-input placeholder="" size="large" v-model:value="form.address" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                                    <label for="nama_customer" class="form-label">Perusahaan</label>
                                    <n-select placeholder="" size="large" :options="[
                                        { label: 'DNP', value: 'DNP' },
                                        { label: 'DKU', value: 'DKU' }
                                    ]" v-model:value="form.company" />
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-2">
                                    <label for="taxpayer">Wajib Pajak</label>
                                    <n-select size="large" clearable :options="[
                                        { label: 'PKP', value: 'PKP' },
                                        { label: 'NON-PKP', value: 'NON_PKP' }
                                    ]" v-model:value="form.taxpayer"></n-select>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="ktpImage" class="form-label">Upload Foto KTP</label>
                                    <input type="file" id="ktpImage" class="form-control" accept="image/*"
                                        @change="handleFileChange('ktp_image', $event)" />
                                    <!-- <div v-if="form.ktp_image">
                                        <n-image :src="form.npwp_image" alt="Preview NPWP" width="auto" height="200" />
                                    </div> -->
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="npwpImage" class="form-label">Upload Foto NPWP</label>
                                    <input type="file" id="npwpImage" class="form-control" accept="image/*"
                                        @change="handleFileChange('npwp_image', $event)" />
                                    <!-- <div v-if="form.npwp_image">
                                        <n-image :src="form.ktp_image" alt="Preview NPWP" width="auto" height="200" />
                                    </div> -->
                                </div>
            
            
                                <div class="d-flex">
                                    <n-button type="primary" class="ms-auto" attr-type="submit">Tambah Data</n-button>
                                </div>
                            </form>
            
                        </div>
                    </div>
                </div>
            </n-tab-pane>
        </n-tabs>

        <!-- LIST -->

    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h, reactive } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NImage, NTag, useNotification } from 'naive-ui';
import { Flash, Lookup, Parties, PartiesGroup } from '../../types/model';
import Swal from 'sweetalert2';


export default defineComponent({
    setup() {
        const page = usePage();
        const currentPage = ref(1);
        const notification = useNotification();

        function createColumns() {
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
                    title: "KODE CUSTOMER",
                    key: 'code',
                    width: 180,
                },
                {
                    title: "NAMA CUSTOMER",
                    key: 'name',
                    width: 200,
                },
                {
                    title: "BADAN USAHA",
                    key: 'legality',
                    width: 100,
                },
                {
                    title: "TIPE",
                    key: 'type_parties',
                    width: 150,
                },
                {
                    title: "PERUSAHAAN",
                    key: 'company',
                    width: 150,
                    render(row) {
                        return h(
                            NTag,
                            {
                                type: row.company === "DNP" ? 'success' : 'info',
                                strong: true,
                                bordered: true
                            },
                            {
                                default: () => row.company
                            }
                        )
                    }
                },
                {
                    title: "PEMBAYARAN CUSTOMER",
                    key: "payment_customer",
                    width: 150,
                },
                {
                    title: "PIC/PURCH",
                    key: 'pic',
                    width: 150,
                },
                {
                    title: "OWNER",
                    key: "owner",
                    width: 150,
                },
                // {
                //     title: "SEGMENT CUSTOMER",
                //     key: "segment_customer",
                //     width: 150,
                //     render(row) {
                //         return row.segment_customer;
                //     }
                // },
                {
                    title: "NO TELP",
                    key: 'phone',
                    width: 150,
                },
                // {
                //     title: "KOTA",
                //     key: "city",
                //     width: 150,
                // },
                {
                    title: "WAJIB PAJAK",
                    key: 'taxpayer',
                    width: 150,
                    render(row) {
                        return row.taxpayer.replace('_', ' ');
                    }
                },
                {
                    title: "ALAMAT",
                    key: "address",
                    width: 250,
                },
                {
                    title: "ALAMAT KIRIM",
                    key: "return_address",
                    width: 250,
                },
                {
                    title: "TERMIN PEMBAYARAN",
                    key: "term_payment",
                    width: 200,
                    render(row) {
                        return `${row.term_payment} HARI`
                    }
                },
                {
                    title: "NPWP",
                    key: "npwp",
                    width: 150,
                },
                {
                    title: "FOTO NPWP",
                    key: "npwp_image_url",
                    width: 250,
                    render(row) {
                        return h(NImage, {
                            src: row.npwp_image_url,
                            height: '70'
                        })
                    },
                },
                {
                    title: "FOTO KTP",
                    key: "ktp_image_url",
                    width: 250,
                    render(row) {
                        return h(NImage, {
                            src: row.ktp_image_url,
                            height: '70'
                        })
                    },
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
                                        router.get(route('admin.parties.customer.edit', row.id));
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
                                            title: `Hapus customer ${row.name}?`,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.parties.customer.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: (page.props.flash as Flash).success,
                                                            duration: 1500,
                                                            closable: false,
                                                        })
                                                    }
                                                });
                                            }
                                        });
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
            legality: '',
            name: '',
            address: "",
            city: '',
            npwp: '',
            phone: "",
            term_payment: null as unknown as number,
            type_parties: '',
            parties_group_id: null as unknown as number,
            npwp_image: null as unknown as string,
            ktp_image: null as unknown as string,
            segment_customer: null as unknown as string,
            company: '',
            taxpayer: ''
        });

        // Filter data
        const filterField = ref('name'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        // Fungsi untuk meng-handle pencarian
        const handleSearch = () => {
            router.get(route('admin.parties.customer'), {
                page: pagination.current_page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, {
                preserveState: true,
                replace: true
            });
        };

        function handleFileChange(field, event) {
            const file = event.target.files[0]; // Ambil file pertama yang dipilih
            if (file) {
                this.form[field] = file; // Simpan file ke field terkait di form
            } else {
                this.form[field] = null; // Hapus jika tidak ada file
            }
        }

        const file = useForm({
            attachment: null as unknown as any,
        });

        function handleChangeFile(event) {
            file.attachment = event.target.files[0];
        }

        function handleImportProducts() {
            // Show loading notification
            Swal.fire({
                title: 'Mengimpor data...',
                text: 'Silahkan tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // TODO : CHANGE ROUTE
            file.post(route('admin.customer.import'), {
                onSuccess: (page) => {
                    Swal.fire({
                        title: 'Berhasil memasukan data!',
                        text: `Data yang diterima ${page.props.flash.success}`,
                        icon: 'success'
                    });
                },
                onError: () => {
                    Swal.fire('Gagal memasukan data!', 'Silahkan lapor developer', 'error');
                }
            });
        }

        function handleDownloadTemplateExcel(){
            const url = route('admin.customer.download-template');

            window.location.href = url;
        }

        // Function to handle page change
        function handlePageChange(page: number) {
            // currentPage.value = page;
            router.get(route('admin.parties.customer'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, { preserveState: true, replace: true }); // Request data for the selected page
        }

        function handleSubmitCustomer() {
            form.post(route('admin.parties.customer.post'), {
                preserveScroll: false,
                forceFormData: true,
                onSuccess: () => {
                    form.reset();
                    form.npwp_image = null as unknown as string;
                    form.ktp_image = null as unknown as string;
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        closable: false,
                        duration: 1500,
                    });
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

        const customer_type = (page.props.customer_type as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        const segmentCustomer = [
            { label: "GROSIR", value: "GROSIR" },
            { label: "RETAIL", value: "RETAIL" },
            { label: "END USER", value: "END_USER" },
            { label: "ALL SEGMENT", value: "ALL_SEGMENT" }
        ];

        return {
            handleSubmitCustomer,
            handlePageChange,
            handleSearch,
            handleFileChange,
            segmentCustomer,
            columns: createColumns(),
            currentPage,
            form,
            groups,
            customer_type,
            pagination,
            filterField,
            filterQuery,
            file,
            handleChangeFile,
            handleImportProducts,
            handleDownloadTemplateExcel,
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>