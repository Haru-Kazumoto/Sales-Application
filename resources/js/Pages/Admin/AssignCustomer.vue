<template>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Data Customer Sales" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.index-sales'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">   
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">USER UID</label>
                        <n-input readonly size="large" v-model:value="($page.props.user as any).user_uid"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">SALESMAN</label>
                        <n-input readonly size="large" v-model:value="($page.props.user as any).fullname"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column gap-1">
                        <label for="">DIVISI</label>
                        <n-input readonly size="large" v-model:value="($page.props.user as any).division.division_name"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="card-title">
                    <span class="fs-5 fw-semibold">Tambahkan customer</span>
                </div>
                <div style="border: 1px solid grey;" class="my-3"/>
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NAMA CUSTOMER</label>
                        <n-select placeholder="" size="large" :options="customerOptions" filterable  v-model:value="form.customer_name"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">ALAMAT CUSTOMER</label>
                        <n-input placeholder="" size="large" readonly v-model:value="form.customer_address"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">NPWP</label>
                        <n-input placeholder="" size="large" readonly v-model:value="form.customer_npwp"/>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">LEGALITAS</label>
                        <n-input placeholder="" size="large" readonly v-model:value="form.customer_legality"/>
                    </div>
                </div>
                <div class="d-flex mt-3">
                    <n-button size="medium" type="primary" class="ms-auto" @click="handleAssignCustomer">Tambah Customer</n-button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <span class="fw-semibold">Data customer yang sudah ada</span>
                <n-data-table :bordered="false" :columns :data="($page.props.user as any).parties" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h, watch } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';
import { ArrowBack } from '@vicons/ionicons5';
import {router, useForm, usePage} from "@inertiajs/vue3";
import { NButton } from 'naive-ui';
import Swal from 'sweetalert2';

export default defineComponent({
    setup () {
        function createColumns() {
            return [
                {
                    title: "#",
                    key: "index",
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "KODE CUSTOMER",
                    key: "code",
                    width: 200,
                },
                {
                    title: "NAMA CUSTOMER",
                    key: "name",
                    width: 200,
                },
                {
                    title: "ALAMAT CUSTOMER",
                    key: "address",
                    width: 200,
                },
                {
                    title: "NPWP",
                    key: "npwp",
                    width: 200,
                },
                {
                    title: "LEGALITAS",
                    key: 'legality',
                    width: 200,
                },
                {
                    title: "AKSI",
                    key: "action",
                    width: 100,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: "error",
                            },
                            { default: () => "HAPUS"}
                        )
                    }
                }
            ]
        }

        const page = usePage();
        const salesman = (page.props.user as any);

        const form = useForm({
            customer_id: null as unknown as number,
            sales_id: salesman.id,
            customer_name: '',
            customer_address: '',
            customer_npwp: '',
            customer_legality: '',
        });

        const customerOptions = (page.props.customers as any[]).map(data => ({
            id: data.id,
            label: data.name,
            value: data.name,
            address: data.address,
            npwp: data.npwp, 
            legality: data.legality,
        }));

        watch(() => form.customer_name, (value) => {
            const selectedCustomer = customerOptions.find(data => data.value === value);

            form.customer_id = selectedCustomer?.id;
            form.customer_address = selectedCustomer?.address;
            form.customer_npwp = selectedCustomer?.npwp;
            form.customer_legality = selectedCustomer?.legality;
        });

        function handleAssignCustomer(){
            if(!form.customer_name){
                Swal.fire('Harap mengisi nama customer', '', 'error');
            } else {
                Swal.fire({
                    title: "Tambahkan customer?",
                    text: `Customer ${form.customer_name} akan ditambahkan ke data sales ${salesman.fullname}`,
                    showCancelButton: true,
                    icon: "question"
                }).then((result) => {
                    if(result.isConfirmed) {
                        form.patch(route('admin.assign-customer-to-sales', { customer: form.customer_id, sales: form.sales_id }), {
                            onSuccess: (page) => {
                                form.reset();
                                Swal.fire((page.props.flash as any).success,'','success');
                            },
                            onError: () => {
                                Swal.fire('Gagal menambahkan customer', '', 'error');
                            }
                        });
                    }
                });
            }
        }

        return {
            columns: createColumns(),
            handleAssignCustomer,
            router,
            form,
            customerOptions,
            ArrowBack,
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>

</style>