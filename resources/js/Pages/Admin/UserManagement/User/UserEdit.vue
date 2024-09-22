<template>
    <div class="d-flex flex-column gap-4">
        <TitlePage title="Edit User" />
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
                            <label for="Fullname">Nama Panjang</label>
                            <n-input id="Fullname" placeholder="" v-model:value="model.fullname" />
                            <ErrorInput :error="$page.props.errors['fullname']" />
                        </div>
                        <div>
                            <label for="Username">Username</label>
                            <n-input id="Username" placeholder="" v-model:value="model.username" />
                            <ErrorInput :error="$page.props.errors['username']" />
                        </div>
                        <div>
                            <label for="Email">Email</label>
                            <n-input id="Email" placeholder="" v-model:value="model.email" />
                            <ErrorInput :error="$page.props.errors['email']" />
                        </div>
                        <div>
                            <label for="Role">Role</label>
                            <n-select id="Role" placeholder="" :options="roles" v-model:value="model.role_id" />
                            <ErrorInput :error="$page.props.errors['role_id']" />
                        </div>
                        <div>
                            <label for="Division">Divisi</label>
                            <n-select id="Division" placeholder="" :options="divisions"
                                v-model:value="model.division_id" />
                            <ErrorInput :error="$page.props.errors['division_id']" />
                        </div>
                        <div class="card" style="border-color: red;">
                            <div class="card-body d-flex flex-column gap-3">
                                <div>
                                    <label for="New Password">Password baru</label>
                                    <n-input id="New Password" placeholder="" v-model:value="model.password" type="password" show-password-on="click"/>
                                    <ErrorInput :error="$page.props.errors['password']" />
                                </div>
                                <div>
                                    <label for="New Password">Konfirmasi Password</label>
                                    <n-input id="New Password" placeholder="" v-model:value="model.password_confirmation" type="password" show-password-on="click"/>
                                    <ErrorInput :error="$page.props.errors['password_confirmation']" />
                                </div>
                            </div>
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
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ArrowBack } from '@vicons/ionicons5';
import { Division, Role, User } from '../../../../types/model';
import { UserUpdateDTO } from '../../../../types/dto';
import { useNotification } from 'naive-ui';
import ErrorInput from '../../../../Components/ErrorInput.vue';

export default defineComponent({
    setup() {
        const page = usePage();
        const notification = useNotification();

        const userSelected = page.props.user as User;

        const form = useForm<UserUpdateDTO>({
            username: userSelected.username,
            fullname: userSelected.fullname,
            password: '',
            password_confirmation: '',
            email: userSelected.email,
            role_id: userSelected.role.id,
            division_id: userSelected.division.id,
        });

        function handleSubmit() {
            form.patch(route('admin.edit-user.patch', userSelected.id), {
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