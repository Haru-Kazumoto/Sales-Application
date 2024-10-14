<template>
    <div class="d-flex flex-column gap-3">
        <TitlePage title="List Invoice DNP" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" :columns="columns"
                    :data="($page.props.travel_documents_dnp as any).data" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { DataTableColumns, NButton, NTag } from 'naive-ui';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { router, usePage } from '@inertiajs/vue3';

function createColumns() {
    return [
        {
            title: "#",
            key: 'index',
            width: 50,
            render(row, index) {
                return index + 1;
            }
        },
        {
            title: "NOMOR SURAT JALAN",
            key: 'document_code',
            width: 150,
            render(row) {
                return h(NTag, { type: "info" }, { default: () => row.document_code });
            }
        },
        {
            title: "NOMOR CO",
            key: 'co_number',
            width: 150,
            render(row) {
                return row.transaction_details.find((data) => data.category === "CO Number")?.value;
            }
        },
        {
            title: "NAMA CUSTOMER",
            key: "customer_name",
            width: 150,
            render(row) {
                return row.transaction_details.find((data) => data.category === "Customer")?.value;
            }
        },
        {
            title: "SALESMAN",
            key: "salesman",
            width: 100,
            render(row) {
                return row.transaction_details.find((data) => data.category === "Salesman")?.value;
            }
        },
        {
            title: "LEGALITAS",
            key: "legality",
            width: 100,
            render(row) {
                return row.transaction_details.find(data => data.category === "Legality")?.value;
            }
        },
        {
            title: "TOTAL",
            key: "total",
            width: 100,
            render(row) {
                return formatRupiah(row.total);
            }
        },
        {
            title: "ACTION",
            key: 'action',
            width: 100,
            render(row) {
                return h(
                    NButton,
                    {
                        type: 'primary',
                        size: 'small',
                        onClick: () => {
                            router.visit(route('aging-finance.create-invoice', row.id), { method: 'get' });
                        }
                    },
                    {
                        default: () => 'Buat Faktur'
                    }
                )
            }
        }
    ]
}

export default defineComponent({
    setup() {
        const page = usePage();

        console.log(page.props.travel_documents);

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
