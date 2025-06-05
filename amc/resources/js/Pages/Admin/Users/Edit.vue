<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  user: Object,
})

const form = useForm({
  name: props.user.name || '',
  email: props.user.email || '',
  role: props.user.role || '',
  id_ea: props.user.id_ea || '',
  photo: null,
})

const imagePreview = ref(
  props.user.foto ? `/${props.user.foto}` : '/fotos/default.png'
)

function onFileChange(event) {
  const file = event.target.files[0]
  form.photo = file || null

  if (!file) {
    imagePreview.value = props.user.foto
      ? `/${props.user.foto}`
      : '/fotos/default.png'
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

  // ✅ ¡No uses transform ni FormData manual!
  form.post(`/admin/usuarios/${props.user.id}`, {
    method: 'post',
    forceFormData: true, // ✅ Esto convierte `form` en un FormData válido
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Usuario actualizado correctamente.'
    },
    onError: (errors) => {
      errorMessage.value = 'Hubo un error al actualizar el usuario.'
      console.log('Errores backend:', errors)
    },
  })
}
</script>




<template>
  <AdminLayout>
    <template #title>Editar Usuario</template>

    <div class="p-6 text-white max-w-xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Editar Usuario</h1>

      <div v-if="successMessage" class="mb-4 p-3 bg-green-600 rounded">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="mb-4 p-3 bg-red-600 rounded">
        {{ errorMessage }}
      </div>

      <form
        @submit.prevent="submit"
        enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow space-y-6"
      >
        <div class="flex items-center gap-6">
          <img
            :src="imagePreview"
            alt="Foto de perfil"
            class="w-20 h-20 rounded-full object-cover border border-gray-400"
          />
          <div class="flex flex-col">
            <label for="photo" class="text-gray-400 mb-1">Foto</label>
            <input
              id="photo"
              type="file"
              accept="image/*"
              @change="onFileChange"
              class="text-white"
            />
            <p v-if="form.errors.photo" class="text-red-500 text-sm mt-1">
              {{ form.errors.photo }}
            </p>
          </div>
        </div>

        <div>
          <label for="name" class="block text-sm text-gray-400 mb-1">Nombre</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
            {{ form.errors.name }}
          </p>
        </div>

        <div>
          <label for="email" class="block text-sm text-gray-400 mb-1">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
            {{ form.errors.email }}
          </p>
        </div>

        <div>
          <label for="role" class="block text-sm text-gray-400 mb-1">Rol</label>
          <select
            id="role"
            v-model="form.role"
            required
            class="w-full p-2 rounded bg-gray-800 text-white"
          >
            <option value="" disabled>Seleccione un rol</option>
            <option value="jugador">Jugador</option>
            <option value="entrenador">Entrenador</option>
            <option value="administrador">Administrador</option>
          </select>
          <p v-if="form.errors.role" class="text-red-500 text-sm mt-1">
            {{ form.errors.role }}
          </p>
        </div>

        <div>
          <label for="id_ea" class="block text-sm text-gray-400 mb-1">ID EA</label>
          <input
            id="id_ea"
            v-model="form.id_ea"
            type="text"
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.id_ea" class="text-red-500 text-sm mt-1">
            {{ form.errors.id_ea }}
          </p>
        </div>

        <button
          type="submit"
          class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded transition"
          :disabled="form.processing"
        >
          Guardar cambios
        </button>
      </form>
    </div>
  </AdminLayout>
</template>
