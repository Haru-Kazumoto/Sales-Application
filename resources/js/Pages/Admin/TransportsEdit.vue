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
                    <n-button class="ms-auto" type="primary" @click="handleUpdateTransport" size="large">Update
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
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ArrowBack } from "@vicons/ionicons5";

export default defineComponent({
    setup() {
        const page = usePage();
        const form = useForm({
            code: (page.props.parties as any).code,
            name: (page.props.parties as any).name,
            address: (page.props.parties as any).address,
            phone: (page.props.parties as any).phone,
            phone_2: (page.props.parties as any).phone_2,
            pic: (page.props.parties as any).pic,
            pic_2: (page.props.parties as any).pic_2
        });

        function handleUpdateTransport() {
            form.patch(route('admin.transport.update', (page.props.parties as any).id), {
                preserveScroll: true,
                onSuccess: (page) => {
                    Swal.fire({
                        title: (page.props.flash as any).success,
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
            handleUpdateTransport,
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