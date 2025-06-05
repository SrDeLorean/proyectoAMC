<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  usuarios: Array,
  formaciones: Array
})

const form = useForm({
  nombre: '',
  descripcion: '',
  color_primario: '#000000',
  color_secundario: '#ffffff',
  logo: null,
  id_formacion: '',
  instagram: '',
  twitch: '',
  youtube: '',
  id_usuario: ''
})

const submit = () => {
  form.post('/equipos', {
    forceFormData: true
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Crear Equipo</template>
    <div class="p-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-[1200px]">
      <h1 class="text-2xl font-bold mb-4 text-white">Crear Equipo</h1>

    <form @submit.prevent="submit" class="bg-gray-900 p-6 rounded-xl space-y-4">

        <div>
          <label class="block text-sm text-gray-400">Nombre</label>
          <input
            v-model="form.nombre"
            type="text"
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Descripción</label>
          <textarea
            v-model="form.descripcion"
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.descripcion" class="text-red-500 text-sm">{{ form.errors.descripcion }}</p>
        </div>

        <div class="flex gap-6">
          <div class="flex-1">
            <label class="block text-sm text-gray-400 mb-1">Color Primario</label>
            <input
              v-model="form.color_primario"
              type="color"
              class="w-full h-10 rounded cursor-pointer"
              title="Color Primario"
            />
          </div>

          <div class="flex-1">
            <label class="block text-sm text-gray-400 mb-1">Color Secundario</label>
            <input
              v-model="form.color_secundario"
              type="color"
              class="w-full h-10 rounded cursor-pointer"
              title="Color Secundario"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Logo</label>
          <input
            type="file"
            @change="e => form.logo = e.target.files[0]"
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.logo" class="text-red-500 text-sm">{{ form.errors.logo }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Formación</label>
          <select v-model="form.id_formacion" class="w-full p-2 rounded bg-gray-800 text-white">
            <option value="">Seleccionar formación</option>
            <option v-for="f in formaciones" :key="f.id" :value="f.id">{{ f.nombre }}</option>
          </select>
          <p v-if="form.errors.id_formacion" class="text-red-500 text-sm">{{ form.errors.id_formacion }}</p>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm text-gray-400">Instagram</label>
            <input
              v-model="form.instagram"
              type="text"
              class="w-full p-2 rounded bg-gray-800 text-white"
            />
          </div>
          <div>
            <label class="block text-sm text-gray-400">Twitch</label>
            <input
              v-model="form.twitch"
              type="text"
              class="w-full p-2 rounded bg-gray-800 text-white"
            />
          </div>
          <div>
            <label class="block text-sm text-gray-400">YouTube</label>
            <input
              v-model="form.youtube"
              type="text"
              class="w-full p-2 rounded bg-gray-800 text-white"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Usuario Responsable</label>
          <select v-model="form.id_usuario" class="w-full p-2 rounded bg-gray-800 text-white">
            <option value="">Seleccionar usuario</option>
            <option v-for="user in usuarios" :key="user.id" :value="user.id">{{ user.name }}</option>
          </select>
          <p v-if="form.errors.id_usuario" class="text-red-500 text-sm">{{ form.errors.id_usuario }}</p>
        </div>

        <button
          type="submit"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition w-full mt-4"
        >
          Crear Equipo
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
