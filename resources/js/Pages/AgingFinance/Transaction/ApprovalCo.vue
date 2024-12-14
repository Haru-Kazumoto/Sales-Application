<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Approval Customer Order" />
        <div class="d-flex flex-column gap-3">
            <n-tabs type="line" animated>
                <n-tab-pane name="unprocessed" tab="BELUM DIPROSES">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <n-data-table :bordered="false" :columns="columns" :data="$page.props.not_process_yet_data" size="small" />
                        </div>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="processed" tab="DIPROSES">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <n-data-table :bordered="false" :columns="columns" :data="$page.props.data" size="small" />
                        </div>
                    </div>
                </n-tab-pane>
            </n-tabs>
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, h, ref, computed } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { NButton, NTag, type DataTableColumns, type DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';
import dayjs from "dayjs";
import 'dayjs/locale/id'; // Import locale Indonesia
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Flash } from '../../../types/model';

dayjs.locale('id'); // Set locale to Indonesian 

function createColumns() {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;  // Menghitung nomor urut
            },
        },
        {
            title: 'TANGGAL FAKTUR',
            key: 'invoice_date',
            width: 250,
            render(row) {
                return dayjs(row.tanggal_co).format('dddd, D MMMM YYYY');
            },
        },
        {
            title: 'NO FAKTUR',
            key: 'nomor_co',
            width: 200,
        },
        {
            title: "STATUS",
            key: "status",
            width: 200,
            render(row) {
                let type;

                switch (row.status) {
                    case "PENDING":
                        type = "info";
                        break;
                    case "HOLD":
                        type = "warning";
                        break;
                    case "APPROVE":
                        type = "success";
                        break;
                    case "REJECT":
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
                    }, { default: () => row.status }
                )
            }
        },
        {
            title: 'CUSTOMER',
            key: 'customer',
            width: 250,
        },
        {
            title: 'PERUSAHAAN',
            key: 'perusahaan',
            width: 200,
            render(rowData) {
                return h(
                    NTag, {
                    type: rowData.perusahaan === "DNP" ? 'success' : 'info',
                    strong: true,
                    size: 'large',
                },
                    {
                        default: () => rowData.perusahaan
                    }
                );
            },
        },
        {
            title: 'TOTAL FAKTUR YANG BELUM LUNAS',
            key: 'total_invoice_nunggak',
            width: 150,
        },
        {
            title: "ACTION",
            key: 'action',
            width: 150,
            render(row) {
                return h(
                    NButton, {
                    type: "info",
                    size: 'medium',
                    onClick: () => {
                        router.get(route('sales.detail-co', row.id));
                    }
                },
                    { default: () => "DETAIL" }
                );
            }
        }
    ];
}

export default defineComponent({
    setup() {


        return {
            columns: createColumns(),
            pagination: {
                pageSize: 10,
            },
        };
    },
    components: {
        TitlePage,
    },
});
</script>