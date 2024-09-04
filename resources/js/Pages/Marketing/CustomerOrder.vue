<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="Customer Order" />
        <div class="d-flex flex-column gap-2">
            <span class="fs-5">PO/CO List</span>
            <div class="card shadow" style="border: none; ">
                <div class="card-body">
                    <n-data-table :columns="columns" :data="data" :pagination="pagination" :bordered="false"
                        size="small" pagination-behavior-on-filter="first" />
                    <n-button type="primary">Create PO/CO</n-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import type { DataTableColumns } from 'naive-ui'
import { POCOList } from '../../types/dto'
import TitlePage from '../../Components/TitlePage.vue';

function createColumns(): DataTableColumns<POCOList> {
    return [
        {
            title: 'No',
            key: 'no',
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: 'No PO/CO & Tgl',
            key: 'poco_number'
        },
        {
            title: 'PT',
            key: 'pt'
        },
        {
            title: 'Salesman',
            key: 'salesman'
        },
        {
            title: 'Customer',
            key: 'customer'
        }
    ]
}

const data: POCOList[] = [
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
    { poco_number: "004/CO-DKU/VI/24", pt: "DKU", salesman: "AFRIYANTO", customer: "ABC" },
]

export default defineComponent({
    setup() {
        return {
            data,
            columns: createColumns(),
            pagination: { pageSize: 10 }
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>