<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="SETTING TARGET SALESMAN" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('marketing.index-target'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="d-flex align-items-center justify-content-center">
            <div class="card shadow-sm border-0 w-50 mb-3">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center w-100">
                        <span class="fs-5">Setting Target</span>
                        <!-- <div class="d-flex align-items-center ms-auto gap-2">
                            <span>Periode</span>
                            <n-date-picker v-model:formatted-value="targetForm.period" type="year" value-format="yyyy"
                                clearable style="flex-grow: 1; min-width: 150px;" placeholder="Tahun Periode"
                                @update:value="updateYear" />
                        </div> -->
                    </div>

                    <n-divider>Informasi Salesman</n-divider>

                    <div class="row g-2">
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Salesman</label>
                            <n-input placeholder="" size="large" v-model:value="model.fullname" disabled />
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Salesman UID</label>
                            <n-input placeholder="" size="large" v-model:value="model.saleman_uid" disabled />
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Divisi</label>
                            <n-input placeholder="" size="large" v-model:value="model.division" disabled />
                        </div>

                        <n-divider>Informasi Target</n-divider>

                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Target Bulanan</label>
                            <n-input placeholder="" size="large" v-model:value="targetForm.monthly_target"
                                @input="(value) => targetForm.monthly_target = value.replace(/\D/g, '')">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Target Tahunan</label>
                            <n-input placeholder="" size="large" v-model:value="targetForm.annual_target"
                                @input="(value) => targetForm.annual_target = value.replace(/\D/g, '')">
                                <template #prefix>Rp</template>
                            </n-input>
                        </div>
                    </div>

                    <n-divider></n-divider>

                    <div class="d-flex">
                        <div class="d-flex gap-2 ms-auto">
                            <n-button type="primary" @click="handleSubmit">Simpan Informasi</n-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        const page = usePage();
        const salesman = page.props.salesman as any;

        const model = ref({
            fullname: salesman.fullname,
            saleman_uid: salesman.user_uid,
            division: salesman.division.division_name,
        });

        const targetForm = useForm({
            annual_target: null as unknown as number,
            monthly_target: null as unknown as number,
            user_id: null as unknown as number,
            period: null as unknown as any,
        });

        function handleSubmit() {
            targetForm.patch(route('marketing.update-target', salesman.id), {
                onSuccess: (page) => {
                    Swal.fire(page.props.flash.success, '', 'success');
                },
                onError: () => {
                    Swal.fire('Gagal memperbarui', '', 'error');
                }
            })
        }

        function updateYear(value) {
            // Pastikan `targetForm.period` hanya mendapatkan tahun dalam format string (misal: "2024")
            const year = new Date(value).getFullYear();
            targetForm.period = year;
        }

        return {
            handleSubmit,
            updateYear,
            router,
            salesman,
            model,
            targetForm,
            ArrowBack,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>