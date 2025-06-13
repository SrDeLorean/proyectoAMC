<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EquipoForm from '@/Components/Forms/CrudForm.vue'

const form = useForm({
  nombre: ''
})

const submit = () => {
  form.post('/competencias', {
    forceFormData: true
  })
}

const fields = [
  { name: 'nombre', label: 'Nombre', type: 'text', required: true }
]
</script>

<template>
  <AdminLayout>
    <template #title>Crear Competencia</template>
    <div class="p-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-[1200px]">
      <h1 class="text-2xl font-bold mb-4 text-white">Crear Competencia</h1>

      <EquipoForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Crear Competencia"
      />
    </div>
  </AdminLayout>
</template>
