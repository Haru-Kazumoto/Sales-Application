<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="TARGET SALES" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="$page.props.salesman" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { h } from 'vue';
import { NButton } from 'naive-ui';
import { router } from '@inertiajs/vue3';

function createColumns(){
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
            title: "SALESMAN",
            key: "fullname",
            width: 160,
        },
        {
            title: "TARGET BULANAN",
            key: "monthly_target",
            width: 150,
        },
        {
            title: "TARGET TAHUNAN",
            key: "annual_target",
            width: 150,
        },
        {
            title: "AKSI",
            key: "action",
            width: 100,
            render(row) {
                return h('div', {class: 'd-flex gap-2'}, [
                    h(
                        NButton,
                        {
                            type: "primary",
                            size: 'medium',
                            onClick: () => {
                                router.get(route('marketing.create-target', row.id));
                            }
                        }, {default: () => "EDIT TARGET"}
                    ),
                    h(
                        NButton,
                        {
                            type: "info",
                            size: "medium",
                            onClick: () => {alert('clicked');}
                        }, {default: () => "DETAIL"}
                    )
                ]);
            }
        }
    ]
}

export default defineComponent({
    setup () {
        

        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>

</style>