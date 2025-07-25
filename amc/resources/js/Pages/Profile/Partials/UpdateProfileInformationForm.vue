<script setup>
import { ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const DEFAULT_IMAGE = '/images/users/default-user.png';

const getUserImageUrl = (foto) => {
  return foto && foto.trim() !== ''
    ? `/${foto}`
    : DEFAULT_IMAGE;
};

const selectedImage = ref(null);
const imagePreview = ref(getUserImageUrl(user.foto));

const form = useForm({
    name: user.name,
    email: user.email,
    id_ea: user.id_ea || '',
    foto: null,  // Aquí siempre null porque será un File o null
});

watch(selectedImage, (file) => {
    if (!file) {
        imagePreview.value = getUserImageUrl(user.foto);
        form.foto = null;
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);

    form.foto = file;
});

function onFileChange(event) {
    selectedImage.value = event.target.files[0];
}

function onImageError(event) {
    event.target.src = DEFAULT_IMAGE;
}

function submit() {
    form.put(route('profile.update'), {
        forceFormData: true
    });
}
</script>


<template>
  <section class="flex flex-col md:flex-row gap-10 items-start text-white">
    <!-- Imagen de perfil -->
    <div class="flex flex-col items-center md:items-start w-full md:w-1/3 text-center md:text-left">
      <img
        :src="imagePreview"
        :alt="`Foto de perfil de ${user.name}`"
        class="w-36 h-36 rounded-full object-cover border-4 border-gray-700 mb-4"
        @error="onImageError"
      />
      <label for="foto" class="text-sm text-gray-300 mb-2 font-medium cursor-pointer">
        Cambiar imagen
      </label>
      <input
        id="foto"
        type="file"
        accept="image/*"
        @change="onFileChange"
        class="file:bg-red-600 file:hover:bg-red-700 file:border-0 file:text-white file:rounded file:px-4 file:py-1
               text-sm text-gray-300 bg-gray-800 border border-gray-700 rounded-md w-full cursor-pointer"
      />
      <InputError class="mt-2 text-red-500" :message="form.errors.foto" />
    </div>

    <!-- Formulario -->
    <div class="w-full md:w-2/3 space-y-6">
      <header>
        <h2 class="text-3xl font-bold text-white">Tu Perfil</h2>
        <p class="text-base text-gray-400 mt-1">
          Edita tu nombre, correo, ID EA y foto de perfil.
        </p>
      </header>

      <!-- Campo: Nombre -->
      <div>
        <InputLabel for="name" value="Nombre" class="text-white" />
        <TextInput
          id="name"
          type="text"
          class="mt-1 w-full bg-gray-800 border border-gray-700 text-white text-base focus:border-red-500 focus:ring-red-500"
          v-model="form.name"
          required
          autocomplete="name"
        />
        <InputError class="mt-2 text-red-500" :message="form.errors.name" />
      </div>

      <!-- Campo: Email -->
      <div>
        <InputLabel for="email" value="Correo electrónico" class="text-white" />
        <TextInput
          id="email"
          type="email"
          class="mt-1 w-full bg-gray-800 border border-gray-700 text-white text-base focus:border-red-500 focus:ring-red-500"
          v-model="form.email"
          required
          autocomplete="username"
        />
        <InputError class="mt-2 text-red-500" :message="form.errors.email" />
      </div>

      <!-- Campo: ID EA -->
      <div>
        <InputLabel for="id_ea" value="ID EA" class="text-white" />
        <TextInput
          id="id_ea"
          type="text"
          class="mt-1 w-full bg-gray-800 border border-gray-700 text-white text-base focus:border-red-500 focus:ring-red-500"
          v-model="form.id_ea"
        />
        <InputError class="mt-2 text-red-500" :message="form.errors.id_ea" />
      </div>

      <!-- Verificación de correo -->
      <div
        v-if="mustVerifyEmail && user.email_verified_at === null"
        class="bg-gray-800 p-4 rounded-lg border border-gray-700"
      >
        <p class="text-sm text-gray-300">
          Tu correo no está verificado.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="text-red-500 underline ml-1 hover:text-red-400"
          >
            Reenviar verificación.
          </Link>
        </p>
        <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm text-green-500">
          Enlace enviado a tu correo.
        </div>
      </div>

      <!-- Botón guardar -->
      <div class="flex items-center gap-4">
        <PrimaryButton
          :disabled="form.processing"
          class="bg-red-600 hover:bg-red-700 text-white text-base px-6 py-2"
          @click="submit"
        >
          Guardar cambios
        </PrimaryButton>

        <Transition
          enter-active-class="transition-opacity duration-300"
          enter-from-class="opacity-0"
          leave-active-class="transition-opacity duration-300"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-green-500">
            Cambios guardados correctamente.
          </p>
        </Transition>
      </div>
    </div>
  </section>
</template>
