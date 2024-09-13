<template>
    <n-notification-provider>
        <div class="d-flex flex-column vh-100">
            <div class="d-flex flex-row flex-grow-1">
                <!-- Kolom kiri untuk konten lain -->
                <div class="d-flex flex-column w-100 ">
                    <slot />
                </div>

                <!-- Kolom kanan untuk gambar (disembunyikan di mobile) -->
                <div class="d-none d-md-flex flex-column w-75">
                    <div class="h-100 d-flex flex-row justify-content-end align-items-center"
                        style="background-color: #EEF8F5;">
                        <n-image width="900" src="/images/finance_dashboard.png"
                            preview-disabled class="img-fluid " style="padding-left: 50px;" />
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <Footer />
        </div>
    </n-notification-provider>
</template>




<script lang="ts">
import { defineComponent } from 'vue'
import Footer from '../Components/Footer.vue';

export default defineComponent({
    name: "GuestLayout",
    components: {
        Footer
    }
})
</script>