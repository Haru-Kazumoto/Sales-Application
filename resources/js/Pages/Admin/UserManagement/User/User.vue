<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="User Management" />
        <div class="row g-0">
            <div class="col-sm-6 col-md-8">
                <Link :href="route('admin.create-user')">
                    <n-button type="primary">
                        <template #icon>
                            <n-icon :component="Add" size="20" />
                        </template>
                        Tambah User
                    </n-button>
                </Link>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center gap-2 ">
                <span>Pencarian Data : </span>
                <n-input style="width: auto;"></n-input>
            </div>
        </div>
        <div class="card shadow" style="border: none;">
            <div class="card-body">
                <n-data-table :columns="columns" :data="$page.props.users" :bordered="false" :single-line="false"
                    size="small" pagination-behavior-on-filter="first" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../../Components/TitlePage.vue';
import { Add, SettingsOutline, TrashBinOutline } from '@vicons/ionicons5';
import { DataTableColumns, NButton, NIcon, useNotification } from 'naive-ui';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Division, Flash, Role } from '../../../../types/model';
import Swal from 'sweetalert2';

interface RowData {
    id: number;
    user_uid: string;
    fullname: string;
    division: Division;
    role: Role;
}

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();

        function createColumns(): DataTableColumns<RowData> {
            return [
                {
                    title: '#',
                    key: 'index',
                    render(rowData, rowIndex) {
                        return rowIndex + 1;  // Menghitung nomor urut
                    },
                },
                {
                    title: 'User UID',
                    key: 'user-uid',
                    render(rowData) {
                        return rowData.user_uid;  // Menampilkan nomor faktur
                    },
                },
                {
                    title: 'Nama karyawan',
                    key: 'employee-name',
                    render(rowData) {
                        return rowData.fullname;
                    },
                },
                {
                    title: 'Divisi',
                    key: 'division',
                    render(rowData) {
                        return rowData.division.division_name;
                    },
                },
                {
                    title: 'Role',
                    key: 'role',
                    render(rowData) {
                        return rowData.role.role_name;
                    },
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
                                        router.visit(route('admin.edit-user', row.id), { method: 'get' });
                                    }
                                },
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
                                            title: `Hapus user ${row.fullname}?`,
                                        }).then((result) => {
                                            if(result.isConfirmed){
                                                router.delete(route('admin.delete-user.delete', row.id), {
                                                    onSuccess: () => {
                                                        notification.success({
                                                            title: (page.props.flash as Flash)['succcess'],
                                                            duration: 1500,
                                                        });
                                                    }
                                                });
                                            }
                                        })
                                    }
                                },
                            )
                        ]);
                    }
                }
            ];
        }

        return {
            columns: createColumns(),
            Add
        }
    },
    components: {
        TitlePage,
        Link
    }
})
</script>