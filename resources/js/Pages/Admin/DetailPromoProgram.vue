<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Program Promo" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.index-promo'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Nama Promo</label>
                        <n-input size="large" placeholder="" v-model:value="form.name" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="">Minimal Pembelian</label>
                        <n-input size="large" v-model:value="form.min" placeholder=""
                            @input="(value) => form.min = value.replace(/\D/g, '')" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="">Maksimal Pembelian</label>
                        <n-input size="large" v-model:value="form.max" placeholder=""
                            @input="(value) => form.max = value.replace(/\D/g, '')" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Persentase Promo</label>
                        <n-input size="large" placeholder="" v-model:value="form.promo_value" readonly>
                            <template #suffix>%</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Deskripsi promo</label>
                        <n-input size="large" placeholder="" type="textarea" v-model:value="form.description"
                            readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Tanggal Aktif</label>
                        <n-input size="large" v-model:value="form.start_date" placeholder=""
                            readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Tanggal Non Aktif</label>
                        <n-input size="large" v-model:value="form.end_date" placeholder=""
                            readonly />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="form.products" />
            </div>
        </div>

        <div class="d-flex">
            <n-button size="large" type="error">Non aktifkan promo</n-button>
            <div class="d-flex ms-auto gap-3">
                <n-button size="large" type="info">Update Promo</n-button>
                <n-button size="large" type="error">Hapus Promo</n-button>
            </div>

        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref, watch } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import RequiredMark from '../../Components/RequiredMark.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { NButton, SelectOption, useNotification } from 'naive-ui';
import { ArrowBack } from '@vicons/ionicons5';
import { Flash, Products } from '../../types/model';
import Swal from 'sweetalert2';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const page = usePage();
        const productsRef = ref<SelectOption[]>([]);
        const loadingRef = ref(false);
        const notification = useNotification();
        const promo = page.props.promoProgram as any;
        console.log(promo);

        function createColumns() {
            return [
                {
                    title: "#",
                    key: "index",
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "Kode Produk",
                    key: "product_code",
                    width: 150,
                    render(row) {
                        return row.code;
                    }
                },
                {
                    title: "Nama Produk",
                    key: "product_name",
                    width: 200,
                    render(row) {
                        return row.name;
                    }
                },
                {
                    title: "Kemasan",
                    key: "product_unit",
                    width: 150,
                    render(row) {
                        return row.unit;
                    }
                },
            ]
        }

        const form = useForm({
            name: promo.name,
            description: promo.description,
            min: promo.min,
            max: promo.max,
            promo_value: promo.promo_value,
            start_date: dayjs(promo.start_date).format('DD MMMM YYYY HH:mm:ss'),
            end_date: dayjs(promo.end_date).format('DD MMMM YYYY HH:mm:ss'),
            products: promo.products,
        });

        const products = ref({
            product_name: "",
            product_code: "",
            product_unit: "",
            product_id: null as unknown as number,
        });

        function handleSubmit() {
            form.post(route('admin.store-promo'), {
                onSuccess: (page) => {
                    form.reset();

                    Swal.fire((page.props.flash as Flash).success, '', 'success');
                },
                onError: () => {
                    Swal.fire('Gagal membuat promo', '', 'error');
                }
            });
        }

        return {
            columns: createColumns(),
            handleSubmit,
            productOptions: productsRef,
            loading: loadingRef,
            form,
            products,
            router,
            ArrowBack,
        }
    },
    components: {
        TitlePage,
        RequiredMark,
        Head
    }
})
</script>

<style scoped></style>