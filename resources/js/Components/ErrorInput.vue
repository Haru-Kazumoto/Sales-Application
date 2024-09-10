<template>
    <span style="color: red;">{{ error }}</span>
</template>

<script lang="ts">
import {defineComponent} from 'vue';

export default defineComponent({
    name: "ErrorInput",
    props: {
        error: {
            type: String,
            required: true
        }
    }
});
</script>