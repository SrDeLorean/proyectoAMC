<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const form = useForm({
  name: '',
  id_ea: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'jugador',
  photo: null,
})

const submit = () => {
  form.post('/admin/usuarios', {
    forceFormData: true,
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Crear Usuario</template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4 text-white">Crear Usuario</h1>

      <form @submit.prevent="submit" class="bg-gray-900 p-6 rounded-xl shadow space-y-4">
        <div>
          <label class="block text-sm text-gray-400">Nombre</label>
          <input v-model="form.name" type="text" class="w-full p-2 rounded bg-gray-800 text-white" />
          <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">ID EA</label>
          <input v-model="form.id_ea" type="text" class="w-full p-2 rounded bg-gray-800 text-white" />
          <p v-if="form.errors.id_ea" class="text-red-500 text-sm">{{ form.errors.id_ea }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Email</label>
          <input v-model="form.email" type="email" class="w-full p-2 rounded bg-gray-800 text-white" />
          <p v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Contraseña</label>
          <input v-model="form.password" type="password" class="w-full p-2 rounded bg-gray-800 text-white" />
          <p v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Confirmar Contraseña</label>
          <input v-model="form.password_confirmation" type="password" class="w-full p-2 rounded bg-gray-800 text-white" />
        </div>

        <div>
          <label class="block text-sm text-gray-400">Rol</label>
          <select v-model="form.role" class="w-full p-2 rounded bg-gray-800 text-white">
            <option value="jugador">Jugador</option>
            <option value="entrenador">Entrenador</option>
            <option value="administrador">Administrador</option>
          </select>
          <p v-if="form.errors.role" class="text-red-500 text-sm">{{ form.errors.role }}</p>
        </div>

        <div>
          <label class="block text-sm text-gray-400">Foto de perfil</label>
          <input
            type="file"
            @change="e => form.photo = e.target.files[0]"
            class="w-full p-2 rounded bg-gray-800 text-white"
          />
          <p v-if="form.errors.photo" class="text-red-500 text-sm">{{ form.errors.photo }}</p>
        </div>

        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
          Crear Usuario
        </button>
      </form>
    </div>
  </AdminLayout>
</template>
