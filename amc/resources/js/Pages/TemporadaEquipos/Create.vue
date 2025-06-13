<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EquipoForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  equipos: Array,
  id_temporadacompetencia: Number
})

const form = useForm({
  id_equipo: '',
  id_temporadacompetencia: props.id_temporadacompetencia,
})

// Opciones para select
const equipoOptions = props.equipos.map(e => ({
  label: e.nombre,
  value: e.id
}))

const fields = [
  {
    name: 'id_equipo',
    label: 'Equipo',
    type: 'select',
    required: true,
    options: equipoOptions
  },
  {
    name: 'id_temporadacompetencia',
    type: 'hidden'
  }
]

const submit = () => {
  form.post('/temporada-equipos', {
    forceFormData: true
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Afiliar Equipo a Temporada Competencia</template>
    <div class="p-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-[800px]">
      <h1 class="text-2xl font-bold mb-4 text-white">Afiliar Equipo</h1>

      <EquipoForm
        :fields="fields"
        :form="form"
        :submit="submit"
        submit-label="Afiliar Equipo"
      />
    </div>
  </AdminLayout>
</template>
