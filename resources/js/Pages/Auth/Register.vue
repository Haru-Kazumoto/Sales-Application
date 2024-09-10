<template>

    <Head title="Signup" />
    <div class="card shadow" style="border: none; width: 40rem;">
        <div class="card-body d-flex flex-column p-4 gap-3">
            <div class="d-flex flex-row">
                <img src="/images/Logo DNP.png" alt="" class="img-fluid" style="height: 70px;" />
            </div>
            <form @submit.prevent="handleSubmit">
                <div class="d-flex flex-column gap-3">
                    <span class="fs-3 align-items-center">Register data</span>
                    <div class="d-flex flex-column gap-1" id="input-1">
                        <label for="fullname">Fullname</label>
                        <n-input placeholder="Jhon Doe" id="fullname" v-model:value="model.fullname"
                            @keydown.enter.prevent />
                        <ErrorInput :error="$page.props.errors['fullname']" />
                    </div>
                    <div class="d-flex flex-column gap-1" id="input-2">
                        <label for="username">Username</label>
                        <n-input placeholder="jhondoe" :allow-input="noSideSpace" id="username"
                            v-model:value="model.username" @keydown.enter.prevent />
                        <ErrorInput :error="$page.props.errors['username']" />
                    </div>
                    <div class="d-flex flex-column gap-1" id="input-3">
                        <label for="email">Email</label>
                        <n-input placeholder="jhondoe@gmail.com" id="email" v-model:value="model.email"
                            @keydown.enter.prevent />
                        <ErrorInput :error="$page.props.errors['email']" />
                    </div>
                    <div class="d-flex flex-column gap-1" id="input-4">
                        <label for="password">Password</label>
                        <n-input type="password" show-password-on="mousedown" id="password"
                            v-model:value="model.password" @keydown.enter.prevent />
                        <ErrorInput :error="$page.props.errors['password']" />
                    </div>
                    <div class="d-flex flex-column gap-1" id="input-5">
                        <label for="password">Role</label>
                        <n-select v-model:value="model.role_id" @keydown.enter.prevent :options="roleOptions"
                            label="Select Role" placeholder="Choose a role" />
                        <ErrorInput :error="$page.props.errors['password']" />
                    </div>
                </div>
                <n-button type="primary" class="my-4" attr-type="submit">Register now</n-button>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import ErrorInput from '../../Components/ErrorInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { defineComponent, h } from 'vue'
import { NIcon } from 'naive-ui'
import { PersonOutline, LockClosedOutline, LogInOutline } from "@vicons/ionicons5"
import { RequestRegisterDto } from '../../types/dto';
import Swal from 'sweetalert2';
import RegisterLayout from '../../Layouts/RegisterLayout.vue';

export default defineComponent({
    setup() {
        const page = usePage();
        const form = useForm<RequestRegisterDto>({
            fullname: '',
            username: '',
            email: '',
            password: '',
            role_id: null,
        });

        function handleSubmit() {
            //create post login logic here...
            form.post(route('register.post'), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Register Success',
                    })
                },
                onFinish: () => {
                    form.reset();
                }
            });
        }

        const roleOptions = page.props.roles.map(role => ({
            label: role.role_name,
            value: role.id
        }));

        return {
            model: form,
            roleOptions,
            renderIcon() {
                return h(NIcon, null, {
                    default: () => h(LogInOutline)
                })
            },
            noSideSpace: (value: string) => !/ /g.test(value),
            handleSubmit,
            PersonOutline,
            LockClosedOutline,
            LogInOutline
        }
    },
    components: {
        ErrorInput,
        Head,
        Link,
    },
    layout: RegisterLayout
})
</script>