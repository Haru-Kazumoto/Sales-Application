<template>

    <Head title="Signup" />
    <div class="card h-100 rounded-end" style="border: none">
        <div class="card-body px-5 d-flex flex-column gap-5"
            style="box-shadow: 10px 0px 14px -8px rgba(0,165,70,0.50);">
            <div class="fs-1 fw-semibold d-flex flex-column" style="color: #00a54f;">
                <span>Sales & Analytics</span>
                <span>Dashboard</span>
            </div>

            <form @submit.prevent="handleSubmit" class="d-flex flex-column gap-4">
                <span class="fs-4 fw-medium">Login to your account</span>
                <div class="d-flex flex-column gap-4">

                    <!-- USERNAME INPUT -->
                    <div class="d-flex flex-column gap-1">
                        <label for="username">Username</label>
                        <n-input v-model:value="model.username" placeholder="Input Username" size="large">
                            <template #prefix>
                                <n-icon :component="PersonOutline" />
                            </template>
                        </n-input>
                        <!-- <ErrorInput :error="$page.props.errors" /> -->
                    </div>

                    <!-- PASSWORD INPUT -->
                    <div class="d-flex flex-column gap-1">
                        <label for="password">Password</label>
                        <n-input type="password" show-password-on="mousedown" v-model:value="model.password"
                            placeholder="Input Password" size="large">
                            <template #prefix>
                                <n-icon :component="LockClosedOutline" />
                            </template>
                        </n-input>
                        <!-- <ErrorInput :error="$page.props.errors" /> -->
                    </div>

                    <n-checkbox size="large" label="Remember me" />
                </div>
                <n-button attr-type="submit" type="primary" class="w-100" :render-icon="renderIcon" size="large">
                    Login
                </n-button>
            </form>

            <div class="d-flex flex-row align-items-center justify-content-center">
                <n-image-group>
                    <n-space>
                        <n-image width="150" src="/images/Frame 57.png" preview-disabled/>
                        <n-image width="150" src="/images/Frame 58.png" preview-disabled/>
                        <n-image width="150" src="/images/Frame 59.png" preview-disabled/>
                    </n-space>
                </n-image-group>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import GuestLayout from '../../Layouts/GuestLayout.vue';
import ErrorInput from '../../Components/ErrorInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineComponent, h } from 'vue'
import { NIcon } from 'naive-ui'
import { PersonOutline, LockClosedOutline, LogInOutline } from "@vicons/ionicons5"
import { RequestLoginDto } from '../../types/dto';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        const form = useForm<RequestLoginDto>({
            username: '',
            password: '',
        });

        function handleSubmit() {
            //create post login logic here...
            // form.post(route('/login'));

            Swal.fire({
                icon: 'success',
                title: 'Success login',
                confirmButtonColor: '#00a54f',
            })
        }

        return {
            model: form,
            renderIcon() {
                return h(NIcon, null, {
                    default: () => h(LogInOutline)
                })
            },
            handleSubmit,
            PersonOutline,
            LockClosedOutline,
            LogInOutline
        }
    },
    components: {
        GuestLayout,
        ErrorInput,
        Head,
        Link,
    },
    layout: GuestLayout
})
</script>