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
import { NButton } from 'naive-ui';
import Swal from 'sweetalert2';

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
                            h(NButton, {
                                type: 'info',
                                size: 'medium',
                                onClick: () => {
                                    router.visit(route('admin.region-delivery.edit', row.id));
                                }
                            }, {default: () => 'Update'}),
                            h(NButton, {
                                type: 'error',
                                size: 'medium',
                                onClick: () => {
                                    handleDelete(row.id, row.region_name);
                                }
                            }, {default: () => 'Hapus'}),
                        ]);
                    }
                }
            ]
        }

        function handleDelete(id: number, name: string) {
            Swal.fire({
                title: `Hapus wilayah ${name}?`,
                icon: "question",
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    router.delete(route('admin.region-delivery.delete', id), {
                        onSuccess: (page) => {
                            Swal.fire(page.props.flash.success, '', 'success');
                        },
                        onError: () => {
                            Swal.fire('Gagal menghapus data', '', 'error');
                        }
                    });
                }
            });
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