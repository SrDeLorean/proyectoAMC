<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: '',
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;
  nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;
  form.clearErrors();
  form.reset();
};
</script>

<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-semibold text-white">Eliminar Cuenta</h2>
      <p class="mt-1 text-sm text-gray-400">
        Una vez eliminada tu cuenta, todos tus datos serán borrados permanentemente. Asegúrate de descargar cualquier información que desees conservar antes de continuar.
      </p>
    </header>

    <div>
      <DangerButton
        @click="confirmUserDeletion"
        class="bg-red-600 hover:bg-red-700 text-white"
      >
        Eliminar Cuenta
      </DangerButton>
    </div>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="p-6 bg-gray-900 rounded-xl text-white max-w-md mx-auto space-y-6">
        <header class="space-y-2">
          <h2 class="text-lg font-semibold">¿Estás seguro de que deseas eliminar tu cuenta?</h2>
          <p class="text-sm text-gray-400">
            Esta acción no se puede deshacer. Ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
          </p>
        </header>

        <div>
          <InputLabel for="password" value="Contraseña" class="sr-only" />
          <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-400"
            placeholder="Contraseña"
            @keyup.enter="deleteUser"
          />
          <InputError :message="form.errors.password" class="mt-2 text-red-500" />
        </div>

        <div class="flex justify-end gap-3">
          <SecondaryButton
            @click="closeModal"
            class="bg-gray-700 hover:bg-gray-600 text-white"
          >
            Cancelar
          </SecondaryButton>

          <DangerButton
            class="bg-red-600 hover:bg-red-700 text-white"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteUser"
          >
            Confirmar Eliminación
          </DangerButton>
        </div>
      </div>
    </Modal>
  </section>
</template>
