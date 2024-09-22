<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Role management" />
        <div class="d-flex gap-3">
            <Link :href="route('admin.create-division')">
                <n-button type="primary">
                    Tambah User
                </n-button>
            </Link>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :data="$page.props.divisions" :bordered="false" :single-line="false"
                    size="small" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NIcon, useNotification } from 'naive-ui';
import { SettingsOutline, TrashBinOutline } from "@vicons/ionicons5";
import { router, Link, usePage } from "@inertiajs/vue3";
import Swal from 'sweetalert2';
interface RowData {

    id: number;
    division_name: string;
    division_uid: string;
}

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();

        function createColumns(): DataTableColumns<RowData> {
            return [
                {
                    title: "#",
                    key: 'index',
                    render(row, index) {
                        return index + 1;
                    },
                },
                {
                    title: 'Division UID',
                    key: 'role-uid',
                    render(row) {
                        return row.division_uid;
                    }
                },
                {
                    title: 'Division Name',
                    key: 'role-name',
                    render(row) {
                        return row.division_name;
                    }
                },
                {
                    title: 'Action',
                    key: 'action',
                    width: 200,
                    render(row) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
                                NButton,
                                {
                                    type: 'info',
                                    size: 'small',
                                    renderIcon() {
                                        return h(NIcon, { component: SettingsOutline })
                                    },
                                    onClick: () => router.visit(route('admin.edit-division', row.id))
                                }
                            ),
                            h(
                                NButton,
                                {
                                    type: 'error',
                                    size: 'small',
                                    renderIcon() {
                                        return h(NIcon, { component: TrashBinOutline })
                                    },
                                    onClick: () => {
                                        Swal.fire({
                                            icon: 'question',
                                            title: `Hapus divisi ${row.division_name}?`
                                        }).then((result) => {
                                            if(result.isConfirmed) {
                                                router.delete(route('admin.delete-division.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: "Divisi berhasil dihapus",
                                                            duration: 1500,
                                                        });
                                                    }
                                                });
                                            }
                                        })
                                    },
                                }
                            )
                        ])
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage,
        Link
    }
})
</script>

<style scoped></style>