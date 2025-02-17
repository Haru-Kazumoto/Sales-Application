<template>
    <div class="d-flex flex-column w-100 my-2 py-1 px-3 rounded " style="background-color: #00a54fFF;">
        <span class="fs-3 fw-semibold text-white">{{title}}</span>
        <span class="fw-medium text-white">{{ subTitle }}</span>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: "TitlePage",
    props: {
        title: {
            type: String,
            required: true,
        },
        subTitle: {
            type: String,
            required: false,
        }
    }
})
</script>

<style scoped>

</style>