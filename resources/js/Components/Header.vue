<template>
    <div class="d-flex" style="background-color: #00a54f; height: 4rem;">
        <div class="mx-4 d-flex align-items-center w-100">
            <div class="d-flex align-items-center" style="gap: 13rem;">

                <div class="d-lg-none mx-lg-1 fs-3 text-white" style="cursor: pointer;" @click="toggleCollapse">
                    <n-icon :component="Hamburger" />
                </div>

                <!-- Logo and Company Name, hidden on small screens -->
                <div class="d-flex align-items-center">
                    <n-image width="50" src="/images/company_logo.png" preview-disabled
                        class="my-1 d-none d-md-block" />
                    <span class="mx-4 fs-5 text-white d-none d-md-block">
                        PT DANITAMA NIAGAPRIMA
                    </span>
                </div>
            </div>
            <!-- Hamburger icon -->

            <!-- User dropdown menu -->
            <n-dropdown :options="options" @select="handleSelectKey" trigger="click">
                <div class="d-flex flex-row ms-auto align-items-center  p-2 text-white gap-2" style="cursor: pointer;">
                    <n-icon :component="Person" />
                    <span class="d-none d-md-flex">{{ $page.props.auth.user.fullname }}</span>
                </div>
            </n-dropdown>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, inject, h } from 'vue';
import { Person, ReorderFourSharp as Hamburger, LogOutOutline as LogoutIcon, PersonCircleOutline as UserIcon } from "@vicons/ionicons5";
import { NIcon, useNotification } from 'naive-ui';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

function renderIcon(icon) {
    return () => h(NIcon, null, { default: () => h(icon) });
}

export default defineComponent({
    name: "Header",
    setup() {
        const form = useForm({});
        const notification = useNotification();
        const toggleCollapse = inject('toggleCollapse'); // Inject toggle function

        function handleSelectKey(key) {
            if (key === 'logout') {
                Swal.fire({
                    icon: 'question',
                    title: 'Keluar dari aplikasi?',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                }).then((result) => {
                    if (result.isConfirmed) {
                        handleLogout();
                    }
                });
            }
        }

        function handleLogout() {
            form.post(route('logout'), {
                onSuccess: () => {
                    notification.success({
                        title: "Sukses logout",
                    });
                },
                onError: (err) => {
                    Swal.fire({
                        icon: 'error',
                        title: err.message
                    });
                }
            });
        }

        return {
            Person,
            Hamburger,
            handleSelectKey,
            toggleCollapse,
            options: [
                {
                    label: 'Profile',
                    key: 'profile',
                    icon: renderIcon(UserIcon)
                },
                {
                    label: 'Logout',
                    key: 'logout',
                    icon: renderIcon(LogoutIcon)
                }
            ]
        };
    }
});
</script>
