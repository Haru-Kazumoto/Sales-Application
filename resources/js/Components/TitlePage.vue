<template>
    <span class="fs-3 fw-medium">{{title}}</span>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: "TitlePage",
    props: {
        title: {
            type: String,
            required: true,
        }
    }
})
</script>

<style scoped>

</style>