<template>
    <div class="d-flex" style="background-color: #00a54f;">
        <div class="mx-4 d-flex flex-row align-items-center w-100">
            <n-image width="50" src="/images/company_logo.png" preview-disabled class="my-1" />
            <span class="mx-4 fs-5 text-white">PT DANITAMA NIAGAPRIMA</span>
            <n-dropdown :options="options" @select="handleSelectKey">
                <div class="d-flex flex-row ms-auto align-items-center border rounded p-2 text-white gap-2"
                    style="cursor: pointer;">
                    <n-icon :component="Person" />
                    <span>Haru Kazumoto</span>
                </div>
            </n-dropdown>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';
import type { Component } from 'vue';
import {
    Person,
    LogOutOutline as LogoutIcon,
    PersonCircleOutline as UserIcon
} from "@vicons/ionicons5";
import { NIcon } from 'naive-ui';
import Swal from 'sweetalert2';

function renderIcon(icon: Component) {
    return () => {
        return h(NIcon, null, {
            default: () => h(icon)
        })
    }
}

export default defineComponent({
    name: "Header",
    setup() {

        function handleSelectKey(key: string): void {
            if (key === 'logout') {
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure you want to logout?',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'You have been logged out.',
                        })
                    }
                })
            }
        }

        return {
            Person,
            handleSelectKey,
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
        }
    }
});
</script>