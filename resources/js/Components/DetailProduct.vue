<template>
    <n-drawer v-model:show="activeModel" :width="502">
        <n-drawer-content closable>
            <template #header>
                Detail Harga
            </template>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td><strong>Nama Produk</strong></td>
                        <td>:</td>
                        <td>{{ dataProduct.name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kemasan Produk</strong></td>
                        <td>:</td>
                        <td>{{ dataProduct.unit }}</td>
                    </tr>
                </tbody>
            </table>
            <n-divider>Harga DO</n-divider>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td><strong>Harga Tebus</strong></td>
                        <td>:</td>
                        <td>Rp.200000</td>
                    </tr>
                    <tr>
                        <td><strong>Harga Jual Grosir</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Harga Jual Retail</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Harga End User</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Harga All Segment</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>OH Depo</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Saving</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Bad Debt</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Budget Marketing</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Margin All Segment</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Margin Grosir</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Margin Retail</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Margin End user</strong></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><strong>Pembulatan All Segment</strong></td>
                        <td>:</td>
                    </tr>
                </tbody>

            </table>
        </n-drawer-content>
    </n-drawer>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';
import { formatRupiah } from '../Utils/options-input.utils';

export default defineComponent({
    name: "DetailProduct",
    props: {
        active: {
            type: Boolean,
            required: true,
            default: false,
        },
        dataProduct: {
            type: Object,
            required: true,
        }
    },
    emits: ['update:active'], // Emit untuk mengupdate state aktif
    setup(props, { emit }) {
        const activeModel = computed({
            get: () => props.active,
            set: (value) => {
                emit("update:active", value) // Memastikan perubahan dari drawer juga mengubah parent
            }
        });

        return {
            activeModel,
            formatRupiah
        };
    }
});
</script>
