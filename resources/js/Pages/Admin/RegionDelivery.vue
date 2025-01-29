<template>
    <Head title="Harga Wilayah Pengiriman"></Head>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Harga Wilayah Pengiriman"></TitlePage>    

        <div class="d-flex flex-column gap-2">
            <n-button type="primary" size="large" class="ms-auto" @click="router.visit(route('admin.region-delivery.create'))">Buat Wilayah Baru</n-button>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <n-data-table :bordered="false" :columns="columns" :data="$page.props.data"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import { Head } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { formatRupiah } from '../../Utils/options-input.utils';
import { router } from '@inertiajs/vue3';

export default defineComponent({
    setup () {
        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 60,
                    render(row: any, index: number) {
                        return index + 1;
                    }
                },
                {
                    title: "NAMA WILAYAH",
                    key: 'region_name',
                    width: 150,
                },
                {
                    title: "KODE WILAYAH",
                    key: "region_code",
                    width: 150,
                },
                {
                    title: "HARGA PER-WILAYAH",
                    key: "region_price",
                    width: 200,
                    render(row: any) {
                        return formatRupiah(row.region_price);
                    }
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 150,
                    render(row) {
                        return h('div',{class: "d-flex gap-2"}, [
                            h('n-button', { type: 'primary', size: 'small' }, 'Edit'),
                            h('n-button', { type: 'danger', size: 'small' }, 'Hapus'),
                        ]);
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
            router
        }
    },
    components: {
        Head,
        TitlePage
    }
})
</script>

<style scoped>

</style>