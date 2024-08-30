<template>
    <div class="text-red fw-medium">
        <span>{{ error }}</span>
    </div>
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