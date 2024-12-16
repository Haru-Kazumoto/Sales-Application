<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Customer Region" />

        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column gap-3">
                <div class="row g-2">
                    <div class="col-6">
                        <label for="region">Wilayah</label>
                        <n-input size="large" placeholder="" v-model:value="form.region" @input="(value) => form.region = value.toUpperCase()"/>
                    </div>
                    <div class="col-6">
                        <label for="region">Harga angkutan wilayah</label>
                        <n-input size="large" placeholder="" v-model:value="form.transportation_cost" @input="(value) => form.transportation_cost = value.replace(/\D/g, '')">
                            <template #prefix>
                                Rp
                            </template>
                        </n-input>
                    </div>
                </div>

                <n-button class="ms-auto" type="primary" @click="handleSubmit">Buat wilayah</n-button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column">
                <n-data-table :bordered="false" :columns :data="$page.props.regions"></n-data-table>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import TitlePage from '../../../Components/TitlePage.vue';
import { router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { formatRupiah } from '../../../Utils/options-input.utils';
import { h } from 'vue';
import { NButton } from 'naive-ui';

export default defineComponent({
    setup () {
        function createColumns() {
            return [
                {
                    title: "#",
                    key: 'index',
                    width: 60,
                    render(row, index) {
                        return index + 1;
                    }
                },
                {
                    title: "NAMA WILAYAH",
                    key: 'region',
                    width: 150,
                },
                {
                    title: "HARGA ANGKUTAN WILAYAH",
                    key: 'transport_cost',
                    width: 200,
                    render(row) {
                        return formatRupiah(row.transportation_cost);
                    }
                },
                {
                    title: "ACTION",
                    key: "action",
                    width: 100,
                    render(row) {
                        return h(
                            NButton,
                            {
                                type: 'info',
                                size: 'medium',
                                onClick: () => {
                                    router.visit(route('admin.customer-region.assign', row.id));
                                }
                            },
                            {
                                default: () => "Assign Customer"
                            }
                        )
                    }
                }
            ]
        }

        const form = useForm({
            region: '',
            transportation_cost: null as unknown as number,
        });

        function handleSubmit() {
            form.post(route('admin.customer-region.store'), {
                onSuccess: (page) => {
                    Swal.fire({
                        icon: "success", 
                        title: page.props.flash.success,
                        timer: 2500,
                    });
                },
                onError: () => {
                    Swal.fire('Oops, server sedang sibuk :(', 'Tunggu sebentar atau hubungi developer segera', 'error');
                }
            });
        }

        return {
            handleSubmit,
            form,
            columns: createColumns(),
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>

</style>