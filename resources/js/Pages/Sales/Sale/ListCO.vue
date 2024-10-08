<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List CO" />

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :columns="columns" :bordered="false" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';

export default defineComponent({
    setup() {

        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "TANGGAL CO",
                    key: "customer_order_date",
                    render(rowData) {
                        const storehouseData = rowData.transaction_details.find((data) => {
                            return data.category === "Storehouse";
                        })

                        return storehouseData?.value;
                    },
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