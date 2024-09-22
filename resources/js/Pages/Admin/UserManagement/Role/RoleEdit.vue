<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Update role" />
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
                            <label for="Rolename">Nama Role</label>
                            <n-input id="Rolename" placeholder="" v-model:value="model.role_name" />
                            <ErrorInput :error="$page.props.errors['role_name']" />
                        </div>
                        <n-button type="primary" attr-type="submit" class="w-25 my-3">Update role</n-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import TitlePage from '../../../../Components/TitlePage.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import { Division, Flash, Role } from '../../../../types/model';
import { RoleUpdateDTO, UserCreateDTO } from '../../../../types/dto';
import { useNotification } from 'naive-ui';
import ErrorInput from '../../../../Components/ErrorInput.vue';

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();

        const roleSelected = (page.props.role as Role);

        const form = useForm<RoleUpdateDTO>({
            role_name: roleSelected.role_name,
        });

        function handleSubmit() {
            form.patch(route('admin.edit-role.patch', roleSelected.id), {
                onSuccess: () => {
                    notification.success({
                        title: "Update role berhasil",
                        duration: 1500
                    });
                },
                onError: () => {
                    notification.error({
                        title: "There is an error, please check log immediately",
                        duration: 1500,
                    });
                },
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