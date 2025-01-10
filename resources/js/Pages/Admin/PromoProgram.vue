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
                        <label for="">Nama Promo
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Minimal Pembelian
                            <RequiredMark />
                        </label>
                        <n-input size="large" v-model:value="form.min" placeholder="" @input="(value) => form.min = value.replace(/\D/g,'')"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Maksimal Pembelian
                            <RequiredMark />
                        </label>
                        <n-input size="large" v-model:value="form.max" placeholder="" @input="(value) => form.max = value.replace(/\D/g,'')"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Persentase Promo 1
                            
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.promo_value_1" @input="(value) => form.promo_value_1 = value.replace(/\D/g, '')">
                            <template #suffix>%</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Persentase Promo 2 
                            
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.promo_value_2" @input="(value) => form.promo_value_2 = value.replace(/\D/g, '')">
                            <template #suffix>%</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Persentase Promo 3
                            
                        </label>
                        <n-input size="large" placeholder="" v-model:value="form.promo_value_3" @input="(value) => form.promo_value_3 = value.replace(/\D/g, '')">
                            <template #suffix>%</template>
                        </n-input>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Tanggal Aktif
                            <RequiredMark />
                        </label>
                        <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="form.start_date" placeholder="" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Tanggal Non Aktif
                            <RequiredMark />
                        </label>
                        <n-date-picker value-format="yyyy-MM-dd HH:mm:ss" type="datetime" id="field8" size="large"
                                v-model:formatted-value="form.end_date" placeholder="" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Deskripsi promo
                            <RequiredMark />
                        </label>
                        <n-input size="large" placeholder="" type="textarea" v-model:value="form.description"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="">Bukti promo
                            <RequiredMark />
                        </label>
                        <input type="file" id="npwpImage" class="form-control" accept="image/*, application/pdf" @change="handleFileChange('file_attachment',$event)"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 ">
                        <label for="">Produk Pilihan</label>
                        <n-select filterable :options="productOptions" placeholder="" size="large"
                            v-model:value="products.product_name" />
                    </div>
                    <div class="col-4 d-flex align-items-end">
                        <n-button size="large" type="primary" @click="addProduct">Tambah produk</n-button>
                    </div>
                </div>
                <n-data-table :bordered="false" :columns="columns" :data="form.products" />
            </div>
        </div>

        <n-button size="large" type="primary" class="w-25 mb-3" @click="handleSubmit">Buat promo</n-button>
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

export default defineComponent({
    setup() {
        const page = usePage();
        const productsRef = ref<SelectOption[]>([]);
        const loadingRef = ref(false);
        const notification = useNotification();

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
                },
                {
                    title: "Nama Produk",
                    key: "product_name",
                    width: 200,
                },
                {
                    title: "Kemasan",
                    key: "product_unit",
                    width: 150,
                },
                {
                    title: "Aksi",
                    key: "action",
                    width: 150,
                    render(row, index) {
                        return h(
                            NButton,
                            {
                                size: "small",
                                type: "error",
                                onClick: () => removeProduct(index),
                            },
                            { default: "Hapus" }
                        )
                    }
                }
            ]
        }

        const form = useForm({
            name: "",
            description: "",
            min: null as unknown as number,
            max: null as unknown as number,
            promo_value_1: null as unknown as number,
            promo_value_2: null as unknown as number,
            promo_value_3: null as unknown as number,
            file_attachment: null as unknown as string,
            start_date: null as unknown as string,
            end_date: null as unknown as string,
            products: [] as any[],
        });

        const products = ref({
            product_name: "",
            product_code: "",
            product_unit: "",
            product_id: null as unknown as number,
        });

        function addProduct() {
            if (!products.value.product_name) {
                notification.error({
                    title: "Field harus diisi!",
                    closable: false,
                    duration: 1500,
                });

                return;
            }

            // Temukan produk berdasarkan nama
            const selectedProduct = productOptions.find(item => item.value === products.value.product_name);

            if (selectedProduct) {
                form.products.push({
                    product_name: selectedProduct.label,
                    product_code: selectedProduct.code,
                    product_unit: selectedProduct.unit,
                    product_id: selectedProduct.id,
                });

                // Clear input
                products.value.product_name = "";
            }

            notification.success({
                title: "Berhasil menambahkan barang",
                duration: 1500,
                closable: false,
            });
        }

        function removeProduct(index: number) {
            form.products.splice(index, 1);

            notification.success({
                title: "Produk berhasil dihapus",
                duration: 1500,
                closable: false,
            });
        }

        function handleSubmit() {
            form.post(route('admin.store-promo'), {
                onSuccess: (page) => {
                    form.reset();
                    form.file_attachment = null as unknown as string;

                    Swal.fire((page.props.flash as Flash).success,'','success');
                }, 
                onError: () => {
                    Swal.fire('Gagal membuat promo','','error');
                }
            });
        }

        function handleFileChange(field,event) {
            const file = event.target.files[0]; // Ambil file pertama yang dipilih
            if (file) {
                this.form[field] = file; // Simpan file ke field terkait di form
            } else {
                this.form[field] = null as any; // Hapus jika tidak ada file1
            }
        }

        const productOptions = (page.props.products as Products[]).map((data) => ({
            id: data.id,
            label: data.name,
            value: data.name,
            code: data.code,
            unit: data.unit,
        }));

        return {
            columns: createColumns(),
            addProduct,
            removeProduct,
            handleSubmit,
            productOptions,
            loading: loadingRef,
            form,
            products,
            router,
            ArrowBack,
            handleFileChange,
            handleSearchProducts: (query: string) => {
                if (!query.length) {
                    productsRef.value = []
                    return
                }
                loadingRef.value = true
                window.setTimeout(() => {
                    productsRef.value = productOptions.filter(
                        item => ~item.label.indexOf(query)
                    )
                    loadingRef.value = false
                }, 1000)
            },
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