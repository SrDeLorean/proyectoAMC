<script setup>
import AuthenticatedLayout from '@/Layouts/AdminLayout.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
})
</script>

<template>
  <Head title="Perfil" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold leading-tight text-white">
        Perfil de Usuario
      </h2>
    </template>

    <main class="py-12 min-h-screen space-y-10">
      <!-- Información del perfil -->
      <div
        class="w-full max-w-full mx-auto px-4 sm:px-6 md:px-8 py-8
               bg-gray-800 rounded-xl shadow text-white
               border border-red-600"
      >
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
          class="max-w-3xl mx-auto"
        />
      </div>

      <!-- Cambiar contraseña -->
      <div
        class="w-full max-w-full mx-auto px-4 sm:px-6 md:px-8 py-8
               bg-gray-800 rounded-xl shadow text-white
               border border-red-600"
      >
        <UpdatePasswordForm class="max-w-3xl mx-auto" />
      </div>

      <!-- Eliminar cuenta -->
      <div
        class="w-full max-w-full mx-auto px-4 sm:px-6 md:px-8 py-8
               bg-gray-800 rounded-xl shadow text-white
               border border-red-600"
      >
        <DeleteUserForm class="max-w-3xl mx-auto" />
      </div>
    </main>
  </AuthenticatedLayout>
</template>
