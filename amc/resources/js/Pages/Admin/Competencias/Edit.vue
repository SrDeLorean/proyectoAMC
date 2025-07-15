<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  competencia: Object,
})

const initialData = {
  nombre: props.competencia.nombre || '',
  logo: null,
}

const form = useForm({ ...initialData })

const fields = [
  {
    name: 'nombre',
    label: 'Nombre',
    type: 'text',
    required: true,
    placeholder: 'Ej: Competencia 2025',
  },
  {
    name: 'logo',
    label: 'Logo',
    type: 'file',
    accept: 'image/*',
  },
]

const existingLogoUrl = props.competencia.logo ? `/${props.competencia.logo}` : null

function submit() {
  form.post(`/admin/competencias/${props.competencia.id}`, {
    _method: 'put',
    forceFormData: true,
    onSuccess: () => form.reset('logo'),
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Competencia</template>

    <div class="p-6 max-w-3xl mx-auto text-white space-y-6">
      <h1 class="text-2xl font-bold">Editar Competencia</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :existingImageUrl="existingLogoUrl"
        submit-label="Guardar Cambios"
      />
    </div>
  </AdminLayout>
</template>
