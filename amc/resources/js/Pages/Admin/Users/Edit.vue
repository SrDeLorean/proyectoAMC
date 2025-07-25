<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.user

const fields = [
  { name: 'name', label: 'Nombre', type: 'text', required: true },
  { name: 'id_ea', label: 'ID EA', type: 'text' },
  { name: 'email', label: 'Email', type: 'email', required: true },
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
  name: user.name || '',
  id_ea: user.id_ea || '',
  email: user.email || '',
  role: user.role || 'jugador',
  foto: null, // en edición se recomienda empezar con null para poder subir nueva
}

const form = useForm({ ...initialData })

// Para mostrar imagen previa si user.foto existe:
const existingPhotoUrl = user.foto ? `/${user.foto}` : null

function submit() {
  form.post(`/admin/usuarios/${user.id}`, {
    _method: 'PUT', // spoofing para método PUT
    forceFormData: true,
    onSuccess: () => {
      form.reset('foto')
    }
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Usuario</template>

    <div class="p-6 text-white max-w-xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Editar Usuario</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :selectOptions="{ roles }"
        :existingImageUrl="existingPhotoUrl"
        submit-label="Actualizar Usuario"
      />
    </div>
  </AdminLayout>
</template>
