<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Customer" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitCustomer">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kode Customer</label>
                        <n-input placeholder="" size="large" v-model:value="form.code"
                            :on-input="(value) => form.code = value.replace(/\D/g, '')" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Nama Customer</label>
                        <n-input placeholder="" size="large" v-model:value="form.name"
                            :on-input="(value) => form.name = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Legalitas</label>
                        <n-input placeholder="" size="large" v-model:value="form.legality"
                            :on-input="(value) => form.legality = value.toUpperCase()" />
                    </div>

                    <!-- Second row -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Tipe</label>
                        <n-select placeholder="" size="large" v-model:value="form.type_parties"
                            :options="customer_type" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kelompok</label>
                        <n-select placeholder="" size="large" v-model:value="form.parties_group_id" :options="groups" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Termin Pembayaran</label>
                        <n-input placeholder="" size="large" v-model:value="form.term_payment"
                            :on-input="(value) => form.term_payment = value.replace(/\D/g, '')" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">NPWP</label>
                        <n-input placeholder="" size="large" v-model:value="form.npwp" />
                    </div>

                    <!-- Third row -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">No. Telpon</label>
                        <n-input placeholder="" size="large" v-model:value="form.phone" :on-input="(value) => form.phone = value.replace(/\D/g, '')" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kota</label>
                        <n-input placeholder="" size="large" v-model:value="form.city"
                            :on-input="(value) => form.city = value.toUpperCase()" />
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
                    <n-input class="w-50" placeholder="Mau cari siapa hayo" />
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" :data="($page.props.parties as any).data"
                    pagination-behavior-on-filter="first" />
                <div class="d-flex mt-3">
                    <n-pagination v-model:page="currentPage" :page-count="($page.props.parties as any).to"
                        class="ms-auto" :page-size="($page.props.parties as any).per_page"
                        @update:page="handlePageChange" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h, watch } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { Flash, Lookup, Parties, PartiesGroup } from '../../types/model';
import debounce from 'lodash/debounce';
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
                    title: "NAMA CUSTOMER",
                    key: 'name',
                    width: 200,
                },
                {
                    title: "LEGALITAS",
                    key: 'legality',
                    width: 100,
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
                },
                {
                    title: "NO TELP",
                    key: 'phone',
                    width: 150,
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
                    title: "TERMIN PEMBAYARAN",
                    key: "term_payment",
                    width: 200,
                },
                {
                    title: "NPWP",
                    key: "npwp",
                    width: 150,
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
                                            if(result.isConfirmed) {
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

        const searchQuery = ref('');
        const loadingSearch = ref(false);

        const form = useForm<Parties>({
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
        });

        // Function to handle page change
        function handlePageChange(page: number) {
            currentPage.value = page;
            router.get(route('admin.parties.customer'), { page }, { preserveState: true }); // Request data for the selected page
        }

        function handleSubmitCustomer() {
            form.post(route('admin.parties.customer.post'), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        closable: false,
                        duration: 1500,
                    });
                }
            });
        }

        const groups = (page.props.groups as PartiesGroup[]).map((data) => ({
            id: data.id,
            label: data.name,
            value: data.id,
        }));

        const customer_type = (page.props.customer_type as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        return {
            handleSubmitCustomer,
            handlePageChange,
            columns: createColumns(),
            currentPage,
            form,
            groups,
            customer_type,
            searchQuery,
            loadingSearch,
            // partiesData,
            // pageCount,
            // pageSize,
            // totalData
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>