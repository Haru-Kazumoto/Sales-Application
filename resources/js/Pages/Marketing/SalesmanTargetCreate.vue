<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="SETTING TARGET SALES" />

        <div class="d-flex align-items-center justify-content-center">
            <div class="card shadow-sm border-0 w-50 mb-3">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center w-100">
                        <span class="fs-5">Setting Target</span>
                        <div class="d-flex align-items-center ms-auto gap-2">
                            <span >Periode</span>
                            <n-select style="flex-grow: 1; min-width: 150px;" placeholder=""/>
                        </div>
                    </div>

                    <n-divider>Informasi Salesman</n-divider>

                    <div class="row g-2">
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Salesman</label>
                            <n-input placeholder="" size="large"/>
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Salesman UID</label>
                            <n-input placeholder="" size="large"/>
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Divisi</label>
                            <n-input placeholder="" size="large"/>
                        </div>
                        
                        <n-divider>Informasi Target</n-divider>

                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Target Bulanan</label>
                            <n-input placeholder="" size="large"/>
                        </div>
                        <div class="col-12 d-flex flex-column gap-1">
                            <label for="">Target Tahunan</label>
                            <n-input placeholder="" size="large"/>
                        </div>
                    </div>

                    <n-divider></n-divider>

                    <div class="d-flex">
                        <div class="d-flex gap-2 ms-auto">
                            <n-button type="primary">Simpan Informasi</n-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import TitlePage from '../../Components/TitlePage.vue';

export default defineComponent({
    setup() {


        return {}
    },
    components: {
        TitlePage,
        
    }
})
</script>

<style scoped></style>