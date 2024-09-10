<template>
    <div>
        <n-button @click="handleLogout" type="primary" attr-type="submit">Logout</n-button>
        <!-- <span>{{ $page.props.auth.user.role.role_name }}</span> -->
    </div>
</template>

<script lang="ts">
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { defineComponent } from 'vue'

export default defineComponent({
    setup () {
        const form = useForm({});

        function handleLogout(){
            form.post(route('logout'), {
                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Logged out',
                    });
                }
            })
        }

        return {
            handleLogout,
        }
    }
})
</script>