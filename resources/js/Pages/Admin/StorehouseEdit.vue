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
                    <n-button class="ms-auto" type="primary" @click="handleSubmitUnit">Update data</n-button>
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

        const form = useForm({
            code: (page.props.storehouse as any).code,
            name: (page.props.storehouse as any).name,
        });

        function handleSubmitUnit() {
            form.patch(route('admin.storehouse.update', (page.props.storehouse as any).id), {
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
            handleSubmitUnit,
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