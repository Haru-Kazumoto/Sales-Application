<template>
    <footer class="d-flex justify-content-center align-items-center py-2 text-center"
        style="background-color: #00a54f;">
        <span class="fw-medium text-white footer-text">Copyright PT DANITAMA NIAGAPRIMA - PT DUTA KOMODITI UTAMA</span>
    </footer>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: "Footer"
});
</script>

<style scoped>
.footer-text {
    font-size: 1.2rem;
    /* Ukuran font default */
}

/* Responsif untuk layar kecil */
@media (max-width: 768px) {
    .footer-text {
        font-size: 0.8rem;
        /* Ukuran font lebih kecil di layar mobile */
    }
}
</style>