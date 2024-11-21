<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Daftar Supplier" />
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitSupplier">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kode Pemasok</label>
                        <n-input placeholder="" size="large" v-model:value="form.code" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Nama Pemasok</label>
                        <n-input placeholder="" size="large" v-model:value="form.name" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Badan Usaha</label>
                        <n-input placeholder="" size="large" v-model:value="form.legality" />
                    </div>

                    <!-- Second row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Tipe</label>
                        <n-select size="large" v-model:value="form.type_parties" :options="supplier_type" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kelompok</label>
                        <n-select size="large" v-model:value="form.parties_group_id" placeholder="" :options="groups" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">NPWP</label>
                        <n-input placeholder="" size="large" v-model:value="form.npwp" />
                    </div>

                    <!-- Third row -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">No. Telpon</label>
                        <n-input placeholder="" size="large" v-model:value="form.phone" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kota</label>
                        <n-input placeholder="" size="large" v-model:value="form.city" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Alamat</label>
                        <n-input placeholder="" size="large" v-model:value="form.address" />
                    </div>

                    <div class="d-flex">
                        <n-button type="primary" class="ms-auto" attr-type="submit">Tambah Data</n-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, h, watch } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3';
import TitlePage from '../../Components/TitlePage.vue';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { Flash, Lookup, Parties, PartiesGroup } from '../../types/model';
import Swal from 'sweetalert2';


export default defineComponent({
    setup() {
        const page = usePage();
        const currentPage = ref(1);
        const notification = useNotification();

        const selectedSupplier = (page.props.parties as Parties);

        const form = useForm<Parties>({
            id: selectedSupplier.id,
            code: selectedSupplier.code || '',
            name: selectedSupplier.name || '',
            legality: selectedSupplier.legality || '',
            parties_group_id: selectedSupplier.parties_group_id || null as unknown as number,
            type_parties: selectedSupplier.type_parties || '',
            npwp: selectedSupplier.npwp || '',
            phone: selectedSupplier.phone || '',
            city: selectedSupplier.city || '',
            address: selectedSupplier.address || '',
        });

        function handleSubmitSupplier() {
            form.patch(route('admin.parties.supplier.update', form.id), {
                preserveScroll: false,
                onSuccess: () => {
                    form.reset();
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        closable: false,
                        duration: 1500,
                    });
                }
            });
        }

        const groups = (page.props.groups as PartiesGroup[]).map((data) => ({
            id: data.id,
            label: data.name,
            value: data.id,
        }));

        const supplier_type = (page.props.supplier_type as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        return {
            handleSubmitSupplier,
            currentPage,
            form,
            groups,
            supplier_type
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>