<template>
    <Head title="Unsur Harga Utama"></Head>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Unsur Harga Utama"></TitlePage>

        <div class="d-flex flex-column gap-2">
            <n-button type="primary" size="large" class="ms-auto" @click="router.visit(route('admin.global-element-prices.create'))">Buat Unsur Harga</n-button>
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
                    title: "NAMA ELEMEN",
                    key: 'name_element',
                    width: 150,
                },
                {
                    title: "HARGA ELEMEN",
                    key: "price_element",
                    width: 150,
                    render(row) {
                        return formatRupiah(row.price_element);
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
                                    router.visit(route('admin.global-element-prices.edit', row.id)); // bagian ini kenapa
                                }
                            }, {default: () => 'Update'}),
                            h(NButton, {
                                type: 'error',
                                size: 'medium',
                                onClick: () => {
                                    handleDelete(row.id, row.name_element);
                                }
                            }, {default: () => 'Hapus'}),
                        ]);
                    }
                }
            ]
        }

        function handleDelete(id: number, name: string) {
            Swal.fire({
                title: `Hapus unsur ${name}?`,
                icon: "question",
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    router.delete(route('admin.global-element-prices.destroy', id), {
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