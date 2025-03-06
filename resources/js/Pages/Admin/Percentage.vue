<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Persentase Harga" />

        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex">
                    <n-button class="ms-auto" type="primary" @click="showCreateDrawer = true">Tambah Persentase Baru</n-button>
                </div>
                <n-data-table :columns :data></n-data-table>
            </div>
        </div>

        <n-drawer v-model:show="showCreateDrawer" :width="502" placement="right">
            <n-drawer-content title="Buat Persentase Baru">
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-column">
                        <label for="">Persentase</label>
                        <n-input size="large" placeholder="" v-model:value="form.label">
                            <template #prefix>%</template>
                        </n-input>
                    </div>
    
                    <div class="d-flex">
                        <n-button type="primary" class="ms-auto" @click="handleSubmitPercentage">Submit</n-button>
                    </div>
                </div>
            </n-drawer-content>
        </n-drawer>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, ref } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { NButton, useNotification } from 'naive-ui';
import { router, useForm } from '@inertiajs/vue3';

export default defineComponent({
    props: {
        data: { type: Array, required: true }
    },
    setup() {
        const showCreateDrawer = ref(false);
        const notification = useNotification();
        const form = ref({
            label: null as unknown as string,
            value: null as unknown as number
        });

        function handleSubmitPercentage() {

            router.post(route('admin.percentage.store'),{
                label: form.value.label,
                value: form.value.value
            }, {
                preserveScroll: true,
                onSuccess: (page) => {
                    showCreateDrawer.value = false;
                    notification.success({
                        title: page.props.flash.success,
                        duration: 2000,
                        closable: false
                    });
                    form.value.label = null as unknown as string;
                    form.value.value = null as unknown as number;
                },
                onError: () => {
                    notification.error({
                        title: "Gagal menambahkan persentase",
                        duration: 2000,
                        closable: false
                    });
                }
            })
        }

        function createColumns() {
            return [
                {
                    title: "#",
                    key: "index",
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "Label Persentase",
                    key: "label",
                    width: 200
                },
                {
                    title: "Isi Persentase",
                    key: "value",
                    width: 200
                },
                {
                    title: "Aksi",
                    key: "action",
                    width: 50,
                    render(row) {
                        return h('div', { class: "d-flex gap-2" }, [
                            h(
                                NButton,
                                {
                                    type: "info",
                                    strong: true,
                                }, { default: () => "Update" }
                            ),
                            h(
                                NButton,
                                {
                                    type: "error",
                                    strong: true,
                                }, { default: () => "Hapus" }
                            )
                        ]);
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
            handleSubmitPercentage,
            showCreateDrawer,
            form
        }
    },
    components: {
        TitlePage
    }
})
</script>