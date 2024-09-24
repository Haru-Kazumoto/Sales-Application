<template>
    <footer class="d-flex justify-content-center align-items-center text-center footer-container">
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
    font-size: 1rem;
    /* Ukuran font default */
}

.footer-container {
    background-color: #00a54f;
    height: 2rem;
}

/* Responsif untuk layar kecil */
@media (max-width: 768px) {
    .footer-text {
        font-size: 0.5rem;
        /* Ukuran font lebih kecil di layar mobile */
    }

    .footer-container {
        margin-top: 1rem;
    }
}
</style>