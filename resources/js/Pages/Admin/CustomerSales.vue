<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="Customer Sales" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="card-title">
                    <span class="fs-5 fw-semibold">List user sales</span>
                </div>
                <n-data-table :bordered="false" :columns="columns" :data="($page.props.sales_users as any).data" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { router, usePage } from '@inertiajs/vue3';
import { NButton } from 'naive-ui';

export default defineComponent({
    setup () {
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
                    title: "USER UID",
                    key: "user_uid",
                    width: 150,
                },
                {
                    title: "NAMA SALESMAN",
                    key: "fullname",
                    width: 250,
                },
                {
                    title: "DIVISI",
                    key: "division",
                    width: 150,
                    render(row) {
                        return row.division.division_name;
                    }
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 100,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "info",
                                onClick: () => {
                                    router.visit(route('admin.assign-customer-sales', row.id));
                                }
                            },
                            { default: () => "Assign Customer"}
                        )
                    }
                }
            ]
        }

        const page = usePage();
        const sales_users = page.props.sales_users as any[];

        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>