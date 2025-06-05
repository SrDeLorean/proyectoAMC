<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  equipo: Object,
  usuarios: Array,
  formaciones: Array,
})

const form = useForm({
  nombre: props.equipo.nombre || '',
  descripcion: props.equipo.descripcion || '',
  color_primario: props.equipo.color_primario || '#000000',
  color_secundario: props.equipo.color_secundario || '#ffffff',
  instagram: props.equipo.instagram || '',
  twitch: props.equipo.twitch || '',
  youtube: props.equipo.youtube || '',
  id_formacion: props.equipo.id_formacion || '',
  id_usuario: props.equipo.id_usuario || '',
  logo: null,
})

const imagePreview = ref(
  props.equipo.logo ? `/${props.equipo.logo}` : '/logos/default.png'
)

function onFileChange(event) {
  const file = event.target.files[0]
  form.logo = file || null

  if (!file) {
    imagePreview.value = props.equipo.logo
      ? `/${props.equipo.logo}`
      : '/logos/default.png'
    return
  }

  const reader = new FileReader()
  reader.onload = e => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const successMessage = ref('')
const errorMessage = ref('')

const submit = () => {
  form.clearErrors()
  successMessage.value = ''
  errorMessage.value = ''

  form.post(`/equipos/${props.equipo.id}`, {
    method: 'post',
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Equipo actualizado correctamente.'
    },
    onError: (errors) => {
      errorMessage.value = 'Hubo un error al actualizar el equipo.'
      console.log('Errores backend:', errors)
    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Equipo</template>

    <div class="max-w-[900px] mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6 text-white">Editar Equipo</h1>

      <div v-if="successMessage" class="mb-4 p-3 bg-green-600 rounded text-white">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="mb-4 p-3 bg-red-600 rounded text-white">
        {{ errorMessage }}
      </div>

      <form
        @submit.prevent="submit"
        enctype="multipart/form-data"
        class="bg-gray-900 p-8 rounded-xl shadow-lg space-y-6"
      >
        <div class="flex items-center gap-6">
          <img
            :src="imagePreview"
            alt="Logo del equipo"
            class="w-24 h-24 rounded-full object-cover border-2 border-gray-600"
          />
          <div class="flex flex-col flex-grow">
            <label for="logo" class="text-gray-400 mb-2 font-semibold">Logo</label>
            <input
              id="logo"
              type="file"
              accept="image/*"
              @change="onFileChange"
              class="text-white bg-gray-800 rounded p-2"
            />
            <p v-if="form.errors.logo" class="text-red-500 text-sm mt-1">
              {{ form.errors.logo }}
            </p>
          </div>
        </div>

        <div>
          <label for="nombre" class="block text-sm text-gray-400 mb-1 font-semibold">Nombre</label>
          <input
            id="nombre"
            v-model="form.nombre"
            type="text"
            required
            class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
          />
          <p v-if="form.errors.nombre" class="text-red-500 text-sm mt-1">
            {{ form.errors.nombre }}
          </p>
        </div>

        <div>
          <label for="descripcion" class="block text-sm text-gray-400 mb-1 font-semibold">Descripción</label>
          <textarea
            id="descripcion"
            v-model="form.descripcion"
            rows="4"
            class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
          ></textarea>
          <p v-if="form.errors.descripcion" class="text-red-500 text-sm mt-1">
            {{ form.errors.descripcion }}
          </p>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label for="color_primario" class="block text-sm text-gray-400 mb-1 font-semibold">Color Primario</label>
            <input
              id="color_primario"
              v-model="form.color_primario"
              type="color"
              class="w-full h-12 p-1 rounded cursor-pointer border border-gray-700"
              title="Color Primario"
            />
          </div>

          <div>
            <label for="color_secundario" class="block text-sm text-gray-400 mb-1 font-semibold">Color Secundario</label>
            <input
              id="color_secundario"
              v-model="form.color_secundario"
              type="color"
              class="w-full h-12 p-1 rounded cursor-pointer border border-gray-700"
              title="Color Secundario"
            />
          </div>
        </div>

        <div>
          <label for="id_formacion" class="block text-sm text-gray-400 mb-1 font-semibold">Formación</label>
          <select
            id="id_formacion"
            v-model="form.id_formacion"
            class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
          >
            <option value="">Seleccionar formación</option>
            <option v-for="f in formaciones" :key="f.id" :value="f.id">{{ f.nombre }}</option>
          </select>
          <p v-if="form.errors.id_formacion" class="text-red-500 text-sm mt-1">
            {{ form.errors.id_formacion }}
          </p>
        </div>

        <div class="grid grid-cols-3 gap-6">
          <div>
            <label for="instagram" class="block text-sm text-gray-400 mb-1 font-semibold">Instagram</label>
            <input
              id="instagram"
              v-model="form.instagram"
              type="text"
              class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
            />
          </div>
          <div>
            <label for="twitch" class="block text-sm text-gray-400 mb-1 font-semibold">Twitch</label>
            <input
              id="twitch"
              v-model="form.twitch"
              type="text"
              class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
            />
          </div>
          <div>
            <label for="youtube" class="block text-sm text-gray-400 mb-1 font-semibold">YouTube</label>
            <input
              id="youtube"
              v-model="form.youtube"
              type="text"
              class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
            />
          </div>
        </div>

        <div>
          <label for="id_usuario" class="block text-sm text-gray-400 mb-1 font-semibold">Usuario Responsable</label>
          <select
            id="id_usuario"
            v-model="form.id_usuario"
            class="w-full p-3 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
          >
            <option value="">Seleccionar usuario</option>
            <option v-for="user in usuarios" :key="user.id" :value="user.id">{{ user.name }}</option>
          </select>
          <p v-if="form.errors.id_usuario" class="text-red-500 text-sm mt-1">
            {{ form.errors.id_usuario }}
          </p>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-red-600 hover:bg-red-700 transition text-white font-semibold py-3 rounded"
        >
          Guardar cambios
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<style scoped>
input[type="color"] {
  border: none !important;
  padding: 0;
  -webkit-appearance: none;
  appearance: none;
  background: none;
}

input[type="color"]::-webkit-color-swatch-wrapper {
  padding: 0;
}

input[type="color"]::-webkit-color-swatch {
  border: none;
  border-radius: 0.375rem; /* coincide con rounded de Tailwind */
}

input[type="color"]::-moz-color-swatch {
  border: none;
  border-radius: 0.375rem;
}
</style>
