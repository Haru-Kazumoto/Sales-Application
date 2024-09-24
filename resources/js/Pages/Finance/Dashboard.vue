<template>
    <div class="d-flex flex-column">
        <div class="d-flex flex-column gap-4">
            <TitlePage title="Finance Dashboard" />
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-md-4">
                    <SalesCountCard type="primary" title="Total Klaim Promo" :count="100" viewName="dashboard.finance" />
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <SalesCountCard type="primary" title="Klaim Promo Berbayar" :count="5" viewName="dashboard.finance" />
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <SalesCountCard type="warning" title="Tagihan Klaim Promo" :count="95" viewName="dashboard.finance" />
                </div>
            </div>
            <div class="card shadow" style="border: none;">
                <div class="card-body">
                    <n-data-table :bordered="false" :columns="columns" :data="data" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h } from 'vue';
import SalesCountCard from '../../Components/SalesCountCard.vue';
import ChartSales from '../../Components/ChartSales.vue';
import ChartSalesTarget from '../../Components/ChartSalesTarget.vue';
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns, NTag } from 'naive-ui';

interface RowData {
    id: number;
    promo_claim_number: string;
    customer_name: string;
    item_name: string;
    quantity: number;
    package: string;
    distributor: string;
    payment_status: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1;
            },
        },
        {
            title: "NO KLAIM PROMO",
            key: 'promo_claim_number',
            width: 200,
            render(row) {
                return row.promo_claim_number;
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: 'customer_name',
            width: 200,
            render(row) {
                return row.customer_name;
            }
        },
        {
            title: "NAMA BARANG",
            key: 'item_name',
            width: 250,
            render(row) {
                return row.item_name;
            }
        },
        {
            title: "QTY",
            key: 'quantity',
            width: 80,
            render(row) {
                return row.quantity;
            }
        },
        {
            title: "SATUAN",
            key: 'package',
            width: 100,
            render(row) {
                return row.package;
            }
        },
        {
            title: "NAMA DISTRIBUTOR",
            key: 'distributor',
            width: 200,
            render(row) {
                return row.distributor;
            }
        },
        {
            title: "STATUS PEMBAYARAN",
            key: 'payment_status',
            width: 200,
            render(row) {
                let type: any;

                switch (row.payment_status) {
                    case 'PAID':
                        type = 'success';
                        break;
                    case 'UNPAID':
                        type = 'warning';
                        break;
                    default:
                        type = 'success'; // Default type
                        break;
                }

                return h(
                    NTag,
                    {
                        bordered: false,
                        type: type,
                    },
                    { default: () => row.payment_status }
                );
            }

        }
    ]
}

export default defineComponent({
    setup() {

        const data = [
            { promo_claim_number: "007/DKU/ACC/1/2024", customer_name: "PUTERA GRAHA S,PT", item_name: "SANIA PREMIUM MARGARINE", quantity: 10, package: "KARTON", distributor: "DKU", payment_status: "PAID" }
        ]

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