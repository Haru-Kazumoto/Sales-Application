<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Menu Access List" />
        <n-tabs type="line" animated default-value="Table">
            <n-tab-pane name="Table" tab="Table">
                <div class="card shadow" style="border: none;">
                    <div class="card-body">
                        <n-data-table :columns="columns" :data="$page.props.menuAcceses" :bordered="false" :single-line="false"
                            size="small" />
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="Tree" tab="Tree">
                <n-tree
                    block-line
                    :data="treeData"
                    expand-on-click
                />
            </n-tab-pane>
        </n-tabs>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, useNotification, TreeOption } from 'naive-ui';
import { usePage } from "@inertiajs/vue3";

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();

        // Helper function to convert menu accesses into tree data
        function convertMenuToTree(menuAcceses: any[]): TreeOption[] {
            return menuAcceses.map((menu: any) => {
                const hasChildren = menu.children_count > 0;
                return {
                    label: menu.menu_name,
                    key: menu.id,
                    children: hasChildren ? convertMenuToTree(menu.children) : undefined,
                };
            });
        }

        // Get menu data from the page props
        const menuAcceses = page.props.menuAcceses as any;

        // Convert menu data to tree format
        const treeData = convertMenuToTree(menuAcceses);

        // Table Columns
        function createColumns(): DataTableColumns<any> {
            return [
                {
                    title: "#",
                    key: 'index',
                    render(row, index) {
                        return index + 1;
                    },
                },
                {
                    title: 'Nama menu',
                    key: 'menu_name',
                    render(row) {
                        return row.menu_name;
                    }
                },
                {
                    title: 'Menu URL',
                    key: 'menu_url',
                    render(row) {
                        return row.menu_url;
                    }
                },
                {
                    title: 'Jumlah Sub Menu',
                    key: 'children_count',
                    render(row) {
                        return row.children_count;
                    }
                },
                {
                    title: 'Base Menu',
                    key: 'base_menu_access_for',
                    render(row) {
                        return row.base_menu_access_for;
                    }
                },
                {
                    title: 'Action',
                    key: 'action',
                    width: 200,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: 'primary',
                                size: 'small',
                                onClick: () => {
                                    console.log(row.id);
                                }
                            },
                            {default: () => 'Detail'}
                        )
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
            treeData
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>
