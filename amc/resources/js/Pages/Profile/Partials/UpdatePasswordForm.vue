<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <section class="space-y-6">
    <header class="space-y-1">
      <h2 class="text-2xl font-semibold text-white">Actualizar Contraseña</h2>
      <p class="text-sm text-gray-400">
        Asegúrate de usar una contraseña segura y aleatoria.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="space-y-6">
      <!-- Contraseña actual -->
      <div>
        <InputLabel for="current_password" value="Contraseña actual" class="text-white" />
        <TextInput
          id="current_password"
          ref="currentPasswordInput"
          v-model="form.current_password"
          type="password"
          class="mt-1 block w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-400"
          autocomplete="current-password"
        />
        <InputError :message="form.errors.current_password" class="mt-2 text-red-500" />
      </div>

      <!-- Nueva contraseña -->
      <div>
        <InputLabel for="password" value="Nueva contraseña" class="text-white" />
        <TextInput
          id="password"
          ref="passwordInput"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-400"
          autocomplete="new-password"
        />
        <InputError :message="form.errors.password" class="mt-2 text-red-500" />
      </div>

      <!-- Confirmar contraseña -->
      <div>
        <InputLabel for="password_confirmation" value="Confirmar contraseña" class="text-white" />
        <TextInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-400"
          autocomplete="new-password"
        />
        <InputError :message="form.errors.password_confirmation" class="mt-2 text-red-500" />
      </div>

      <!-- Botón guardar -->
      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing" class="bg-red-600 hover:bg-red-700 text-white">
          Guardar
        </PrimaryButton>

        <Transition
          enter-active-class="transition-opacity duration-300"
          enter-from-class="opacity-0"
          leave-active-class="transition-opacity duration-300"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-green-500">
            Contraseña actualizada correctamente.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
