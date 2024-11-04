<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="SETTING PESAN WA CUSTOMER" />

        <div class="d-flex">
            <div class="row g-5">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="d-flex flex-column w-100">
                        <n-tabs type="segment" animated>
                            <n-tab-pane name="Customer" tab="Customer">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="">Isi Pesan Untuk Customer</label>
                                        <n-input type="textarea" placeholder=""
                                            v-model:value="templateFormCustomer.message" />
                                    </div>
                                    <n-button class="ms-auto" type="primary"
                                        @click="handleSubmitTemplateCustomer(template_customer.id)">Simpan
                                        pesan</n-button>
                                </div>
                            </n-tab-pane>
                            <n-tab-pane name="Salesman" tab="Salesman">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="">Isi Pesan Untuk Salesman</label>
                                        <n-input type="textarea" placeholder="" v-model:value="templateForm.message" />
                                    </div>
                                    <n-button class="ms-auto" type="primary"
                                        @click="handleSubmitTemplate(template_sales.id)">Simpan pesan</n-button>
                                </div>
                            </n-tab-pane>
                        </n-tabs>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="w-100">
                        <h5 class="mb-3">Cara Membuat Template Pesan untuk Blast WhatsApp</h5>
                        <ol class="list-group list-group-flush">
                            <li class="list-group-item">
                                Format untuk pembuatan template pesan.
                            </li>
                            <li class="list-group-item">
                                Untuk membuat pesan yang dinamis seperti:
                                <code>tagihan anda sisa Rp 700.000</code>, Anda perlu membungkus data tersebut dengan
                                <code>{}</code>.
                                Contoh: <code>tagihan anda sisa Rp {tagihan}</code>. Variable <code>{tagihan}</code>
                                akan di gantikan dengan data yang akan dikirim
                            </li>
                        </ol>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        const page = usePage();
        const template_customer = page.props.template_customer as any;
        const template_sales = page.props.template_sales as any;

        const templateForm = useForm({
            name: "SALES",
            message: template_sales.message,
        });

        const templateFormCustomer = useForm({
            name: "CUSTOMER",
            message: template_customer.message,
        });

        function handleSubmitTemplate(template_id: number) {
            templateForm.patch(route('aging-finance.store-template', template_id), {
                onSuccess: (page) => {
                    Swal.fire(page.props.flash.success, '', 'success');
                },
                onError: () => {
                    Swal.fire('Gagal menyimpan!', '', 'error');
                }
            })
        }

        function handleSubmitTemplateCustomer(template_id: number) {
            templateFormCustomer.patch(route('aging-finance.store-template', template_id), {
                onSuccess: (page) => {
                    Swal.fire(page.props.flash.success, '', 'success');
                },
                onError: () => {
                    Swal.fire('Gagal menyimpan!', '', 'error');
                }
            });
        }

        return {
            handleSubmitTemplate,
            handleSubmitTemplateCustomer,
            template_customer,
            template_sales,
            templateFormCustomer,
            templateForm
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>