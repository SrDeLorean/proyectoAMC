<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EquipoForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  temporadas: Array,
  competencias: Array,
})

const form = useForm({
  nombre: '',
  id_temporada: '',
  id_competencia: '',
  fecha_inicio: '',
  fecha_termino: '',
})

// Opciones para selects, para pasarlas en campos custom
const temporadaOptions = props.temporadas.map(t => ({ label: t.nombre, value: t.id }))
const competenciaOptions = props.competencias.map(c => ({ label: c.nombre, value: c.id }))

const fields = [
  { name: 'nombre', label: 'Nombre', type: 'text', required: true },
  {
    name: 'id_temporada', label: 'Temporada', type: 'select', required: true,
    options: temporadaOptions
  },
  {
    name: 'id_competencia', label: 'Competencia', type: 'select', required: true,
    options: competenciaOptions
  },
  { name: 'fecha_inicio', label: 'Fecha Inicio', type: 'date' },
  { name: 'fecha_termino', label: 'Fecha Término', type: 'date' },
]

const submit = () => {
  form.post('/admin/temporada-competencias', {
    forceFormData: true
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Crear Temporada Competencia</template>
    <div class="p-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-[1200px]">
      <h1 class="text-2xl font-bold mb-4 text-white">Crear Temporada Competencia</h1>

      <EquipoForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Crear Temporada Competencia"
      />
    </div>
  </AdminLayout>
</template>
