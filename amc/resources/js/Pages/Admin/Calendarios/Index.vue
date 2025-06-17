<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import dayjs from 'dayjs'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'

dayjs.extend(isSameOrAfter)

import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  calendarios: Array,
  jornadas: Array,
  temporadas: Array,
  competencias: Array,
  success: String,
  trashed: Boolean
})

const selectedJornada = ref('')
const selectedTemporada = ref('')
const selectedCompetencia = ref('')

const jornadaActual = computed(() => {
  const hoy = dayjs()
  const proximosPartidos = props.calendarios
    .filter(c => dayjs(c.fecha).isSameOrAfter(hoy, 'day'))
    .sort((a, b) => dayjs(a.fecha).diff(dayjs(b.fecha)))

  if (proximosPartidos.length > 0) {
    return proximosPartidos[0].jornada
  }

  const ultJornada = Math.max(...props.calendarios.map(c => c.jornada))
  return ultJornada
})

if (!selectedJornada.value) {
  selectedJornada.value = jornadaActual.value
}

const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Temporada', key: 'temporada_competencia.temporada.nombre' },
  { label: 'Competencia', key: 'temporada_competencia.competencia.nombre' },
  { label: 'Jornada', key: 'jornada' },
  { label: 'Local', key: 'equipo_local.nombre' },
  { label: 'L', key: 'equipo_local.logo' },
  { label: 'V', key: 'equipo_visitante.logo' },
  { label: 'Visitante', key: 'equipo_visitante.nombre' },
  { label: 'Fecha', key: 'fecha' },
  { label: 'Hora', key: 'hora' }
]

const columns = computed(() =>
  props.trashed
    ? [...baseColumns, { label: 'Eliminado el', key: 'deleted_at' }]
    : baseColumns
)

const filteredCalendarios = computed(() => {
  return props.calendarios.filter(c => {
    const matchJornada = selectedJornada.value
      ? String(c.jornada) === String(selectedJornada.value)
      : true

    const matchTemporada = selectedTemporada.value
      ? String(c.temporada_competencia.temporada.id) === String(selectedTemporada.value)
      : true

    const matchCompetencia = selectedCompetencia.value
      ? String(c.temporada_competencia.competencia.id) === String(selectedCompetencia.value)
      : true

    return matchJornada && matchTemporada && matchCompetencia
  })
})

const actions = computed(() =>
  props.trashed
    ? [
        {
          label: 'Restaurar',
          class: 'text-green-500 hover:text-green-700 transition',
          actionName: 'restore'
        }
      ]
    : [
        {
          label: 'Detalle',
          class: 'text-yellow-400 hover:text-yellow-600 transition',
          actionName: 'show'
        },
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
)

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/calendarios/${row.id}/edit`)
  }
  if (actionName === 'show') {
    router.get(`/admin/calendarios/${row.id}`)
  }
  if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar partido jornada ${row.jornada}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/calendarios/${row.id}`)
      }
    })
  }
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar partido jornada ${row.jornada}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/admin/calendarios/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>
      {{ trashed ? 'Partidos Eliminados' : 'Calendario de Partidos' }}
    </template>

    <div class="p-6 space-y-6">
      <div v-if="success" class="p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Partidos Eliminados' : 'Calendario de Partidos' }}
        </h1>
      </div>

      <div class="mb-4 text-white font-semibold text-lg">
        Jornada actual: <span class="text-red-500">Jornada {{ jornadaActual }}</span>
      </div>

      <!-- Filtros con iconos y estilos -->
      <div class="flex flex-wrap gap-8 mb-8">

        <!-- Temporada -->
        <div class="flex flex-col w-64">
          <label
            for="filtro-temporada"
            class="mb-2 text-sm font-semibold text-gray-300 flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05L5.636 5.636m12.728 0l-1.414 1.414M7.05 16.95l-1.414 1.414" />
            </svg>
            Filtrar por Temporada:
          </label>
          <select
            id="filtro-temporada"
            v-model="selectedTemporada"
            class="bg-gray-900 text-white rounded-lg px-4 py-2 border border-gray-700 shadow-inner
                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
          >
            <option value="">Todas las temporadas</option>
            <option
              v-for="temporada in temporadas"
              :key="temporada.id"
              :value="temporada.id"
            >
              {{ temporada.nombre }}
            </option>
          </select>
        </div>

        <!-- Competencia -->
        <div class="flex flex-col w-64">
          <label
            for="filtro-competencia"
            class="mb-2 text-sm font-semibold text-gray-300 flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a8 8 0 11-16 0 8 8 0 0116 0z" />
            </svg>
            Filtrar por Competencia:
          </label>
          <select
            id="filtro-competencia"
            v-model="selectedCompetencia"
            class="bg-gray-900 text-white rounded-lg px-4 py-2 border border-gray-700 shadow-inner
                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
          >
            <option value="">Todas las competencias</option>
            <option
              v-for="competencia in competencias"
              :key="competencia.id"
              :value="competencia.id"
            >
              {{ competencia.nombre }}
            </option>
          </select>
        </div>

        <!-- Jornada -->
        <div class="flex flex-col w-48">
          <label
            for="filtro-jornada"
            class="mb-2 text-sm font-semibold text-gray-300 flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" />
            </svg>
            Filtrar por Jornada:
          </label>
          <select
            id="filtro-jornada"
            v-model="selectedJornada"
            class="bg-gray-900 text-white rounded-lg px-4 py-2 border border-gray-700 shadow-inner
                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
          >
            <option value="">Todas las jornadas</option>
            <option v-for="j in jornadas" :key="j" :value="j">
              Jornada {{ j }}
            </option>
          </select>
        </div>
      </div>

      <BaseTable
        :columns="columns"
        :rows="filteredCalendarios"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
