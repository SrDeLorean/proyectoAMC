<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'
import { usePage } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'

const page = usePage()
const equipoData = page.props.equipo || {
  nombre: '',
  descripcion: '',
  color_primario: '#ff0000',
  color_secundario: '#00ff00',
  instagram: '',
  twitch: '',
  youtube: '',
  id_formacion: '',
  id_usuario: '',
  logo: null,
}

// Aquí podrías reemplazar formacionesOptions con props o datos del servidor si prefieres
const formacionesOptions = [
  { value: '1', label: '4-4-2' },
  { value: '2', label: '4-3-3' },
  { value: '3', label: '3-5-2' },
]

const fields = [
  { name: 'nombre', label: 'Nombre', type: 'text', required: true },
  { name: 'descripcion', label: 'Descripción', type: 'textarea' },
  { name: 'logo', label: 'Logo', type: 'file' },
  { name: 'color_primario', label: 'Color Primario', type: 'color' },
  { name: 'color_secundario', label: 'Color Secundario', type: 'color' },
  {
    name: 'id_formacion',
    label: 'Formación',
    type: 'select',
    options: formacionesOptions,
    required: true,
  },
  { name: 'instagram', label: 'Instagram', type: 'text', placeholder: 'URL Instagram' },
  { name: 'twitch', label: 'Twitch', type: 'text', placeholder: 'URL Twitch' },
  { name: 'youtube', label: 'YouTube', type: 'text', placeholder: 'URL YouTube' },
  { name: 'id_usuario', label: 'ID Usuario', type: 'text' },
]

const form = useForm({ ...equipoData, logo: null })

const submitUrl = `/equipos/${equipoData.id || ''}`
const method = 'post' // Spoofing con _method='PUT' para actualizar

function submit() {
  form.post(submitUrl, {
    _method: 'PUT',
    forceFormData: true,
    onSuccess: () => {
      form.reset('logo')
    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Equipo</template>

    <div class="p-6 bg-gray-900 min-h-screen max-w-3xl mx-auto">
      <h1 class="text-3xl font-bold text-white mb-6">Editar Equipo</h1>
      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Actualizar Equipo"
      />
    </div>
  </AdminLayout>
</template>
