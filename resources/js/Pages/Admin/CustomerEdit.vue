<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Edit Customer" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('admin.parties.customer'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form class="row g-3" @submit.prevent="handleSubmitCustomer">
                    <!-- First row -->
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kode Customer</label>
                        <n-input placeholder="" size="large" v-model:value="form.code"
                            :on-input="(value) => form.code = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Nama Customer</label>
                        <n-input placeholder="" size="large" v-model:value="form.name"
                            :on-input="(value) => form.name = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Legalitas</label>
                        <n-input placeholder="" size="large" v-model:value="form.legality"
                            :on-input="(value) => form.legality = value.toUpperCase()" />
                    </div>

                    <!-- Second row -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Tipe</label>
                        <n-select placeholder="" size="large" v-model:value="form.type_parties"
                            :options="customer_type" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kelompok</label>
                        <n-select placeholder="" size="large" v-model:value="form.parties_group_id" :options="groups" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Termin Pembayaran</label>
                        <n-input placeholder="" size="large" v-model:value="form.term_payment"
                            :on-input="(value) => form.term_payment = value.replace(/\D/g, '')" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">NPWP</label>
                        <n-input placeholder="" size="large" v-model:value="form.npwp" />
                    </div>

                    <!-- Third row -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">No. Telpon</label>
                        <n-input placeholder="" size="large" v-model:value="form.phone" :on-input="(value) => form.phone = value.replace(/\D/g, '')" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Kota</label>
                        <n-input placeholder="" size="large" v-model:value="form.city"
                            :on-input="(value) => form.city = value.toUpperCase()" />
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex flex-column">
                        <label for="nama_customer" class="form-label">Alamat</label>
                        <n-input placeholder="" size="large" v-model:value="form.address" />
                    </div>

                    <div class="d-flex">
                        <n-button type="primary" class="ms-auto" attr-type="submit">Update Customer</n-button>
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
import { ArrowBack } from '@vicons/ionicons5';
import { DataTableColumns, NButton, useNotification } from 'naive-ui';
import { Flash, Lookup, Parties, PartiesGroup } from '../../types/model';

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();

        const customerSelected = (page.props.parties as Parties);

        const form = useForm<Parties>({
            id: customerSelected.id,
            code: customerSelected.code || '',
            legality: customerSelected.legality || '',
            name: customerSelected.name || '',
            address: customerSelected.address || '',
            city: customerSelected.city || '',
            npwp: customerSelected.npwp || '',
            phone: customerSelected.phone || '',
            term_payment: customerSelected.term_payment || null as unknown as number ,
            type_parties: customerSelected.type_parties || '',
            parties_group_id: customerSelected.parties_group_id || null as unknown as number,
        });

        function handleSubmitCustomer() {
            form.patch(route('admin.parties.customer.update', form.id), {
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

        const customer_type = (page.props.customer_type as Lookup[]).map((data) => ({
            label: data.label,
            value: data.value,
        }));

        return {
            handleSubmitCustomer,
            form,
            groups,
            customer_type,
            router,
            ArrowBack
        }
    },
    components: {
        TitlePage,
    }
})
</script>

<style scoped></style>