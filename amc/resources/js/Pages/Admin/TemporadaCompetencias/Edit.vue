<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  temporadaCompetencia: Object,
  temporadas: Array,
  competencias: Array,
})

// Opciones para selects
const temporadaOptions = props.temporadas.map(t => ({ label: t.nombre, value: t.id }))
const competenciaOptions = props.competencias.map(c => ({ label: c.nombre, value: c.id }))

const initialData = {
  nombre: props.temporadaCompetencia.nombre ?? '',
  id_temporada: props.temporadaCompetencia.id_temporada ?? '',
  id_competencia: props.temporadaCompetencia.id_competencia ?? '',
  fecha_inicio: props.temporadaCompetencia.fecha_inicio ?? '',
  fecha_termino: props.temporadaCompetencia.fecha_termino ?? '',
}

const form = useForm({ ...initialData })

const fields = [
  {
    name: 'nombre',
    label: 'Nombre',
    type: 'text',
    placeholder: 'Ej: Temporada 2025 - Liga',
    required: true,
  },
  {
    name: 'id_temporada',
    label: 'Temporada',
    type: 'select',
    options: temporadaOptions,
    required: true,
  },
  {
    name: 'id_competencia',
    label: 'Competencia',
    type: 'select',
    options: competenciaOptions,
    required: true,
  },
  {
    name: 'fecha_inicio',
    label: 'Fecha Inicio',
    type: 'date',
  },
  {
    name: 'fecha_termino',
    label: 'Fecha Término',
    type: 'date',
  },
]

function submit() {
  form.put(`/admin/temporada-competencias/${props.temporadaCompetencia.id}`, {
    onSuccess: () => {
      // Opcional: acciones post éxito, ej: notificación o redirección
    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Temporada Competencia</template>

    <div class="p-6 max-w-3xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-6">Editar Temporada Competencia</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Guardar Cambios"
      />
    </div>
  </AdminLayout>
</template>
