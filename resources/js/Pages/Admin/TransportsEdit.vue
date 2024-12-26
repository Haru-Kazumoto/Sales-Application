<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-2">
            <TitlePage title="Edit Transport" />
            <Link :href="route('admin.create-transports')">
            <n-button text style="width: 20px;">
                <template #icon>
                    <n-icon :component="ArrowBack" />
                </template>
                Kembali
            </n-button>
            </Link>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Kode Transportasi</label>
                        <n-input size="large" placeholder="" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Nama Transportasi</label>
                        <n-input size="large" placeholder="" v-model:value="form.name"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Nomor Polisi</label>
                        <n-input size="large" placeholder="" v-model:value="form.number_plate"/>
                    </div>

                    <!-- Second row -->
                    <!-- <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Tipe</label>
                        <n-input size="large" placeholder="" v-model:value="form.type" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Kelompok</label>
                        <n-input size="large" placeholder="" v-model:value="form.group_name" disabled />
                    </div> -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Nomor Telepon</label>
                        <n-input size="large" placeholder="" v-model:value="form.phone"/>
                    </div>

                    <!-- Third row -->
                    <!-- <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="">Kota</label>
                        <n-input size="large" placeholder="" v-model:value="form.city"/>
                    </div> -->
                    <div class="col-12 col-md-6 col-lg-8 d-flex flex-column">
                        <label for="">Alamat</label>
                        <n-input size="large" placeholder="" type="textarea" v-model:value="form.address" />
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button class="ms-auto" type="info" @click="handleSubmitTransport">Edit data</n-button>
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
import {ArrowBack} from "@vicons/ionicons5";

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();
        const transport = page.props.parties as Parties;

        const form = useForm({
            code: transport.code,
            name: transport.name,
            number_plate: transport.number_plate,
            type: transport.type_parties,
            group_name: transport.parties_group.name,
            phone: transport.phone,
            city: transport.city,
            address: transport.address,
        });

        function handleSubmitTransport() {
            form.patch(route('admin.transport.update', transport.id), {
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
            ArrowBack
        }
    },
    components: {
        TitlePage,
        RequiredMark,
        Link,
    }
})
</script>

<style scoped></style>