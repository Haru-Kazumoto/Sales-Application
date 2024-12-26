<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Produk" />
        <n-tabs type="line" animated size="large">
            <n-tab-pane name="products" tab="Daftar Produk">
                <div class="row g-3">
                    <div class="col-12 col-lg-6 d-flex align-items-lg-center gap-3 mb-3">
                        <label>Total Jumlah Produk</label>
                        <span class="border px-4 py-1 bg-white" style="border-radius: 3px;">
                            {{ ($page.props.products as any).total }}
                        </span>
                    </div>
                    <div class="col-12 col-lg-6 d-flex">
                        <div class="ms-auto d-flex align-items-lg-center gap-2 justify-content-lg-end w-100">
                            <label>Pencarian Data</label>
                            <n-select class="w-25" v-model:value="filterField" placeholder="Pilih field" :options="[
                                { label: 'Kode', value: 'code' },
                                { label: 'Nama', value: 'name' },
                                { label: 'Kategori', value: 'category' },
                                { label: 'Supplier', value: 'supplier' },
                            ]" />
                            <n-input class="w-50" placeholder="" @input="handleSearchQuery"
                                v-model:value="filterQuery" />
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-body">
                        <n-data-table :columns="columns" :bordered="false" :data="($page.props.products as any).data"
                            pagination-behavior-on-filter="first" />
                        <div class="d-flex mt-3">
                            <n-pagination class="ms-auto" v-model:page="pagination.current_page"
                                :page-count="pagination.last_page" :page-size="pagination.per_page"
                                @update:page="handlePageChange"
                                @update:page-count="pagination.last_page = ($page.props.units as any).last_page" />
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Barang</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Nama Barang</strong></td>
                                            <td>:</td>
                                            <td>{{ selectedData.name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kode Barang</strong></td>
                                            <td>:</td>
                                            <td>{{ selectedData.code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jenis Produk</strong></td>
                                            <td>:</td>
                                            <td>{{ selectedData.type }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Segment Produk</strong></td>
                                            <td>:</td>
                                            <td>{{ selectedData.product_type?.name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kategori</strong></td>
                                            <td>:</td>
                                            <td>{{ selectedData.category }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pemasok</strong></td>
                                            <td>:</td>
                                            <td>{{ selectedData.parties?.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <n-divider>HARGA</n-divider>
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Harga Tebus</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.redemp_price) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga Jual Grosir</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.retail_price) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga Retail</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.restaurant_price) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga All Segment</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.all_segment_price) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga End User</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.price_3) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga DD</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.dd_price) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Margin All Segment</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.normal_margin) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Margin End User</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.margin_end_user) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Margin Retail</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.margin_retail) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>OH Depo</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.oh_depo) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saving</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.saving) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bad Debt DD</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.bad_debt_dd) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saving Marketing</strong></td>
                                            <td>:</td>
                                            <td>{{ formatRupiah(selectedData.saving_marketing) }}</td>
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

            </n-tab-pane>
            <n-tab-pane name="create-product" tab="Buat Produk Baru">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-4 d-flex flex-column">
                                <h4>Tambah data dari excel</h4>
                                <n-divider></n-divider>
                                <label for="file-upload" class="form-label">Upload File Excel</label>
                                <input type="file" id="file-upload" accept=".xlsx" class="form-control"
                                    @change="handleChangeFile" />
                                <small class="text-muted">
                                    Hanya file Excel (.xlsx) yang diperbolehkan.
                                </small>
                                <n-button type="primary" class="mt-3 w-25"
                                    @click="handleImportProducts">Import</n-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h4>Tambah data baru</h4>
                        <n-divider></n-divider>
                        <form class="row g-3" @submit.prevent="handleSubmitProduct">
                            <!-- FIRST ROW -->
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Kode Barang
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.code" />
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Nama Produk
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.name" />
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Kelompok
                                    <RequiredMark />
                                </label>
                                <n-select size="large" placeholder="" :options="productTypeOptions"
                                    v-model:value="form.product_type_id" />
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Pemasok
                                    <RequiredMark />
                                </label>
                                <n-select placeholder="" v-model:value="form.supplier_id" size="large"
                                    :options="supplierOptions" filterable />
                            </div>

                            <!-- SECOND ROW -->
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Kemasan
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.unit"
                                    @input="(value) => form.unit = value.toUpperCase()" />
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga Tebus
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.redemp_price">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga Trucking
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.transportation_cost"
                                    @input="(value) => form.transportation_cost = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Kategori Produk
                                    <RequiredMark />
                                </label>
                                <n-select size="large" placeholder="" v-model:value="form.category"
                                    :options="categoryProductOptions" />
                            </div>

                            <!-- THIRD ROW -->
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">OH Depo
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.oh_depo"
                                    @input="(value) => form.oh_depo = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Saving
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.saving"
                                    @input="(value) => form.saving = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Bad Debt
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.bad_debt_dd"
                                    @input="(value) => form.bad_debt_dd = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Saving Marketing
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.saving_marketing"
                                    @input="(value) => form.saving_marketing = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>

                            <!-- FOURTH ROW -->
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga Jual Grosir
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.retail_price"
                                    @input="(value) => form.retail_price = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga Retail
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.restaurant_price"
                                    @input="(value) => form.restaurant_price = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga End User
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.price_3"
                                    @input="(value) => form.price_3 = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga All Segment
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.all_segment_price"
                                    @input="(value) => form.all_segment_price = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Harga Jual DD
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.dd_price"
                                    @input="(value) => form.dd_price = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>


                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Margin All Segment
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.normal_margin"
                                    @input="(value) => form.normal_margin = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Margin End User
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.margin_end_user"
                                    @input="(value) => form.margin_end_user = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Margin Retail
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.margin_retail"
                                    @input="(value) => form.margin_retail = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-4 d-flex flex-column">
                                <label for="">Margin Grosir
                                    <RequiredMark />
                                </label>
                                <n-input size="large" placeholder="" v-model:value="form.margin_grosir"
                                    @input="(value) => form.margin_grosir = value.replace(/\D/g, '')">
                                    <template #prefix>
                                        Rp
                                    </template>
                                </n-input>
                            </div>

                            <div class="d-flex">
                                <n-button type="primary" class="ms-auto" attr-type="submit">Tambah Data</n-button>
                            </div>
                        </form>
                    </div>
                </div>
            </n-tab-pane>
        </n-tabs>


    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, reactive } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import RequiredMark from '../../Components/RequiredMark.vue';
import { DataTableColumns, NButton, NIcon, SelectOption, useNotification } from 'naive-ui';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Flash, Lookup, Products } from '../../types/model';
import { formatRupiah } from '../../Utils/options-input.utils';
import Swal from 'sweetalert2';
import axios from 'axios';
import { ClipboardBulletListLtr20Regular } from '@vicons/fluent';

export default defineComponent({
    setup() {
        const currentPage = ref(1);
        const page = usePage();
        const notification = useNotification();
        const supplierOptionsRef = ref<SelectOption[]>([]);
        const loading = ref(false);
        const selectedData = ref({});

        const form = useForm({
            code: '',
            unit: '',
            name: '',
            category: '',
            package: '',
            redemp_price: null as unknown as number,
            retail_price: null as unknown as number,
            restaurant_price: null as unknown as number,
            price_3: null as unknown as number,
            dd_price: null as unknown as number,
            all_segment_price: null as unknown as number,
            normal_margin: null as unknown as number,
            oh_depo: null as unknown as number,
            saving: null as unknown as number,
            bad_debt_dd: null as unknown as number,
            saving_marketing: null as unknown as number,
            product_type_id: null as unknown as number,
            supplier_id: null as unknown as number,
            transportation_cost: null as unknown as number,
            margin_retail: null as unknown as number,
            margin_end_user: null as unknown as number,
            margin_grosir: null as unknown as number,
        });

        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 70,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "DETAIL",
                    key: 'detail',
                    width: 80,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "info",
                                size: "medium",
                                onClick() {
                                    showDetail(row);
                                },
                                renderIcon() {
                                    return h(NIcon, {
                                        component: ClipboardBulletListLtr20Regular,
                                        size: 20
                                    });
                                },
                            }
                        );
                    }
                },
                {
                    title: "KODE BARANG",
                    key: "code",
                    width: 150
                },
                {
                    title: "NAMA BARANG",
                    key: 'name',
                    width: 200,
                },
                {
                    title: "KEMASAN",
                    key: 'unit',
                    width: 100,
                },
                {
                    title: "JENIS PRODUK",
                    key: "type",
                    width: 150,
                },
                {
                    title: "SEGMENT PRODUK",
                    key: "segment",
                    width: 200,
                    render(row) {
                        return row.product_type?.name
                    }
                },
                {
                    title: "KATEGORI",
                    key: "category",
                    width: 150,
                },
                {
                    title: "PEMASOK",
                    key: "supplier",
                    width: 150,
                    render(row) {
                        return row.parties?.name;
                    }
                },
                {
                    title: "HARGA TEBUS",
                    key: 'redemption_price',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.redemp_price);
                    }
                },
                {
                    title: "HARGA JUAL GROSIR",
                    key: "retail_selling_price",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.retail_price);
                    }
                },
                {
                    title: "HARGA RETAIL",
                    key: "selling_price",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.restaurant_price);
                    }
                },
                {
                    title: "HARGA ALL SEGMENT",
                    key: "all_segment_price",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.all_segment_price);
                    }
                },
                {
                    title: "HARGA END USER",
                    key: "price_3",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.price_3);
                    }
                },
                {
                    title: "HARGA DD",
                    key: "price_dd",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.dd_price);
                    }
                },
                {
                    title: "MARGIN ALL SEGMENT",
                    key: "margin_normal",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.normal_margin);
                    }
                },
                {
                    title: "MARGIN END USER",
                    key: "margin_end_user",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.margin_end_user);
                    }
                },
                {
                    title: "MARGIN RETAIL",
                    key: "margin_retail",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.margin_retail);
                    }
                },
                {
                    title: "OH DEPO",
                    key: "over_head_depo",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.oh_depo);
                    }
                },
                {
                    title: "SAVING",
                    key: "saving",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.saving);
                    }
                },
                {
                    title: "BAD DEBT DD",
                    key: "bad_debt_dd",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.bad_debt_dd);
                    }
                },
                {
                    title: "SAVING MARKETING",
                    key: "saving_marketing",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.saving_marketing);
                    }
                },
                {
                    title: 'ACTION',
                    key: "action",
                    width: 100,
                    render(row) {
                        return h('div', { class: "d-flex gap-2" }, [
                            h(
                                NButton, {
                                type: 'info',
                                onClick() {
                                    router.get(route('admin.products.edit', row.id));
                                },
                            },
                                { default: () => 'UPDATE' }
                            ),
                            h(
                                NButton, {
                                type: 'error',
                                onClick() {
                                    Swal.fire({
                                        icon: 'question',
                                        title: `Hapus barang ${row.name} ? `,
                                        showCancelButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            router.delete(route('admin.products.delete', row.id), {
                                                onSuccess: () => {
                                                    notification.success({
                                                        title: "Barang berhasil dihapus",
                                                        duration: 1500,
                                                        closable: false,
                                                    })
                                                }
                                            });
                                        }
                                    })
                                }
                            }, { default: () => "HAPUS" }
                            )
                        ]);
                    }
                }
            ]
        }

        // Filter data
        const filterField = ref('name'); // Default filter field
        const filterQuery = ref(''); // Filter input value

        function showDetail(row) {
            selectedData.value = row;

            const modal = new bootstrap.Modal(document.getElementById('exampleModal'));

            modal.show();

            console.log(selectedData.value);
        }

        // Fungsi untuk meng-handle pencarian
        const handleSearchQuery = () => {
            router.get(route('admin.products'), {
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
            router.get(route('admin.products'), {
                page,
                filter_field: filterField.value,
                filter_query: filterQuery.value
            }, { preserveState: true }); // Request data for the selected page
        }

        function handleSubmitProduct() {
            form.post(route('admin.products.post'), {
                onSuccess() {
                    form.reset();
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        duration: 1500,
                        closable: false,
                    });
                },
                onError() {
                    Swal.fire('Cek kembali form', 'Form dengan <span class="text-danger">*</span> harus di isi!', 'error');
                }
            });
        }

        const file = useForm({
            attachment: null as unknown as any,
        });

        function handleChangeFile(event) {
            file.attachment = event.target.files[0];
        }

        function handleImportProducts() {
            if (file.attachment === null) {
                Swal.fire('File belum di pilih!', 'Silahkan pilih file excel terlebih dahulu', 'error');
                return;
            }

            // Show loading notification
            Swal.fire({
                title: 'Importing data...',
                text: 'Please wait while we import the data.',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            file.post(route('admin.products.import'), {
                onSuccess: () => {
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

        const pagination = reactive({
            current_page: (page.props.products as any).current_page,
            per_page: (page.props.products as any).per_page,
            total: (page.props.products as any).total,
            last_page: (page.props.products as any).last_page,
        });

        const unitOptions = (page.props.units as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value
        }));

        const productTypeOptions = (page.props.product_type as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        const categoryProductOptions = [
            { label: "NON TEPUNG", value: "NON TEPUNG" },
            { label: "TEPUNG", value: "TEPUNG" },
        ];

        const supplierOptions = (page.props.suppliers as any[]).map((data) => ({
            label: data.name,
            value: data.id,
        }));

        return {
            columns: createColumns(),
            showDetail,
            handleImportProducts,
            handleChangeFile,
            handlePageChange,
            handleSubmitProduct,
            handleSearchQuery,
            currentPage,
            unitOptions,
            productTypeOptions,
            selectedData,
            categoryProductOptions,
            form,
            formatRupiah,
            filterField,
            filterQuery,
            pagination,
            loading,
            supplierOptions,
        }
    },
    components: {
        TitlePage,
        RequiredMark
    }
})
</script>