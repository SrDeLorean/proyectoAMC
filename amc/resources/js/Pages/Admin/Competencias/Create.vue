<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

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

const initialData = {
  nombre: '',
  logo: null,
}

const form = useForm({ ...initialData })

function submit() {
  form.post('/admin/competencias', {
    forceFormData: true,
    onSuccess: () => {
      form.reset('nombre', 'logo')
    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Crear Competencia</template>
    <div class="p-6 max-w-2xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-4">Crear Competencia</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Crear Competencia"
      />
    </div>
  </AdminLayout>
</template>
