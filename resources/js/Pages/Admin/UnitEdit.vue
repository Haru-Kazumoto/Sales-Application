<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Buat Transport" />
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
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
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
        const unit = page.props.unit as any;

        const form = useForm({
            label: unit.label,
            value: unit.value,
        });

        function handleSubmitTransport() {
            form.patch(route('admin.unit.update', unit.id), {
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