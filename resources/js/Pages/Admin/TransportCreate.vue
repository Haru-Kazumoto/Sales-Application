<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-2">
            <TitlePage title="Edit Ekspedisi" />
            <Link :href="route('admin.index-transports')">
            <n-button text style="width: 20px;">
                <template #icon>
                    <n-icon :component="ArrowBack" />
                </template>
                Kembali
            </n-button>
            </Link>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <h4>Tambah data dari excel</h4>
                        <n-divider></n-divider>
                        <label for="file-upload" class="form-label">Upload File Excel</label>
                        <input type="file" id="file-upload" accept=".xlsx" class="form-control"   @change="handleChangeFile"/>
                        <small class="text-muted">
                            Hanya file Excel (.xlsx) yang diperbolehkan.
                        </small>
                        <n-button type="primary" class="mt-3 w-25" @click="handleImportProducts">Import</n-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shdaow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="">KODE EKSPEDISI
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="">NAMA EKSPEDISI
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">PIC 1
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.pic" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">NO PIC 1
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.phone" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">PIC 2</label>
                        <n-input size="large" placeholder="" v-model:value="form.pic_2" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">NO PIC 2</label>
                        <n-input size="large" placeholder="" v-model:value="form.phone_2" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-8 d-flex flex-column">
                        <label for="">ALAMAT EKSPEDISI
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" type="textarea" v-model:value="form.address" />
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button class="ms-auto" type="primary" @click="handleSubmitTransport" size="large">Tambah
                        data</n-button>
                </div>
            </div>
        </div>
    </div>

</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import RequiredMark from '../../Components/RequiredMark.vue';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { Flash, Parties } from '../../types/model';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ArrowBack } from "@vicons/ionicons5";

export default defineComponent({
    setup() {
        const page = usePage();
        const form = useForm({
            code: page.props.transport_code,
            name: '',
            address: '',
            phone: '',
            phone_2: '',
            pic: '',
            pic_2: ''
        });

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
                text: 'Tunggu sistem mengimport data.',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            file.post(route('admin.transport.import'), {
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

        function handleSubmitTransport() {
            form.post(route('admin.transport.post'), {
                preserveScroll: true,
                onSuccess: (page) => {
                    Swal.fire({
                        title: page.props.flash.success,
                        icon: "success",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                },
                onError: (errors) => {
                    Swal.fire({
                        title: "Gagal menambahkan data",
                        icon: "error",
                        text: "Silahkan cek kembali data yang anda masukkan",
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            });
        }

        return {
            form,
            handleSubmitTransport,
            ArrowBack,
            handleChangeFile,
            handleImportProducts
        }
    },
    components: {
        TitlePage,
        RequiredMark,
        Link,
    }
})
</script>