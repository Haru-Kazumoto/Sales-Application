<template>
    <n-notification-provider>
        <div class="d-flex flex-column vh-100 w-100 justify-content-center align-items-center">
            <slot />
        </div>
    </n-notification-provider>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: "RegisterLayout",
})
</script>
