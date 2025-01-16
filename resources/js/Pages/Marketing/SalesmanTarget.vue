<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="TARGET SALES" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns" :data="$page.props.salesman" />
            </div>
        </div>
    </div>
    <!-- <n-modal v-model:show="showModal" class="custom-card" preset="card" :style="bodyStyle" title="Modal"
        :bordered="false" size="huge" :segmented="segmented">
        Content
        <template #footer>
            <div class="d-flex">
                <div class="d-flex ms-auto gap-2">
                    <n-button type="error" @click="showModal = false">TUTUP</n-button>
                </div>
            </div>
        </template>
    </n-modal> -->
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { h } from 'vue';
import { NButton } from 'naive-ui';
import { router } from '@inertiajs/vue3';
import { formatRupiah } from '../../Utils/options-input.utils';


export default defineComponent({
    setup() {
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
                    title: "SALESMAN",
                    key: "fullname",
                    width: 160,
                },
                // {
                //     title: "TARGET BULANAN",
                //     key: "monthly_target",
                //     width: 150,
                //     // render(row) {
                //     //     return formatRupiah(row.user_target.monthly_target) ?? 0;
                //     // }
                // },
                // {
                //     title: "TARGET TAHUNAN",
                //     key: "annual_target",
                //     width: 150,
                //     // render(row) {
                //     //     return formatRupiah(row.user_target.annual_target) ?? 0;
                //     // }
                // },
                {
                    title: "AKSI",
                    key: "action",
                    width: 100,
                    render(row) {
                        return h('div', { class: 'd-flex gap-2' }, [
                            h(
                                NButton,
                                {
                                    type: "primary",
                                    size: 'medium',
                                    onClick: () => {
                                        router.get(route('marketing.create-target', row.id));
                                    }
                                }, { default: () => "EDIT TARGET" }
                            ),
                            // h(
                            //     NButton,
                            //     {
                            //         type: "info",
                            //         size: "medium",
                            //         onClick: () => { showModal.value = true }
                            //     }, { default: () => "DETAIL" }
                            // )
                        ]);
                    }
                }
            ]
        }

        const showModal = ref(false);

        return {
            columns: createColumns(),
            showModal,
            bodyStyle: {
                width: '600px'
            },
            segmented: {
                content: 'soft',
                footer: 'soft'
            } as const,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>