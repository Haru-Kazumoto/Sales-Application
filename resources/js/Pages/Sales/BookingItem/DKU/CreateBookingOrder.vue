<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Booking Order" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('sales.booking-item.index-booking-dnp'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="">Nomor Booking</label>
                        <n-input size="large" placeholder="" v-model:value="form.document_code" readonly />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="">Nama Produk</label>
                        <n-select filterable placeholder="" size="large" :options="productOptions"
                            v-model:value="form.product_name" />
                        <span :style="{ color: stockStatusColor }">
                            {{ stockMessage }}
                        </span>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="">Quantity</label>
                        <n-input size="large" placeholder="" v-model:value="form.quantity" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="">Kemasan</label>
                        <n-input size="large" placeholder="" v-model:value="form.unit" readonly />
                    </div>
                </div>
                <div class="d-flex w-100 my-2">
                    <n-button class="ms-auto" type="primary" @click="addProduct">Tambah produk</n-button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="form.products" />
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 d-flex">
                        <span class="fw-semibold">TOTAL</span>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="ms-auto fw-semibold">{{ totalAmount }}</span>
                    </div>
                </div>
                <div style="border: 1px solid black;" class="my-3" />
            </div>
        </div>

        <div class="d-flex">
            <n-button type="primary" class="ms-auto w-25" @click="handleSubmit">SUBMIT</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, watch, computed, ref } from 'vue';
import { router, useForm, usePage } from "@inertiajs/vue3";
import { ArrowBack } from '@vicons/ionicons5';
import TitlePage from '../../../../Components/TitlePage.vue';
import { NButton, useNotification } from 'naive-ui';
import { formatRupiah } from "../../../../utils/options-input.utils";
import Swal from 'sweetalert2';
import { Flash } from '../../../../types/model';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
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
                    title: "NOMOR BOOKING",
                    key: "booking_number",
                    width: 200,
                },
                {
                    title: "NAMA PRODUK",
                    key: "product_name",
                    width: 200,
                },
                {
                    title: "QUANTITY",
                    key: "quantity",
                    width: 150,
                },
                {
                    title: "SATUAN",
                    key: "unit",
                    width: 150,
                },
                {
                    title: "HARGA",
                    key: "price",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.price);
                    }
                },
                {
                    title: "TOTAL",
                    key: "total",
                    width: 200,
                    render(row) {
                        return formatRupiah(row.total);
                    }
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 150,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "error",
                                size: 'small',
                                onClick: () => removeProduct(row)
                            },
                            { default: () => "Hapus" }
                        );
                    }
                }
            ]
        }

        const page = usePage();
        const notification = useNotification();
        const products = page.props.products;
        const stockMessage = ref(''); // Untuk pesan stok
        const stockStatusColor = ref('black'); // Untuk warna teks

        const form = useForm({
            document_code: page.props.booking_number,
            product_name: '',
            quantity: null as unknown as number,
            unit: '',
            last_stock: null as unknown as number,  
            price: null as unknown as number,
            total: null as unknown as number,
            product_id: null as unknown as number,
            products: [] as any[],
            transaction_details: [] as any[],
        });

        const productOptions = (products as any[]).map(data => ({
            label: data.name,
            value: data.name,
            id: data.id,
            code: data.code,
            retail_price: data.retail_price,
            status: data.status,
            unit: data.unit,
            warehouse: data.warehouse,
            last_stock: data.last_stock
        }));

        const totalAmount = computed(() => {
            const total = form.products.reduce((total, item) => {
                return total + Number(item.total ?? 0); // Konversi amount ke number
            }, 0);

            form.total = total;
            return formatRupiah(total);
        });

        watch(() => form.product_name, (value) => {
            const selectedProduct = productOptions.find(data => data.value === value);
            form.unit = selectedProduct?.unit || '';
            form.price = selectedProduct?.retail_price || 0;
            form.product_id = selectedProduct?.id || 0;
            form.last_stock = selectedProduct?.last_stock || 0;

            // Tentukan pesan dan warna status berdasarkan last_stock
            if (selectedProduct?.last_stock <= 10) {
                stockMessage.value = `Stok saat ini : (${selectedProduct?.last_stock})`;
                stockStatusColor.value = 'red'; // Merah jika stok kurang dari 10
            } else {
                stockMessage.value = `Stok saat ini : (${selectedProduct?.last_stock})`;
                stockStatusColor.value = 'black'; // Default hitam
            }
        });

        function handleSubmit() {
            form.transaction_details = [
                {
                    name: "Nomor Booking",
                    category: "Booking Number",
                    value: form.document_code,
                    data_type: "string"
                },
                {
                    name: "Tanggal Booking",
                    category: "Booking Date",
                    value: new Date(),
                    data_type: "dateTime",
                },
                {
                    name: "Pengecekan",
                    category: "Check",
                    value: "PENDING",
                    data_type: "string",
                },
                {
                    name: "Salesman",
                    category: "Salesman",
                    value: (page.props.auth as any).user.fullname,
                    data_type: 'string',
                },
                {
                    name: "Deskripsi",
                    category: "Description",
                    value: "-",
                    data_type: 'text'
                },
                {
                    name: "Gudang",
                    category: "Warehouse",
                    value: "DKU",
                    data_type: 'string',
                }
            ]

            form.post(route('sales.booking-item.store-booking-dnp'), {
                onSuccess: (page) => {
                    Swal.fire((page.props.flash as Flash).success, '', 'success');
                }
            });
        }

        function addProduct() {
            if (!form.product_name || !form.quantity) {
                Swal.fire('Cek form kembali', '', 'error');
                return;
            }

            if (!form.price) {
                Swal.fire('Harga di produk ini belum ada', 'masukan harga di data master terlebih dahulu', 'warning');
                return;
            }

            // Pastikan quantity dan stok adalah angka
            const quantity = Number(form.quantity);
            const lastStock = Number(form.last_stock);

            // Periksa apakah quantity lebih besar dari stok
            if (quantity > lastStock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Quantity barang tidak cukup!',
                    text: `Stok aktual ${lastStock}`,
                });
                return; // Hentikan eksekusi jika quantity tidak valid
            }

            const selectedProduct = productOptions.find(data => data.value === form.product_name);

            const total = (form.quantity || 0) * (selectedProduct?.retail_price || 0);

            form.products.push({
                booking_number: form.document_code,
                product_name: form.product_name,
                quantity: form.quantity as number,
                unit: form.unit,
                price: form.price,
                total: total,
                product_id: form.product_id,
            });

            form.product_name = '';
            form.quantity = null as unknown as number;
            form.unit = '';
            form.price = null as unknown as number;

            notification.success({
                title: "Produk ditambahkan",
                duration: 1500,
                closable: false,
            });
        }

        function removeProduct(product) {
            form.products = form.products.filter(p => p.product_name !== product.product_name);

            notification.warning({
                title: "Produk di hapus",
                duration: 1500,
                closable: false,
            });
        }

        return {
            columns: createColumns(),
            router,
            form,
            productOptions,
            totalAmount,
            stockMessage,
            stockStatusColor,
            ArrowBack,
            addProduct,
            formatRupiah,
            removeProduct,
            handleSubmit
        }
    },
    components: {
        TitlePage
    }
})
</script>