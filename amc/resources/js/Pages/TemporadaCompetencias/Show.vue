<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  temporadaCompetencia: Object,
  equipos: Array
})

// Columnas de equipos afiliados
const equipoColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre del Equipo', key: 'equipo.nombre' },
]

// Acciones sobre temporada_equipos
const equipoActions = [
  {
    label: 'Editar',
    class: 'text-blue-400 hover:text-blue-600 transition',
    actionName: 'edit'
  },
  {
    label: 'Eliminar',
    class: 'text-red-500 hover:text-red-700 transition',
    actionName: 'delete'
  }
]

function onEquipoAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/temporada-equipos/${row.id}/edit`)
  }

  if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la afiliación del equipo "${row.equipo?.nombre}"?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/temporada-equipos/${row.id}`)
      }
    })
  }
}

function goToCreateEquipo() {
  router.get('/temporada-equipos/create', {
    id_temporadacompetencia: props.temporadaCompetencia.id
  })
}

function generarCalendario() {
  Swal.fire({
    title: '¿Generar calendario de partidos?',
    text: 'Esta acción generará todos los partidos del torneo con los equipos afiliados.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#e30613',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, generar',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.post(`/calendario/generar/${props.temporadaCompetencia.id}`, {}, {
        onSuccess: () => {
          Swal.fire({
            title: '¡Éxito!',
            text: 'El calendario fue generado correctamente.',
            icon: 'success',
            confirmButtonColor: '#e30613',
          })
        }
      })
    }
  })
}
</script>


<template>
  <AdminLayout>
    <template #title>
      Detalle de Temporada Competencia
    </template>

    <div class="p-6 space-y-8 text-white">
      <!-- Información General -->
      <div class="space-y-2">
        <div class="flex justify-between items-center mb-2">
          <h2 class="text-xl font-semibold text-red-500">Información General</h2>
          <button
            @click="generarCalendario"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
          >
            ⚽ Generar Calendario
          </button>
        </div>
        <p><strong>Nombre:</strong> {{ temporadaCompetencia.nombre }}</p>
        <p><strong>Temporada:</strong> {{ temporadaCompetencia.temporada.nombre }}</p>
        <p><strong>Competencia:</strong> {{ temporadaCompetencia.competencia.nombre }}</p>
        <p><strong>Inicio:</strong> {{ temporadaCompetencia.fecha_inicio }}</p>
        <p><strong>Término:</strong> {{ temporadaCompetencia.fecha_termino }}</p>
      </div>

      <!-- Equipos Afiliados -->
      <div class="space-y-4">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold text-red-500">Equipos Afiliados</h2>
          <button
            @click="goToCreateEquipo"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
          >
            + Afiliar Equipo
          </button>
        </div>

        <BaseTable
          :columns="equipoColumns"
          :rows="equipos"
          :actions="equipoActions"
          @action="onEquipoAction"
        />

        <p v-if="equipos.length === 0" class="text-gray-400">
          No hay equipos afiliados a esta temporada competencia.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>
