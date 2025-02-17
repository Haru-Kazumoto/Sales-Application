<template>

    <Head title="Harga Pengiriman DO"></Head>
    <div class="d-flex flex-column gap-3 pb-3">
        <TitlePage title="Pengiriman DEPO" subTitle="Daftar sub delivery dari delivery DEPO" />

        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center">
                <div class="d-flex ms-auto">
                    <div class="row">
                        <div class="col-12 d-flex gap-2">
                            <n-tooltip trigger="hover">
                                <template #trigger>
                                    <n-button type="primary" color="#006B3F" strong @click="active = true">
                                        <template #icon>
                                            <n-icon :component="Add16Filled" />
                                        </template>
                                        Tambah Sub Delivery
                                    </n-button>
                                </template>
                                Tambah sub delivery untuk DEPO
                            </n-tooltip>
                        </div>
                    </div>
                </div>
            </div>
            <span class="fw-semibold fs-5">
                SUB DELIVERY <span class="fw-medium fs-5" style="color: #b3b3b3">({{ countSubShipping
                    }})</span>
            </span>
            <n-data-table :columns :data="subShippings"></n-data-table>

        </div>

        <n-drawer v-model:show="active" :width="502" placement="right">
            <n-drawer-content title="Buat Sub Delivery (DEPO)">
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-column gap-1">
                        <label for="">Nama Sub Delivery </label>
                        <n-input placeholder="" size="large" v-model:value="formSubShipping.name"
                            @input="(value) => formSubShipping.name = value.toUpperCase()" />
                    </div>
                    <n-button type="primary" size="medium" class="ms-auto" @click="handleAddSubDelivery">Tambah
                        Data</n-button>
                </div>
            </n-drawer-content>
        </n-drawer>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue';
import TitlePage from '../../../../Components/TitlePage.vue';
import CurrencyInput from '../../../../Components/CurrencyInput.vue';
import FloatCard from '../../../../Components/FloatCard.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Search24Filled, Add16Filled, BoxSearch20Regular, VehicleTruckProfile24Filled } from '@vicons/fluent';
import { NButton, NIcon } from 'naive-ui';
import DetailProduct from '../../../../Components/DetailProduct.vue';
import Swal from 'sweetalert2';

export default defineComponent({
    props: {
        product_prices: {
            type: Object,
            required: true
        },
        countSubShipping: {
            type: Number,
            required: true
        },
        subShippings: {
            type: Array,
            required: true
        }
    },
    setup(props) {
        const active = ref(false);

        const formSubShipping = useForm({
            name: '',
            shipping_name: 'DEPO'
        });

        function createColumns() {
            return [
                {
                    title: "NAMA SUB DELIVERY",
                    key: "name",
                    width: 200,
                    render(row) {
                        return row.name.replace("_", " ");
                    }
                },
                {
                    title: "JUMLAH PRODUK",
                    key: "product_price_count",
                    width: 200,
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 100,
                    render(row) {
                        return h(
                            NButton,
                            {
                                strong: true,
                                size: "medium",
                                type: "info",
                                onClick() {
                                    // router.visit(route('admin.pricing.do.edit', row.id));
                                }
                            },
                            {
                                default: () => "Proses"
                            }
                        );
                    }
                }
            ]
        }

        function handleAddSubDelivery() {
            formSubShipping.post(route('admin.pricing.sub-shipping.store'), {
                onSuccess: (page) => {
                    active.value = false;
                    Swal.fire(page.props.flash.success, '', 'success');
                },
                onError: () => {
                    Swal.fire("Terjadi kesalahan!", '', 'error');
                }
            });
        }

        return {
            columns: createColumns(),
            handleAddSubDelivery,
            active,
            router,
            formSubShipping,
            Search24Filled,
            Add16Filled,
            VehicleTruckProfile24Filled
        }
    },
    components: {
        TitlePage,
        CurrencyInput,
        Head,
        DetailProduct,
        FloatCard
    }
})
</script>

<style scoped>
.float-card {
    border-color: rgb(101, 182, 94);
    transition: transform 0.3s ease-in-out, border 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.float-card:hover {
    transform: scale(1.00);
    border: 1px solid #28a745;
    box-shadow: 0 1px 10px rgba(40, 167, 69, 0.5);
}
</style>