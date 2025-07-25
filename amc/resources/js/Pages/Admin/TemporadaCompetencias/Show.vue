<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  temporadaCompetencia: Object,
  equipos: Array
})

// Columnas con estadísticas
const equipoColumns = [
  { label: 'Logo', key: 'equipo.logo' },
  { label: 'ID', key: 'id' },
  { label: 'Equipo', key: 'equipo.nombre' },
  { label: 'P', key: 'puntos' },
  { label: 'PJ', key: 'partidos_jugados' },
  { label: 'V', key: 'victorias' },
  { label: 'E', key: 'empates' },
  { label: 'D', key: 'derrotas' },
  { label: 'GF', key: 'goles_a_favor' },
  { label: 'GC', key: 'goles_en_contra' },
  { label: 'DG', key: 'diferencia_goles' },
]

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
    router.get(`/admin/temporada-equipos/${row.id}/edit`)
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
        router.delete(`/admin/temporada-equipos/${row.id}`)
      }
    })
  }
}

function goToCreateEquipo() {
  router.get('/admin/temporada-equipos/create', {
    id_temporadacompetencia: props.temporadaCompetencia.id
  })
}

// Funciones para liga
function generarCalendario() {
  Swal.fire({
    title: '¿Generar calendario de partidos (ida y vuelta)?',
    text: 'Se generarán todos los partidos de ida y vuelta.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#e30613',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, generar',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.post(`/admin/calendarios/${props.temporadaCompetencia.id}/generar`, {}, {
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

function generarCalendarioSoloIda() {
  Swal.fire({
    title: '¿Generar calendario solo de ida?',
    text: 'Se generarán únicamente los partidos de ida (una sola vuelta).',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#e30613',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, solo ida',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.post(`/admin/calendarios/${props.temporadaCompetencia.id}/generar-solo-ida`, {}, {
        onSuccess: () => {
          Swal.fire({
            title: '¡Éxito!',
            text: 'El calendario solo de ida fue generado correctamente.',
            icon: 'success',
            confirmButtonColor: '#e30613',
          })
        }
      })
    }
  })
}

// Funciones para copa
function generarPareamientoDirecto() {
  Swal.fire({
    title: '¿Generar pareamiento 1 a 1 desde cuartos/octavos?',
    text: 'Se emparejarán todos los equipos desde la fase de eliminación directa.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#e30613',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, generar pareamiento',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.post(`/admin/calendarios/${props.temporadaCompetencia.id}/generar-copa-directa`, {}, {
        onSuccess: () => {
          Swal.fire({
            title: '¡Éxito!',
            text: 'El pareamiento fue generado correctamente.',
            icon: 'success',
            confirmButtonColor: '#e30613',
          })
        }
      })
    }
  })
}

function generarPareamientoConVentaja() {
  Swal.fire({
    title: '¿Generar calendario con ventaja para primero y segundo lugar?',
    html: `
      <p>El primer lugar queda directo en la final,</p>
      <p>el segundo lugar queda en semifinal,</p>
      <p>el resto inicia en cuartos/octavos según cantidad de equipos.</p>
    `,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#e30613',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, generar con ventaja',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (result.isConfirmed) {
      router.post(`/admin/calendarios/${props.temporadaCompetencia.id}/generar-copa-con-ventaja`, {}, {
        onSuccess: () => {
          Swal.fire({
            title: '¡Éxito!',
            text: 'El calendario con ventaja fue generado correctamente.',
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

    <div class="p-6 space-y-8 text-white max-w-5xl mx-auto">

      <!-- Información General Mejorada -->
      <section
        class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-xl shadow-lg p-7"
      >
        <div class="flex justify-between items-center mb-5">
          <h2 class="text-3xl font-extrabold text-red-500 tracking-wide uppercase">
            Información General
          </h2>

          <div class="flex flex-wrap gap-3">
            <template v-if="temporadaCompetencia.formato === 'liga'">
              <button
                @click="generarCalendario"
                class="flex items-center gap-3 bg-red-600 hover:bg-red-700 transition rounded-lg px-5 py-2.5 font-semibold shadow-md shadow-red-700/50"
              >
                ⚽ <span>Calendario Ida y Vuelta</span>
              </button>
              <button
                @click="generarCalendarioSoloIda"
                class="flex items-center gap-3 bg-red-500 hover:bg-red-600 transition rounded-lg px-5 py-2.5 font-semibold shadow-md shadow-red-600/40"
              >
                🟥 <span>Solo Ida</span>
              </button>
            </template>

            <template v-else-if="temporadaCompetencia.formato === 'copa'">
              <button
                @click="generarPareamientoDirecto"
                class="flex items-center gap-3 bg-red-600 hover:bg-red-700 transition rounded-lg px-5 py-2.5 font-semibold shadow-md shadow-red-700/50"
              >
                🤝 <span>Pareamiento Directo</span>
              </button>
              <button
                @click="generarPareamientoConVentaja"
                class="flex items-center gap-3 bg-red-500 hover:bg-red-600 transition rounded-lg px-5 py-2.5 font-semibold shadow-md shadow-red-600/40"
              >
                🏆 <span>Calendario con Ventaja</span>
              </button>
            </template>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-y-6 gap-x-10 text-gray-300">

          <!-- Nombre -->
          <div class="flex flex-col">
            <span class="text-red-500 font-semibold tracking-wide mb-1 uppercase">Nombre</span>
            <span class="text-white text-lg font-medium truncate">{{ temporadaCompetencia.nombre }}</span>
          </div>

          <!-- Formato -->
          <div class="flex flex-col">
            <span class="text-red-500 font-semibold tracking-wide mb-1 uppercase">Formato</span>
            <span class="text-white text-lg font-medium capitalize">
              {{ temporadaCompetencia.formato }}
            </span>
          </div>

          <!-- Temporada -->
          <div class="flex flex-col">
            <span class="text-red-500 font-semibold tracking-wide mb-1 uppercase">Temporada</span>
            <span class="text-white text-lg font-medium truncate">{{ temporadaCompetencia.temporada.nombre }}</span>
          </div>

          <!-- Competencia -->
          <div class="flex flex-col">
            <span class="text-red-500 font-semibold tracking-wide mb-1 uppercase">Competencia</span>
            <span class="text-white text-lg font-medium truncate">{{ temporadaCompetencia.competencia.nombre }}</span>
          </div>

          <!-- Inicio -->
          <div class="flex flex-col">
            <span class="text-red-500 font-semibold tracking-wide mb-1 uppercase">Inicio</span>
            <span class="text-white text-lg font-medium">
              {{ new Date(temporadaCompetencia.fecha_inicio).toLocaleDateString() }}
            </span>
          </div>

          <!-- Término -->
          <div class="flex flex-col">
            <span class="text-red-500 font-semibold tracking-wide mb-1 uppercase">Término</span>
            <span class="text-white text-lg font-medium">
              {{ new Date(temporadaCompetencia.fecha_termino).toLocaleDateString() }}
            </span>
          </div>
        </div>
      </section>

      <!-- Equipos Afiliados -->
      <div class="space-y-4">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold text-red-500">Tabla de Posiciones</h2>
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
