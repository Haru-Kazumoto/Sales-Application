<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Role management" />
        <div class="d-flex gap-3">
            <Link :href="route('admin.create-role')">
                <n-button type="primary">
                    Tambah User
                </n-button>
            </Link>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :data="$page.props.roles" :bordered="false" :single-line="false"
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
import { Flash } from '../../../../types/model';

interface RowData {
    id: number;
    role_name: string;
    role_uid: string;
}

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

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
                    title: 'Role UID',
                    key: 'role-uid',
                    render(row) {
                        return row.role_uid;
                    }
                },
                {
                    title: 'Role Name',
                    key: 'role-name',
                    render(row) {
                        return row.role_name;
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
                                    onClick: () => {
                                        router.visit(route('admin.edit-role', row.id), {method: 'get'})
                                    }
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
                                            title: `Hapus role ${row.role_name}?`
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(route('admin.delete-role.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: (page.props.flash as Flash)['success'],
                                                            duration: 1500,
                                                        });
                                                    }
                                                })
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