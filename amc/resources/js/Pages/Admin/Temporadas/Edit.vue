<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  temporada: Object,
})

// Datos iniciales con valores existentes para edición
const initialData = {
  nombre: props.temporada.nombre ?? '',
}

const form = useForm({ ...initialData })

const fields = [
  {
    name: 'nombre',
    label: 'Nombre',
    type: 'text',
    placeholder: 'Ej: Temporada 2025',
    required: true,
  },
]

function submit() {
  form.put(`/admin/temporadas/${props.temporada.id}`, {
    onSuccess: () => {
      // Opcional: acciones post éxito (ej: notificación, redireccionar...)
    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Temporada</template>

    <div class="p-6 max-w-3xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-6">Editar Temporada</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Guardar Cambios"
      />
    </div>
  </AdminLayout>
</template>
