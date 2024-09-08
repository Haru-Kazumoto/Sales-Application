<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Sales Dashboard" />
        <div class="d-flex flex-row gap-2">
            <n-date-picker size="large" type="date" style="width: 10rem;" />
            <n-date-picker size="large" type="date" style="width: 10rem;" />
            <n-select size="large" style="width: 10rem;" placeholder="Customer" />
            <n-select size="large" style="width: 10rem;" placeholder="Salesman" />
            <n-select size="large" style="width: 10rem;" placeholder="Jatuh tempo" />
        </div>
        <div class="d-flex flex-column">
            <span v-if="checkedRowKeys.length > 0" role="alert" class="alert alert-success">
                Select {{ checkedRowKeys.length }} row{{ checkedRowKeys.length < 2 ? '' : 's' }}
            </span>
            <n-data-table :columns="columns" :data="data" :pagination="pagination" :row-key="rowKey"
                        @update:checked-row-keys="handleCheck" />
            <n-button type="primary" class="ms-auto my-3" style="width: 15rem;" @click="handleSendReminder">Send Reminder</n-button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import type { DataTableColumns, DataTableRowKey } from 'naive-ui'
import Swal from 'sweetalert2';

interface RowData {
    key: number
    name: string
    age: string
    address: string
}

function createColumns(): DataTableColumns<RowData> {
    return [
        {
            type: 'selection',
        },
        {
            title: '#',
            key: 'index',
            render(rowData, rowIndex) {
                return rowIndex + 1;
            },
        },
        {
            title: 'Name',
            key: 'name'
        },
        {
            title: 'Age',
            key: 'age'
        },
        {
            title: 'Address',
            key: 'address'
        }
    ]
}

const data = Array.from({ length: 46 }).map((_, index) => ({
    name: `Edward King ${index}`,
    age: 32,
    address: `London, Park Lane no. ${index}`
}));

export default defineComponent({
    setup() {
        const checkedRowKeysRef = ref<DataTableRowKey[]>([]);

        function handleSendReminder() {
            if(checkedRowKeysRef.value.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'No data to send!',
                    text: 'At least check 1',
                });
            } else {
                //Create post logic send reminder here...
        
                Swal.fire({
                    title: 'Are you sure you want to send reminders?',
                    text: 'This action will send reminder text to customers',
                    icon: 'question',
                }).then((result) => {
                    if (result.isConfirmed){
                        Swal.fire({
                            icon: 'success',
                            title: 'Success send reminder',
                            text: `Reminder has send to ${checkedRowKeysRef.value.length} customer${checkedRowKeysRef.value.length < 2 ? '' : 's'}`
                        });
                    }
                });

            }
            
        }

        return {
            data,
            columns: createColumns(),
            handleSendReminder,
            checkedRowKeys: checkedRowKeysRef,
            pagination: {
                pageSize: 10
            },
            rowKey: (row: RowData) => row.address,
            handleCheck(rowKeys: DataTableRowKey[]) {
                checkedRowKeysRef.value = rowKeys
            }
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>