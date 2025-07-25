<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import dayjs from 'dayjs'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'

dayjs.extend(isSameOrAfter)

import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  calendarios: Object,
  jornadas: Array,
  temporadas: Array,
  competencias: Array,
  filters: Object,
  success: String,
  trashed: Boolean,
})

// Filtros
const selectedJornada = ref(props.filters?.jornada || '')
const selectedTemporada = ref(props.filters?.temporada || '')
const selectedCompetencia = ref(props.filters?.competencia || '')
const filterText = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

// Computed para jornada actual
const jornadaActual = computed(() => {
  const hoy = dayjs()
  const proximosPartidos = props.calendarios.data
    .filter(c => dayjs(c.fecha).isSameOrAfter(hoy, 'day'))
    .sort((a, b) => dayjs(a.fecha).diff(dayjs(b.fecha)))

  if (proximosPartidos.length > 0) {
    return proximosPartidos[0].jornada
  }
  return Math.max(...props.calendarios.data.map(c => c.jornada))
})

// Columnas
const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Temporada', key: 'temporada_competencia.temporada.nombre' },
  { label: 'Competencia', key: 'temporada_competencia.competencia.nombre' },
  { label: 'Jornada', key: 'jornada' },
  { label: 'Local', key: 'equipo_local.nombre' },
  { label: 'L', key: 'equipo_local.logo' },

  // Nueva columna Marcador con render personalizado
  {
    label: 'Marcador',
    key: 'marcador',
    render: (row) => {
      if (row.marcador && typeof row.marcador === 'string') {
        // Asumiendo formato "x - y" o "x-y"
        return row.marcador.trim() !== '' ? row.marcador : '---'
      }
      // Si marcador no existe o está vacío
      return '---'
    }
  },

  { label: 'V', key: 'equipo_visitante.logo' },
  { label: 'Visitante', key: 'equipo_visitante.nombre' },
  { label: 'Fecha', key: 'fecha' },
  { label: 'Hora', key: 'hora' },
]
const columns = computed(() =>
  props.trashed ? [...baseColumns, { label: 'Eliminado el', key: 'deleted_at' }] : baseColumns
)

// Acciones
const actions = computed(() =>
  props.trashed
    ? [{ label: 'Restaurar', actionName: 'restore' }]
    : [
        { label: 'Detalle', actionName: 'show' },
        { label: 'Editar', actionName: 'edit' },
        { label: 'Eliminar', actionName: 'delete' },
      ]
)

// Acciones de tabla
function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/calendarios/${row.id}/edit`)
  } else if (actionName === 'show') {
    router.get(`/admin/calendarios/${row.id}`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar partido jornada ${row.jornada}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/calendarios/${row.id}`)
      }
    })
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar partido jornada ${row.jornada}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/admin/calendarios/${row.id}/restore`)
      }
    })
  }
}

// Actualizar filtros o paginación
function reloadWithParams(extra = {}) {
  router.get('/admin/calendarios', {
    jornada: selectedJornada.value || undefined,
    temporada: selectedTemporada.value || undefined,
    competencia: selectedCompetencia.value || undefined,
    search: filterText.value || undefined,
    perPage: perPage.value,
    ...extra
  }, {
    preserveState: true,
    replace: true,
  })
}

watch([selectedJornada, selectedTemporada, selectedCompetencia], reloadWithParams)
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

      <!-- Filtros personalizados -->
      <!-- (sin cambios en tu bloque de filtros existentes) -->

      <BaseTable
        :columns="columns"
        :rows="props.calendarios.data"
        :meta="props.calendarios.meta"
        :links="props.calendarios.links"
        :filterText="filterText"
        :perPage="perPage"
        :actions="actions"
        @action="onTableAction"
        @update:filterText="val => { filterText = val; reloadWithParams() }"
        @update:perPage="val => { perPage = val; reloadWithParams() }"
        @navigate="url => router.visit(url, { preserveScroll: true, preserveState: true })"
      />
    </div>
  </AdminLayout>
</template>
