<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  temporadaEquipo: Object,
  temporadaCompetencias: Array,
  equipos: Array,
})

const equipoOptions = props.equipos.map(e => ({
  label: e.nombre,
  value: e.id,
}))

const  form = useForm({
  id_temporadacompetencia: props.temporadaEquipo.id_temporadacompetencia,
  id_equipo: props.temporadaEquipo.id_equipo,
})
const fields = [
  {
    name: 'id_equipo',
    label: 'Equipo',
    type: 'select',
    required: true,
    options: equipoOptions,
  },
  {
    name: 'id_temporadacompetencia',
    type: 'hidden',
  }
]

function submit() {
  form.put(`/temporada-equipos/${props.temporadaEquipo.id}`, {
    onSuccess: () => {
      // opcional: notificación o redirección
    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Afiliación de Equipo</template>

    <div class="p-6 max-w-3xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-6">Editar Afiliación de Equipo</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Guardar Cambios"
      />
    </div>
  </AdminLayout>
</template>
