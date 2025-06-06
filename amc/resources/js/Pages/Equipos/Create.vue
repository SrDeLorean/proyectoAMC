<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EquipoForm from '@/Components/Forms/CrudForm.vue'

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

const fields = [
  { name: 'nombre', label: 'Nombre', type: 'text', required: true },
  { name: 'descripcion', label: 'Descripción', type: 'textarea' },
  { name: 'color_primario', label: 'Color Primario', type: 'color' },
  { name: 'color_secundario', label: 'Color Secundario', type: 'color' },
  { name: 'logo', label: 'Logo', type: 'file' },
  { name: 'id_formacion', label: 'Formación', type: 'select', optionsKey: 'formaciones', required: true },
  { name: 'instagram', label: 'Instagram', type: 'text' },
  { name: 'twitch', label: 'Twitch', type: 'text' },
  { name: 'youtube', label: 'YouTube', type: 'text' },
  { name: 'id_usuario', label: 'Usuario Responsable', type: 'select', optionsKey: 'usuarios', required: true }
]
</script>

<template>
  <AdminLayout>
    <template #title>Crear Equipo</template>
    <div class="p-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-[1200px]">
      <h1 class="text-2xl font-bold mb-4 text-white">Crear Equipo</h1>

      <EquipoForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :selectOptions="{ usuarios: props.usuarios, formaciones: props.formaciones }"
        submit-label="Crear Equipo"
      />
    </div>
  </AdminLayout>
</template>
