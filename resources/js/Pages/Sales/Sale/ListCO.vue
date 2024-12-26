<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List CO" />

        <div class="row g-2">
            <div class="col-5 col-lg-2">
                <n-select size="large" placeholder="PERUSAHAAN" clearable />
            </div>
            <div class="col-6 col-lg-4">
                <n-input size="large" placeholder="CARI NOMOR CO" />
            </div>
        </div>
        <n-tabs type="line" animated>
            <n-tab-pane name="non-submission" tab="CO NON PENGAJUAN DISKON">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-data-table :columns="columns" :bordered="false"
                            :data="($page.props.customer_orders as any).data" size="small" />
                    </div>
                </div>
            </n-tab-pane>
            <n-tab-pane name="submission" tab="CO PENGAJUAN DISKON">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-data-table :columns="columns" :bordered="false"
                            :data="($page.props.draf_customer_orders as any).data" size="small" />
                    </div>
                </div>
            </n-tab-pane>
        </n-tabs>

    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { Transactions } from '../../../types/model.ts';
import { router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id'; // Import locale Indonesia

dayjs.locale('id'); // Set locale to Indonesian

export default defineComponent({
    setup() {
        const page = usePage();

        function createColumns(): DataTableColumns<Transactions> {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 30,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "TANGGAL CO",
                    key: "customer_order_date",
                    width: 150,
                    render(rowData) {
                        const co_date = rowData.transaction_details.find((data) => {
                            return data.category === "CO Date";
                        });

                        return co_date?.value ? dayjs(co_date?.value).format('dddd, D MMMM YYYY') : '';
                    },
                },
                {
                    title: "NOMOR CO",
                    key: "document_code",
                    width: 100,
                },
                {
                    title: "PERUSAHAAN",
                    key: "warehouse",
                    width: 150,
                    render(row) {
                        const warehouse = row.transaction_details.find(data => data.category === "Warehouse")?.value;

                        return h(NTag, { type: warehouse === "DNP" ? 'success' : 'info', strong: true, bordered: true }, { default: () => warehouse });
                    }
                },
                {
                    title: "CUSTOMER",
                    key: "customer_name",
                    width: 150,
                    render(rowData) {
                        const customer_name = rowData.transaction_details.find((data) => {
                            return data.category === "Customer";
                        });

                        return customer_name?.value;
                    },
                },
                // {
                //     title: "BADAN USAHA",
                //     key: "legality",
                //     width: 100,
                //     render(rowData) {
                //         const legality = rowData.transaction_details.find((data) => {
                //             return data.category === "Legality";
                //         })

                //         return legality?.value;
                //     },
                // },
                {
                    title: "STATUS PENGAJUAN CO",
                    key: "status",
                    width: 100,
                    render(row) {
                        let type;

                        switch (row.status) {
                            case "PENDING_ON_AGING":
                                type = "info";
                                break;
                            case "PENDING_ON_FINANCE":
                                type = "info";
                                break;
                            case "HOLD_BY_FINANCE":
                                type = "warning";
                                break;
                            case "HOLD_BY_AGING":
                                type = "warning";
                                break;
                            case "APPROVE":
                                type = "success";
                                break;
                            case "REJECT_BY_FINANCE":
                                type = 'error';
                                break;
                            case "REJECT_BY_AGING":
                                type = 'error';
                                break;
                            default:
                                type = '';
                                break;
                        }

                        return h(
                            NTag,
                            {
                                type,
                                strong: true,
                                size: 'large',
                            }, { default: () => row.status.replace(/_/g, " ") }
                        )
                    }
                },
                {
                    title: "STATUS PENGAJUAN DISKON",
                    key: "status_diskon",
                    width: 150,
                    render(row) {
                        let type;
                        const status = row.transaction_details.find(data => data.category === "Submission Status")?.value;

                        switch (status) {
                            case "true":
                                type = "success";
                                break;
                            case "false":
                                type = "error";
                                break;
                            default:
                                type = "";
                                break;
                        }

                        return h(
                            NTag,
                            {
                                type,
                                strong: true,
                                size: 'large',
                            }, { 
                                default: () => {
                                    if(status === "true") {
                                        return "APPROVE";
                                    } else if (status === "false"){
                                        return "REJECT";
                                    } else {
                                        return "TIDAK ADA";
                                    }
                                }
                            }
                        )
                    }
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 50,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "primary",
                                size: 'small',
                                onClick: () => {
                                    router.get(route('sales.detail-co', row.id));
                                }
                            },
                            { default: () => "Detail" }
                        )
                    }
                }
            ]
        }

        return {
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>