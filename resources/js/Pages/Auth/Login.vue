<template>

    <Head title="Signup" />
    <div class="card h-100 rounded-end w-100" style="border: none">
        <div class="card-body d-flex flex-column gap-5 justify-content-center"
            style="box-shadow: 10px 0px 14px -8px rgba(0,165,70,0.50); padding: 0 3rem;">
            <div class="fs-1 fw-semibold d-flex flex-column" style="color: #00a54f;">
                <span>Sales & Analytics</span>
                <span>Dashboard</span>
            </div>

            <form @submit.prevent="handleSubmit" class="d-flex flex-column gap-4">
                <span class="fs-4 fw-medium">Login to your account</span>
                <div v-if="flash.info" class="alert alert-danger">
                    {{ flash.info }}
                </div>
                <div class="d-flex flex-column gap-4">
                    <!-- USERNAME INPUT -->
                    <div class="d-flex flex-column gap-1">
                        <label for="username">Username</label>
                        <n-input v-model:value="model.username" placeholder="Username" size="large">
                            <template #prefix>
                                <n-icon :component="PersonOutline" />
                            </template>
                        </n-input>
                        <ErrorInput :error="$page.props.errors['username']" />
                    </div>

                    <!-- PASSWORD INPUT -->
                    <div class="d-flex flex-column gap-1">
                        <label for="password">Password</label>
                        <n-input type="password" show-password-on="click" v-model:value="model.password" size="large"
                            placeholder="Password">
                            <template #prefix>
                                <n-icon :component="LockClosedOutline" />
                            </template>
                        </n-input>
                        <ErrorInput :error="$page.props.errors['password']" />
                    </div>

                </div>
                <n-button attr-type="submit" type="primary" class="w-100" :render-icon="renderIcon" size="large">
                    Login
                </n-button>
            </form>

            <div class="d-flex flex-row flex-nowrap align-items-center justify-content-center">
                <n-image-group>
                    <n-space>
                        <n-image :width="isMobile ? 100 : 150" src="/images/Frame 57.png" preview-disabled />
                        <n-image :width="isMobile ? 100 : 150" src="/images/Frame 58.png" preview-disabled />
                    </n-space>
                </n-image-group>
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import GuestLayout from '../../Layouts/GuestLayout.vue';
import ErrorInput from '../../Components/ErrorInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { defineComponent, h, ref, onMounted, computed } from 'vue'
import { NIcon, useNotification } from 'naive-ui'
import { PersonOutline, LockClosedOutline, LogInOutline } from "@vicons/ionicons5"
import { RequestLoginDto } from '../../types/dto';
import Swal from 'sweetalert2';

export default defineComponent({
    setup() {
        const form = useForm<RequestLoginDto>({
            username: '',
            password: '',
        });
        const isMobile = ref(false);

        function checkScreenSize() {
            isMobile.value = window.innerWidth <= 768;
        }

        const flash = computed(() => {
            return usePage().props.flash as any;
        });

        function handleSubmit() {
            form.post(route('login'), {
                preserveScroll: false,
                onFinish: () => {
                    form.reset();
                },
                onSuccess: (page) => {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 2500,
                        title: `Selamat datang, ${page.props.auth.user.fullname}!`,
                        icon: "success",
                    });
                }
            });
        }

        onMounted(() => {
            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
        });

        return {
            isMobile,
            model: form,
            renderIcon() {
                return h(NIcon, null, {
                    default: () => h(LogInOutline)
                })
            },
            handleSubmit,
            PersonOutline,
            LockClosedOutline,
            LogInOutline,
            flash
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
