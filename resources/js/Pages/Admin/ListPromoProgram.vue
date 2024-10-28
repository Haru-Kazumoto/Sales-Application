<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Program Promo" />

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-button type="primary" size="large" @click="router.visit(route('admin.create-promo'))">Buat Program
                    Promo</n-button>
                <n-data-table :bordered="false" :columns="columns" :data="$page.props.promos" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import { router } from "@inertiajs/vue3";
import { ArrowBack } from '@vicons/ionicons5';
import TitlePage from '../../Components/TitlePage.vue';
import { NButton, NTag } from 'naive-ui';
import dayjs from "dayjs";
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
                    title: "Nama Promo",
                    key: "name",
                    width: 180,
                },
                {
                    title: "Deskripsi Promo",
                    key: "description",
                    width: 260,
                },
                {
                    title: "Persentase Promo",
                    key: "promo_value",
                    width: 150,
                    render(row) {
                        return `${row.promo_value} %`
                    }
                },
                {
                    title: "Quantity Minimum",
                    key: "min",
                    width: 160,
                },
                {
                    title: "Quantity Maximum",
                    key: "max",
                    width: 160,
                },
                {
                    title: "Kupon mulai aktif",
                    key: "start_date",
                    width: 250,
                    render(row) {
                        return dayjs(row.start_date).format('DD MMMM YYYY HH:mm:ss');
                    }
                },
                {
                    title: "Kupon berakhir",
                    key: "end_date",
                    width: 250,
                    render(row) {
                        return dayjs(row.end_date).format('DD MMMM YYYY HH:mm:ss');
                    }
                },
                {
                    title: "Status Kupon",
                    key: "status",
                    width: 150,
                    render(row) {
                        return h(NTag, {
                            type: row.status === 'BERAKHIR' ? 'error' : 'success', // Menentukan tipe berdasarkan status
                        }, row.status); // Menampilkan status langsung
                    }
                },
                {
                    title: "Aksi",
                    key: "action",
                    width: 100,
                    render(row, index) {
                        return h(
                            NButton,
                            {
                                type: "primary",
                                size: "small",
                                onClick: () => { router.visit(route('admin.detail-promo', row.id)) }
                            },
                            { default: () => "Detail" }
                        )
                    }
                }
            ]
        }

        return {
            router,
            columns: createColumns(),
            ArrowBack
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>