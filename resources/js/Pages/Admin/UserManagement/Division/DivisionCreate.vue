<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Create new division" />
        <Link :href="route('admin.role-management')">
        <n-button text style="width: 20px;">
            <template #icon>
                <n-icon :component="ArrowBack" />
            </template>
            Kembali
        </n-button>
        </Link>
        <div class="d-flex container justify-content-center">
            <div class="card shadow w-50 justify-content-center" style="border: none;">
                <div class="card-body">
                    <form @submit.prevent="submit" class="d-flex flex-column gap-3">
                        <div>
                            <label for="Nama Role">Nama Divisi</label>
                            <n-input id="Nama Role" placeholder="" v-model:value="model.division_name" />
                            <ErrorInput :error="$page.props.errors['division_name']" />
                        </div>
                        <n-button type="primary" attr-type="submit" class="w-25 my-3">Buat divisi baru</n-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import TitlePage from '../../../../Components/TitlePage.vue';
import { InertiaForm, Link, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import { DivisionCreateDTO, RoleCreateDTO, } from '../../../../types/dto';
import { useNotification } from 'naive-ui';
import ErrorInput from '../../../../Components/ErrorInput.vue';

export default defineComponent({
    setup() {
        const notification = useNotification();
        const form = useForm<DivisionCreateDTO>({
            division_name: ''
        });

        function handleSubmit() {
            form.post(route('admin.create-division.post'), {
                onSuccess: () => {
                    notification.success({
                        title: "Success!",
                        duration: 1500
                    });
                },
                onError: () => {
                    notification.error({
                        title: "There is an error, please check log immediately",
                        duration: 1500,
                    });
                },
                onFinish: () => {
                    form.reset();
                }
            });
        }


        return {
            model: form,
            submit: handleSubmit,
            ArrowBack
        }
    },
    components: {
        TitlePage,
        Link,
        ErrorInput
    }
})
</script>

<style scoped></style>