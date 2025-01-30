<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Update Unsur Harga Baru" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.global-element-prices.index'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2 mb-2">
                    <div class="col-12 col-lg-6 d-flex flex-column gap-1">
                        <label for="">NAMA ELEMEN</label>
                        <n-input placeholder="" size="large" v-model:value="form.name_element" @input="(value) => form.name_element = value.toUpperCase()"></n-input>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-column gap-1">
                        <label for="">HARGA ELEMEN</label>
                        <n-input placeholder="" size="large" v-model:value="form.price_element" @input="(value) => form.price_element = value.replace(/\D/g,'')">
                            <template #prefix>Rp</template>
                        </n-input>
                    </div>
                </div>
                <div class="d-flex">
                    <n-button class="ms-auto" type="primary" size="large" @click="handleSubmit">BUAT ELEMEN BARU</n-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import Swal from 'sweetalert2';

export default defineComponent({
    setup () {

        const page = usePage();
        const element: any = page.props.element;

        const form = useForm({
            name_element: element.name_element,
            price_element: element.price_element
        });

        function handleSubmit() {
            Swal.fire({
                title: 'Mengubah data unsur harga..',
                text: 'Menyingkronkan data produk dengan unsur harga baru..',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        
            form.put(route('admin.global-element-prices.update', element.id), {
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