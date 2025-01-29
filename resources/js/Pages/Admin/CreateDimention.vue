<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Dimensi Baru" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.dimentions.index'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2 mb-2">
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">NAMA DIMENSI</label>
                        <n-input placeholder="" size="large" v-model:value="form.dimention_name" @input="(value) => form.dimention_name = value.toUpperCase()"></n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">MINIMAL DIMENSI</label>
                        <n-input placeholder="" size="large" v-model:value="form.min_value" @input="(value) => form.min_value = value.replace(/\D/g,'')">
                            <template #prefix>MIN</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">MAKSIMUM DIMENSI</label>
                        <n-input placeholder="" size="large" v-model:value="form.max_value" @input="(value) => form.max_value = value.replace(/\D/g,'')">
                            <template #prefix>MAX</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-lg-4 d-flex flex-column gap-1">
                        <label for="">HARGA DIMENSI</label>
                        <n-input placeholder="" size="large" v-model:value="form.price_dimention" @input="(value) => form.price_dimention = value.replace(/\D/g,'')">
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
import { Head, useForm } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import Swal from 'sweetalert2';

export default defineComponent({
    setup () {
        const form = useForm({
            dimention_name: null as unknown as string,
            min_value: null as unknown as number,
            max_value: null as unknown as number,
            price_dimention: null as unknown as number
        });

        function handleSubmit() {
            form.post(route('admin.dimentions.store'), {
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
