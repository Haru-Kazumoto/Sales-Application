<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="List Claim Promo" />

        <!-- Bagian filter: responsive untuk input -->
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <n-select placeholder="BULAN" size="large" />
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <n-input placeholder="NO KLAIM" size="large" />
            </div>
        </div>

        <!-- Bagian tabel: responsive dengan card -->
        <div class="card shadow-sm" style="border: none;">
            <div class="card-body">
                <n-data-table :bordered="false" :data="data" :columns="columns" size="small" />
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, h } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { DataTableColumns, NButton, NTag } from 'naive-ui';

interface RowData {
    key: number;
    promo_claim_date: string;
    promo_claim_number: string;
    distributor_name: string;
    total_claim: number;
    status_claim: string;
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            title: '#',
            key: 'index',
            width: 50,
            render(rowData, rowIndex) {
                return rowIndex + 1; // Untuk memberikan nomor urut baris
            },
        },
        {
            title: "TGL KLAIM PROMO",
            key: 'promo_claim_date',
            width: 200,
            render(row) {
                return row.promo_claim_date;
            }
        },
        {
            title: "NOMOR KLAIM PROMO",
            key: 'promo_claim_number',
            width: 200,
            render(row) {
                return row.promo_claim_number;
            }
        },
        {
            title: "NAMA DISTRIBUTOR",
            key: 'distributor_name',
            width: 250,
            render(row) {
                return row.distributor_name;
            }
        },
        {
            title: "TOTAL KLAIM",
            key: 'total_claim',
            width: 200,
            render(row) {
                return formatRupiah(row.total_claim);
            }
        },
        {
            title: "STATUS",
            key: 'status_claim',
            width: 200,
            render(row) {
                let type: any;

                switch (row.status_claim) {
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
                    { default: () => row.status_claim }
                );
            }
        },
        {
            title: 'Action',
            key: 'actions',
            render(row) {
                return h('div', { class: 'd-flex gap-2' }, [
                    h(
                        NButton,
                        {
                            type: 'info',
                            size: 'small',
                            onClick: () => {
                                alert(`${row.key} is selected!`);
                            }
                        },
                        { default: () => 'Detail' }
                    )
                ]);
            }
        }
    ];
}

const data: RowData[] = [
    {
        key: 1,
        promo_claim_date: '12/02/2024',
        promo_claim_number: '007/DKU/ACC/1/2024',
        distributor_name: 'PT DUTA KOMODITI UTAMA',
        total_claim: 22000,
        status_claim: 'UNPAID',
    },
    {
        key: 1,
        promo_claim_date: '12/02/2024',
        promo_claim_number: '007/DKU/ACC/1/2024',
        distributor_name: 'PT DUTA KOMODITI UTAMA',
        total_claim: 22000,
        status_claim: 'UNPAID',
    },
    {
        key: 1,
        promo_claim_date: '12/02/2024',
        promo_claim_number: '007/DKU/ACC/1/2024',
        distributor_name: 'PT DUTA KOMODITI UTAMA',
        total_claim: 22000,
        status_claim: 'UNPAID',
    },
    {
        key: 1,
        promo_claim_date: '12/02/2024',
        promo_claim_number: '007/DKU/ACC/1/2024',
        distributor_name: 'PT DUTA KOMODITI UTAMA',
        total_claim: 22000,
        status_claim: 'UNPAID',
    }
];


export default defineComponent({
    setup() {


        return {
            data,
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>