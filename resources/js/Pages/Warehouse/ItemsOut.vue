<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="DAFTAR PRODUK KELUAR" />
        <div class="d-flex flex-column gap-2">
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="$page.props.products" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, h } from 'vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import dayjs from "dayjs";
import 'dayjs/locale/id';
import TitlePage from '../../Components/TitlePage.vue';

dayjs.locale('id');

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
            title: 'NOMOR CO',
            key: 'document_code',
            width: 150,
        },
        {
            title: 'JUMLAH BARANG',
            key: 'quantity',
            width: 150,
        },
        {
            title: 'NAMA BARANG',
            key: 'product_name',
            width: 200,
        },
        {
            title: 'KODE BATCH BARANG',
            key: 'batch_code',
            width: 200,
        },
        {
            title: "BARANG PERUSAHAAN",
            key: "warehouse",
            width: 150,
            render(row) {
                return h(NTag, {
                    type: row.warehouse === "DNP" ? "success" : 'info',
                    strong: true,
                    bordered: true,
                    size: 'medium',
                }, {
                    default: () => row.warehouse
                });
            }
        },
        {
            title: "STATUS",
            key: "status",
            width: 150,
            render(row) {
                return h(
                    NTag, {
                        type: row.status === "APPROVE" ? 'success' : 'warning',
                        strong: true,
                        bordered: true,
                        size: 'medium'
                    }, {
                        default: () => row.status
                    }
                );
            }
        }
    ];
}

export default defineComponent({
    setup() {

        // Pagination dummy data
        const pagination = reactive({
            page: 1,
            pageSize: 10,
        });

        return {
            pagination,
            columns:  createColumns(),
            dayjs,
        };
    },
    components: {
        TitlePage,
    }
});
</script>

<style scoped></style>