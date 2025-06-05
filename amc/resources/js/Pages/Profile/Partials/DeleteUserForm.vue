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
  <section
    class="bg-gray-900 rounded-xl shadow-lg px-6 sm:px-10 py-10 flex flex-col md:flex-row gap-10 border border-gray-800 w-full max-w-5xl mx-auto"
  >
    <header class="flex-1">
      <h2 class="text-lg font-semibold text-white">Eliminar Cuenta</h2>
      <p class="mt-1 text-sm text-gray-400">
        Una vez eliminada tu cuenta, todos tus datos serán borrados permanentemente. Asegúrate de descargar cualquier información que desees conservar antes de continuar.
      </p>
    </header>

    <div class="flex items-start md:items-center">
      <DangerButton
        @click="confirmUserDeletion"
        class="bg-red-600 hover:bg-red-700 text-white whitespace-nowrap"
      >
        Eliminar Cuenta
      </DangerButton>
    </div>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="p-6 bg-gray-900 rounded-xl text-white max-w-md mx-auto">
        <h2 class="text-lg font-semibold">
          ¿Estás seguro de que deseas eliminar tu cuenta?
        </h2>

        <p class="mt-1 text-sm text-gray-400">
          Una vez eliminada, todos tus datos serán borrados permanentemente. Ingresa tu contraseña para confirmar esta acción.
        </p>

        <div class="mt-6">
          <InputLabel for="password" value="Contraseña" class="text-white sr-only" />
          <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full bg-gray-800 border border-gray-700 text-white"
            placeholder="Contraseña"
            @keyup.enter="deleteUser"
          />
          <InputError :message="form.errors.password" class="mt-2 text-red-500" />
        </div>

        <div class="mt-6 flex justify-end">
          <SecondaryButton
            @click="closeModal"
            class="bg-gray-700 text-white hover:bg-gray-600"
          >
            Cancelar
          </SecondaryButton>

          <DangerButton
            class="ml-3 bg-red-600 hover:bg-red-700 text-white"
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
