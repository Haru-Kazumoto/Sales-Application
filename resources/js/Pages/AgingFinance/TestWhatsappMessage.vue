<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Kirim Pesan" />
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-lg-6 d-flex flex-column">
                                <label for="">Nomor Penerima</label>
                                <n-input placeholder="Awali dengan 62" v-model:value="form.recipient"
                                    :on-input="(value) => form.recipient = onlyNumber(value)">
                                </n-input>
                            </div>
                            <div class="col-12 col-lg-6 d-flex flex-column">
                                <!-- template isi message -->
                                <label for="">Template</label>
                                <n-select placeholder="" :options="templates" v-model:value="form.template_id" />
                            </div>
                            <n-divider dashed>Variable Message</n-divider>
                            <!-- placeholder (key value) -->
                            <n-dynamic-input v-model:value="value" preset="pair" />
                            <pre>{{ JSON.stringify(value, null, 2) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <n-divider dashed>Preview Message</n-divider>
                        <n-input type="textarea" placeholder="" v-model:value="form.content" />
                        <n-button type="primary" class="my-2" @click="handleSubmit">Kirim Pesan</n-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import TitlePage from '../../Components/TitlePage.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onlyNumber } from '../../Utils/options-input.utils';
import { useNotification } from 'naive-ui';

export default defineComponent({
    setup() {
        const notification = useNotification();
        const page = usePage();
        const form = useForm({
            recipient: '',
            template_id: null as unknown as any,  // Bind n-select to template_id
            content: '',  // Store the message content
            placeholders: [] as any[],
        });
        const value = ref([{ key: '', value: '' }]);

        // Store the entire template data (including message)
        const templates = page.props.templates.map((data) => ({
            label: data.name,
            value: data.id,
            message: data.message, // Store message here
        }));

        const resultMessage = ref(''); // To store the result message

        // Watch for changes in choosenTemplate
        watch(() => form.template_id, (newTemplateId) => {
            // Find the selected template based on the id
            const selectedTemplate = templates.find((template) => template.value === newTemplateId);

            if (selectedTemplate) {
                resultMessage.value = selectedTemplate.message; // Update result message with the template message

                form.content = selectedTemplate.message;
            } else {
                resultMessage.value = ''; // Reset if no template is selected
                form.content = '';
            }
        });

        function handleSubmit() {
            // Clear the form placeholders
            form.placeholders = [];

            // Push all key-value pairs from value into form.placeholders
            value.value.forEach((pair) => {
                if (pair.key && pair.value) {
                    form.placeholders.push({
                        key: pair.key,
                        value: pair.value
                    });
                }
            });

            form.post(route('aging-finance.save-message'), {
                onSuccess: () => {
                    form.reset();
                    notification.success({
                        title: page.props.flash.success,
                        duration: 1500,
                        closable: false,
                    });
                }
            })
        }

        return {
            value,
            handleSubmit,
            form,
            templates,
            onlyNumber,
            resultMessage, // Expose resultMessage to the template
        };
    },
    components: {
        TitlePage,
    },
});
</script>

<style scoped></style>
