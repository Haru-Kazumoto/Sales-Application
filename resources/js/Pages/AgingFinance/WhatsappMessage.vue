<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="SETTING PESAN WA CUSTOMER" />
        <div class="d-flex flex-column w-50">
            <n-tabs type="segment" animated >
                <n-tab-pane name="Customer" tab="Customer">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Isi Pesan Untuk Customer</label>
                            <n-input type="textarea" />
                        </div>
    
                        <n-button class="ms-auto" type="primary">Simpan pesan</n-button>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="Salesman" tab="Salesman">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex flex-column gap-1">
                            <label for="">Isi Pesan Untuk Salesman</label>
                            <n-input type="textarea" />
                        </div>
    
                        <n-button class="ms-auto" type="primary">Simpan pesan</n-button>
                    </div>
                </n-tab-pane>
            </n-tabs>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';

export default defineComponent({
    setup () {
        

        return {}
    },
    components: {
        TitlePage
    }
})
</script>

<style scoped>

</style>