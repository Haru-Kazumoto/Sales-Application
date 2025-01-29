<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Edit Customer" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.region-delivery.index'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2 mb-2">
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">NAMA WILAYAH</label>
                        <n-input placeholder="" size="large" v-model:value="form.region_name" @input="(value) => form.region_name = value.toUpperCase()"></n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">KODE WILAYAH</label>
                        <n-input placeholder="" size="large" v-model:value="form.region_code" @input="(value) => form.region_code = value.toUpperCase()"></n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">HARGA PER-WILAYAH</label>
                        <n-input placeholder="" size="large" v-model:value="form.region_price" @input="(value) => form.region_price = value.replace(/\D/g,'')">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                </div>
                <div class="d-flex">
                    <n-button class="ms-auto" type="primary" size="large" @click="handleSubmit">BUAT WILAYAH</n-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import Swal from 'sweetalert2';

export default defineComponent({
    setup () {
        const form = useForm({
            region_name: '',
            region_code: '',
            region_price: ''
        });

        function handleSubmit() {
            form.post(route('admin.region-delivery.store'), {
                onSuccess: (page) => {
                    Swal.fire(page.props.flash.success,'','success');
                },
                onError: () => {
                    Swal.fire('Cek form kembali','','error');
                }
            });
        }

        return {
            router,
            ArrowBack,
            handleSubmit,
            form
        }
    },
    components: {
        Head,
        TitlePage
    }
})
</script>

<style scoped>

</style>