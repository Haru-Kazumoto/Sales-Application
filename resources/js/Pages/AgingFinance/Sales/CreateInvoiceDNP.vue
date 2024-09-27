<template>
    <div class="d-flex flex-column gap-4">
        <div class="d-flex flex-column gap-3">
            <TitlePage title="Buat Invoice DNP" />
            <n-button text class="justify-content-start w-25 " size="large"
                @click="router.visit(route('aging-finance.invoice-dnp'), { method: 'get' })">
                <n-icon :component="ArrowBack" style="margin-right: 5px;" />
                Kembali
            </n-button>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2">
                    <!-- row 1 -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>

                    <!-- row 2 -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>

                    <!-- row 3 -->
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-1">
                        <label for="">Input label</label>
                        <n-input />
                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow-sm border-0">
            <div class="card-body">
                <n-data-table :bordered="false" />
            </div>
        </div>

        <div class="card shadow-sm border-0 ">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between py-2">
                    <span>Sub Total</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>PPN 11%</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="d-flex justify-content-between py-2 fw-bold border-top border-bottom">
                    <span>Total Harga</span>
                    <span>Rp 200.000</span>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-lg-8 d-flex flex-column text-body-tertiary">
                        <span>Harap ditransfer ke : </span>
                        <span>PT. DANITAMA NIAGAPRIMA</span>
                        <span>NPWP: 01.345.766.7-064.000</span>
                        <span>BANK CENTRAL ASIA CAB HASANUDIN JAKARTA A/C NO. 523.0300.200</span>
                        <span>BANK CENTRAL ASIA CAB JATIASIH JAKARTA A/C NO. 675.5010.255</span>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex justify-content-between">
                            <span>Total Harga</span>
                            <span class="fw-bold">Rp 200.000</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Jatuh Tempo</span>
                            <span class="fw-bold">12 September 2024</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <n-button type="primary" size="medium" class="ms-auto mb-5 ">Buat Surat Invoice</n-button>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../../Components/TitlePage.vue';
import { router } from '@inertiajs/vue3';
import {ArrowBack} from "@vicons/ionicons5";

export default defineComponent({
    setup() {


        return {
            router,
            ArrowBack
        }
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped></style>