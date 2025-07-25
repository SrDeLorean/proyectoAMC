<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  equipos: Array,
  jugadores: Array,
})

const equiposOptions = props.equipos.map(equipo => ({
  value: equipo.id,
  label: equipo.nombre,
}))

const jugadoresOptions = props.jugadores.map(jugador => ({
  value: jugador.id,
  label: jugador.name,
}))

const fields = [
  {
    name: 'id_equipo',
    label: 'Equipo',
    type: 'select',
    placeholder: 'Seleccione un equipo',
    required: true,
    optionsKey: 'equiposOptions',
  },
  {
    name: 'id_jugador',
    label: 'Jugador',
    type: 'select',
    placeholder: 'Seleccione un jugador',
    required: true,
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

const form = useForm({
  id_equipo: '',
  id_jugador: '',
  posicion: '',
  numero: '',
})

function submit() {
  form.post('/admin/plantillas', {
    onSuccess: () => {
      form.reset('posicion', 'numero', 'id_equipo', 'id_jugador') // limpia todo para nueva creación
    }
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Crear Plantilla</template>

    <div class="p-6 max-w-2xl mx-auto text-white">
      <h1 class="text-2xl font-bold mb-4">Crear Plantilla</h1>

      <CrudForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :selectOptions="{ equiposOptions, jugadoresOptions }"
        submit-label="Crear Plantilla"
      />
    </div>
  </AdminLayout>
</template>
