<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Create new user" />
        <Link :href="route('admin.user-management')">
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
                            <label for="Fullname">Fullname</label>
                            <n-input id="Fullname" placeholder="" v-model:value="model.fullname" />
                            <ErrorInput :error="$page.props.errors['fullname']" />
                        </div>
                        <div>
                            <label for="Username">Username</label>
                            <n-input id="Username" placeholder="" v-model:value="model.username" />
                            <ErrorInput :error="$page.props.errors['username']" />
                        </div>
                        <div>
                            <label for="Username">Nomor Telepon</label>
                            <n-input id="Username" placeholder="" v-model:value="model.phone" />
                            <ErrorInput :error="$page.props.errors['phone']" />
                        </div>
                        <div>
                            <label for="Email">Email</label>
                            <n-input id="Email" placeholder="" v-model:value="model.email" />
                            <ErrorInput :error="$page.props.errors['email']" />
                        </div>
                        <div>
                            <label for="Password">Password</label>
                            <n-input id="Password" placeholder="" v-model:value="model.password" />
                            <ErrorInput :error="$page.props.errors['password']" />
                        </div>
                        <div>
                            <label for="Role">Role</label>
                            <n-select id="Role" placeholder="" :options="roles" v-model:value="model.role_id" />
                            <ErrorInput :error="$page.props.errors['role_id']" />
                        </div>
                        <div>
                            <label for="Division">Division</label>
                            <n-select id="Division" placeholder="" :options="divisions"
                                v-model:value="model.division_id" />
                            <ErrorInput :error="$page.props.errors['division_id']" />
                        </div>
                        <n-button type="primary" attr-type="submit" class="w-25 my-3">Buat user baru</n-button>
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
import { Division, Flash, Role } from '../../../../types/model';
import { UserCreateDTO } from '../../../../types/dto';
import { useNotification } from 'naive-ui';
import ErrorInput from '../../../../Components/ErrorInput.vue';

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();
        const form = useForm({
            username: '',
            fullname: '',
            email: '',
            password: '',
            phone: '',
            role_id: null as any,
            division_id: null as any,
        });

        function handleSubmit() {
            form.post(route('admin.create-user.post'), {
                onSuccess: () => {
                    console.log(page.props);
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
                    console.log(page.props.errors);
                },
                onFinish: () => {
                    form.reset();
                }
            });
        }

        //options
        const roles = (page.props.roles as Role[]).map((data) => ({
            label: data.role_name,
            value: data.id
        }));

        const divisions = (page.props.divisions as Division[]).map((data) => ({
            label: data.division_name,
            value: data.id
        }));

        return {
            roles,
            divisions,
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