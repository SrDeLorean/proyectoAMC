<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const fields = [
  { name: 'name', label: 'Nombre', type: 'text', required: true },
  { name: 'id_ea', label: 'ID EA', type: 'text' },
  { name: 'email', label: 'Email', type: 'email', required: true },
  { name: 'password', label: 'Contraseña', type: 'password', required: true },
  { name: 'password_confirmation', label: 'Confirmar Contraseña', type: 'password', required: true },
  {
    name: 'role',
    label: 'Rol',
    type: 'select',
    required: true,
    placeholder: 'Seleccione un rol',
    optionsKey: 'roles'
  },
  { name: 'foto', label: 'Foto de perfil', type: 'file' },
]

const roles = [
  { label: 'Jugador', value: 'jugador' },
  { label: 'Entrenador', value: 'entrenador' },
  { label: 'Administrador', value: 'administrador' }
]

const initialData = {
  name: '',
  id_ea: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'jugador',
  foto: null,
}

const form = useForm({ ...initialData })

function submit() {
  form.post('/admin/usuarios', {
    forceFormData: true,
    onSuccess: () => {
      form.reset('password', 'password_confirmation', 'foto') // Limpiar solo esos campos tras éxito
    }
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Crear Usuario</template>
    <div class="p-6 max-w-2xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-4">Crear Usuario</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :selectOptions="{ roles }"
        submit-label="Crear Usuario"
      />
    </div>
  </AdminLayout>
</template>
