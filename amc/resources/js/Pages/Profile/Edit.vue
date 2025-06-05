<script setup>
import AuthenticatedLayout from '@/Layouts/AdminLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Perfil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-white">
                Perfil de Usuario
            </h2>
        </template>

        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

                <!-- Información del perfil -->
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-3xl"
                />

                <!-- Cambiar contraseña -->
                <UpdatePasswordForm class="max-w-3xl" />

                <!-- Eliminar cuenta -->
                <DeleteUserForm class="max-w-3xl" />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
