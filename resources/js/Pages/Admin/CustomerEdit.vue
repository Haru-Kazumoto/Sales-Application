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
        <div class="card shadow-sm border-0 mb-5">
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
                        <label for="nama_customer" class="form-label">Badan Usaha</label>
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
                        <n-input placeholder="" size="large" v-model:value="form.phone"
                            :on-input="(value) => form.phone = value.replace(/\D/g, '')" />
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
                    <!-- Input NPWP Image -->
                    <div class="col-12 col-md-6 d-flex flex-column">
                        <label for="npwp_image" class="form-label">Upload NPWP</label>
                        <input type="file" class="form-control" id="npwp_image"
                            @change="handleImageUpload($event, 'npwp_image')" />
                        <!-- Preview NPWP Image -->
                        <div v-if="form.npwp_image" class="mt-3">
                            <n-image :src="npwp_img_preview" alt="Preview NPWP" width="auto" height="200" />
                        </div>
                    </div>

                    <!-- Input KTP Image -->
                    <div class="col-12 col-md-6 d-flex flex-column">
                        <label for="ktp_image" class="form-label">Upload KTP</label>
                        <input type="file" class="form-control" id="ktp_image"
                            @change="handleImageUpload($event, 'ktp_image')" />
                        <!-- Preview KTP Image -->
                        <div v-if="form.ktp_image" class="mt-3">
                            <n-image :src="ktp_img_preview" alt="Preview KTP" width="auto" height="200" />
                        </div>
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

        const customerSelected = (page.props.parties as any);

        const form = useForm({
            _method: 'PUT',
            id: customerSelected.id,
            code: customerSelected.code || '',
            legality: customerSelected.legality || '',
            name: customerSelected.name || '',
            address: customerSelected.address || '',
            city: customerSelected.city || '',
            npwp: customerSelected.npwp || '',
            phone: customerSelected.phone || '',
            term_payment: customerSelected.term_payment || null as unknown as number,
            type_parties: customerSelected.type_parties || '',
            parties_group_id: customerSelected.parties_group_id || null as unknown as number,
            npwp_image: null as unknown as string, // Previous image data
            ktp_image: null as unknown as string, // Previous image data
        });
        const npwp_img_preview = ref(customerSelected.npwp_image);
        const ktp_img_preview = ref(customerSelected.ktp_image);


        function handleImageUpload(event: Event, type: 'npwp_image' | 'ktp_image') {
            const file = (event.target as HTMLInputElement).files?.[0];
            if (file) {
                // Save the file object directly for submission (no base64)
                form[type] = file;

                // Set preview URL for the corresponding image
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (type === 'npwp_image') {
                        npwp_img_preview.value = e.target?.result as string;
                    } else if (type === 'ktp_image') {
                        ktp_img_preview.value = e.target?.result as string;
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // Reset the image URL and file in form if no file is selected
                form[type] = null;
                if (type === 'npwp_image') {
                    npwp_img_preview.value = '';
                } else if (type === 'ktp_image') {
                    ktp_img_preview.value = '';
                }
            }
        }

        function handleSubmitCustomer() {
            const formData = new FormData();

            // Append form fields
            Object.keys(form).forEach((key) => {
                formData.append(key, form[key]);
            });

            form.put(route('admin.parties.customer.update', form.id), {
                data: formData,
                preserveScroll: false,
                onSuccess: () => {
                    form.reset();
                    notification.success({
                        title: (page.props.flash as Flash).success,
                        closable: false,
                        duration: 1500,
                    });
                },
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
            handleImageUpload,
            form,
            groups,
            customer_type,
            npwp_img_preview,
            ktp_img_preview,
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