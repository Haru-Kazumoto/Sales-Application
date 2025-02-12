<template>
    <label for="">
        {{ label }}
        <RequiredMark v-if="required" />
    </label>
    <n-input size="large" placeholder="" :value="formattedValue" @input="handleInput">
        <template #prefix>
            Rp
        </template>
    </n-input>
</template>

<script>
import { computed } from 'vue';
import { NInput } from 'naive-ui';

export default {
    components: {
        NInput,
    },
    props: {
        modelValue: {
            type: [String, Number],
            default: '',
        },
        label: {
            type: String,
            default: 'Harga',
        },
        required: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        // Format nilai untuk ditampilkan di input
        const formattedValue = computed(() => {
            return formatCurrency(props.modelValue);
        });

        // Fungsi untuk memformat nilai dengan separator ribuan
        const formatCurrency = (value) => {
            if (!value) return '';
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        // Fungsi untuk menghapus separator dan mengupdate nilai asli
        const handleInput = (value) => {
            // Hapus semua titik (separator) dan koma (jika ada)
            const rawValue = value.replace(/\./g, '');
            emit('update:modelValue', rawValue); // Kirim nilai asli ke parent
        };

        return {
            formattedValue,
            handleInput,
        };
    },
};
</script>