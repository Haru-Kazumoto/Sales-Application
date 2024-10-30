<template>
    <div>
        <!-- Button trigger for modal -->
        <n-button @click="showModal = true" type="primary">Launch Modal</n-button>

        <!-- Modal -->
        <transition name="modal">
            <div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ title }}</h5>
                            <n-button type="error" text @click="closeModal">Close</n-button>
                        </div>
                        <div class="modal-body">
                            <slot name="body">Default Modal Content</slot>
                        </div>
                        <div class="modal-footer">
                            <n-button @click="closeModal" type="default">Close</n-button>
                            <n-button @click="understood" type="primary">Understood</n-button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { NButton } from 'naive-ui'

const showModal = ref(false)

const closeModal = () => {
    showModal.value = false
}

const understood = () => {
    alert("Understood!")
    closeModal()
}

const props = defineProps({
    title: {
        type: String,
        default: "Modal Title"
    }
})
</script>

<style scoped>
.modal {
    background: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    transition: all 0.3s;
}
</style>