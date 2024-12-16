<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Assign Customer" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.customer-region.index'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="region">Wilayah</label>
                        <n-input size="large" placeholder="" v-model:value="region.region" disabled />
                    </div>
                    <div class="col-6">
                        <label for="region">Harga angkutan wilayah</label>
                        <n-input size="large" placeholder="" v-model:value="cost" disabled />
                    </div>
                </div>
                <div class="card-title">
                    <div style="border: 1px solid grey;" class="my-3" />
                    <h5>Pilih Customer</h5>
                </div>
                <div class="row g-2">
                    <div class="col-6 ">

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="">Nama Customer</label>
                                <n-select size="large" placeholder="" filterable clearable :options="customerOptions"
                                    v-model:value="customer.name" />
                            </div>
                            <div class="col-6">
                                <label for="">Perusahaan</label>
                                <n-input size="large" placeholder="" v-model:value="customer.company" disabled />
                            </div>
                        </div>

                        <n-button type="info" size="medium" @click="addCustomer">Tambah customer</n-button>

                    </div>
                    <div class="col-6">
                        <div class="table-container">
                            <n-data-table :columns="columns" :data="form.customers" bordered size="large" />
                        </div>
                    </div>
                </div>

                <div class="d-flex mt-3">
                    <n-button class="ms-auto" type="primary" size="large"
                        @click="handleSubmitCustomers">Submit</n-button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <span class="fw-semibold">Data customer yang sudah ada</span>
                <n-data-table :bordered="false" :columns="customersColumns" :data="$page.props.assignedCustomers" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.table-container {
    max-height: 200px;
    /* Sesuaikan tinggi maksimum yang diinginkan */
    overflow-y: auto;
    /* Tambahkan scrollbar jika data melebihi tinggi ini */
}
</style>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { h } from 'vue';
import { NButton, NTag, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        function createColumns() {
            return [
                {
                    title: "#",
                    key: "index",
                    width: 50,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "NAMA CUSTOMER",
                    key: "name",
                    width: 200,
                },
                {
                    title: "BADAN USAHA",
                    key: "legality",
                    width: 150
                },
                {
                    title: "PERUSAHAAN",
                    key: "company",
                    width: 150,
                    render(row) {
                        return h(
                            NTag,
                            {
                                type: row.company === "DNP" ? "success" : "info",
                                strong: true,
                                bordered: true,
                            },
                            {
                                default: () => row.company
                            }
                        )
                    }
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 100,
                    render(row, index) {
                        return h(
                            NButton,
                            {
                                type: "error",
                                size: "small",
                                onClick: () => removeCustomer(index)
                            },
                            { default: () => "Hapus" }
                        )
                    }
                }
            ]
        }

        function createColumnCustomers() {
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
                    title: "BADAN USAHA",
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
                                onClick: () => {
                                    Swal.fire({
                                        icon: 'question',
                                        title: `Hapus customer ini dari region ${region.region}?`,
                                        showCancelButton: true,
                                        confirmButtonText: 'Hapus',
                                    }).then(result => {
                                        if (result.isConfirmed) {
                                            router.patch(route('admin.customer-region.unassign', row.id), {}, {
                                                onSuccess: (page) => {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: page.props.flash.success,
                                                        timer: 1000,
                                                        showConfirmButton: false,
                                                    });
                                                },
                                                onError: () => {
                                                    Swal.fire('Failed', 'neither me', 'error');
                                                }
                                            });
                                        }
                                    });
                                }
                            },
                            { default: () => "HAPUS" }
                        )
                    }
                }
            ]
        }

        const page = usePage();
        const region = page.props.customerRegion as any;
        const cost = formatRupiah(region.transportation_cost);
        const notification = useNotification();

        const customerOptions = (page.props.customers as any[]).map(data => ({
            label: data.name,
            value: data.name,
            company: data.company || 'null',
            legality: data.legality,
            id: data.id
        }));

        const customer = ref({
            id: null as unknown as number,
            name: '',
            company: '',
            legality: '',
        });

        const form = useForm<any>({
            customers: [] // Langsung gunakan form.customers
        });

        // Sinkronisasi data perusahaan dan legality secara otomatis berdasarkan pilihan customer
        watch(() => customer.value.name, (name) => {
            const selectedCustomer = customerOptions.find(option => option.label === name);
            if (selectedCustomer) {
                customer.value.id = selectedCustomer.id;
                customer.value.company = selectedCustomer.company;
                customer.value.legality = selectedCustomer.legality;
            } else {
                customer.value.company = '';
                customer.value.legality = '';
            }
        });

        function addCustomer() {
            if (!customer.value.name || !customer.value.company) {
                notification.warning({
                    title: "Pilih customer terlebih dahulu",
                    duration: 1500,
                    closable: false,
                });
                return;
            }

            const existingCustomer = form.customers.find(item => item.name === customer.value.name);
            if (existingCustomer) {
                notification.error({
                    title: "Customer sudah ada di table",
                    meta: "Pilih customer lain",
                    duration: 1500,
                    closable: false,
                });
                return;
            }

            form.customers.push({ ...customer.value });

            // Reset data customer setelah menambahkan
            customer.value = {
                id: null as unknown as number,
                name: '',
                company: '',
                legality: '',
            };
        }

        function removeCustomer(index: number) {
            form.customers.splice(index, 1);
        }

        function handleSubmitCustomers() {
            if(form.customers.length < 1) {
                notification.error({
                    title: "Pilih minimal 1 customer!",
                    duration: 1500,
                    closable: false,
                });
                return;
            }
            form.patch(route('admin.customer-region.assign-all', region.id), {
                onSuccess: (page) => {
                    form.reset();
                    Swal.fire({
                        title: page.props.flash.success,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                onError: () => {
                    Swal.fire('Oops, server sedang sibuk :(', 'Tunggu sebentar atau hubungi developer segera', 'error');
                }
            });
        }


        return {
            region,
            cost,
            router,
            ArrowBack,
            customerOptions,
            form,
            customer,
            addCustomer,
            removeCustomer,
            handleSubmitCustomers,
            columns: createColumns(),
            customersColumns: createColumnCustomers(),
        }
    },
    components: {
        TitlePage
    }
})
</script>
