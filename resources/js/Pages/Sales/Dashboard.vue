<template>
    <div class="d-flex flex-column">
        <div class="d-flex flex-column gap-4">
            <div class="d-flex flex-row align-items-center">
                <TitlePage title="Dashboard" />
                <n-date-picker v-model:value="timestamp" type="date" class="ms-auto" />
            </div>
            <div class="d-flex flex-row align-items-center justify-content-between">
                <SalesCountCard type="primary" title="Total Klaim Promo" :count="100" viewName="dashboard.sales" />
                <SalesCountCard type="primary" title="Total Klaim Promo" :count="100" viewName="dashboard.sales" />
                <SalesCountCard type="primary" title="Total Klaim Promo" :count="100" viewName="dashboard.sales" />
            </div>
            <div class="d-flex flex-row gap-4" style="height: 28rem;">
                <div class="d-flex flex-column gap-4" style="width: 50rem;">
                    <n-date-picker v-model:value="timestamp" type="date" class="ms-auto" />
                    <div class="card shadow flex-grow-1" style="border: none;">
                        <div class="card-body d-flex" style="height: 100%;">
                            <ChartSales style="height: 100%; width: 100%;" />
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-grow-1">
                    <n-tabs type="line">
                        <n-tab-pane name="Penjualan" tabs="Penjualan">
                            <div class="card shadow flex-grow-1" style="border: none;">
                                <div class="card-body d-flex flex-column" style="height: 100%;">
                                    <span class="fs-5 fw-medium">Sales Target</span>
                                    <ChartSalesTarget style="height: 100%; width: 100%;" />
                                </div>
                            </div>
                        </n-tab-pane>
                        <n-tab-pane name="Aging" tabs="Aging">
                            <n-data-table :bordered="false" :columns="columns" :data="data" />
                        </n-tab-pane>
                    </n-tabs>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import SalesCountCard from '../../Components/SalesCountCard.vue';
import ChartSales from '../../Components/ChartSales.vue';
import ChartSalesTarget from '../../Components/ChartSalesTarget.vue';
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns } from 'naive-ui';

interface RowData {
    customer_name: string;
    due_date: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: 'Customer',
            key: 'customer_name',
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: 'Jatuh Tempo',
            key: 'due_date',
            render(row) {
                return row.due_date;
            }
        }
    ]
}

const data: RowData[] = [
    {customer_name: "ADIT", due_date: "04-04-2006"},
    {customer_name: "ADIT", due_date: "04-04-2006"},
    {customer_name: "ADIT", due_date: "04-04-2006"},
    {customer_name: "ADIT", due_date: "04-04-2006"},
    {customer_name: "ADIT", due_date: "04-04-2006"},
]

export default defineComponent({
    setup() {
        return {
            timestamp: ref(1183135260000),
            columns: createColumns(),
            data
        }
    },
    components: {
        SalesCountCard,
        ChartSales,
        ChartSalesTarget,
        TitlePage
    }
});
</script>

<style scoped></style>