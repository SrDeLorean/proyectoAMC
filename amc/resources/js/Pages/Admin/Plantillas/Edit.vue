<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  plantilla: Object,
  equipos: Array,
  jugadores: Array,
})

// Datos iniciales con valores existentes para edición
const initialData = {
  id_equipo: props.plantilla.id_equipo ?? '',
  id_jugador: props.plantilla.id_jugador ?? '',
  posicion: props.plantilla.posicion ?? '',
  numero: props.plantilla.numero ?? '',
}

const form = useForm({ ...initialData })

// Opciones para selects
const equiposOptions = props.equipos.map(e => ({
  value: e.id,
  label: e.nombre,
}))

const jugadoresOptions = props.jugadores.map(j => ({
  value: j.id,
  label: j.name,
}))

const fields = [
  {
    name: 'id_equipo',
    label: 'Equipo',
    type: 'select',
    required: true,
    placeholder: 'Seleccione un equipo',
    optionsKey: 'equiposOptions',
  },
  {
    name: 'id_jugador',
    label: 'Jugador',
    type: 'select',
    required: true,
    placeholder: 'Seleccione un jugador',
    optionsKey: 'jugadoresOptions',
  },
  {
    name: 'posicion',
    label: 'Posición',
    type: 'text',
    placeholder: 'Ej: Defensa',
    required: true,
  },
  {
    name: 'numero',
    label: 'Número',
    type: 'number',
    placeholder: 'Ej: 10',
    required: true,
  },
]

function submit() {
  form.put(`/admin/plantillas/${props.plantilla.id}`, {
    onSuccess: () => {

    },
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Plantilla</template>

    <div class="p-6 max-w-3xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-6">Editar Plantilla</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :selectOptions="{ equiposOptions, jugadoresOptions }"
        submit-label="Guardar Cambios"
      />
    </div>
  </AdminLayout>
</template>
