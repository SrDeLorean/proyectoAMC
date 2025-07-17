<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { computed, ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  temporadaCompetencias: Object,  // paginada
  filters: Object,                // { search, perPage }
  success: String,
  trashed: Boolean,
})

// Local reactive para el mensaje success
const localSuccess = ref(props.success)
watch(() => props.success, val => {
  localSuccess.value = val
  if (val) setTimeout(() => (localSuccess.value = ''), 3000)
})

// Reactivos para filtros
const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 15)

// Debounce para evitar peticiones continuas
let timeout = null
watch([search, perPage], ([newSearch, newPerPage]) => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(
      '/admin/temporada-competencias',
      { search: newSearch, perPage: newPerPage },
      { preserveState: true, replace: true }
    )
  }, 300)
})

// Columnas base
const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' },
  { label: 'Temporada', key: 'temporada.nombre' },
  { label: 'Competencia', key: 'competencia.nombre' },
  { label: 'Formato', key: 'formato' },
  { label: 'Fecha Inicio', key: 'fecha_inicio' },
  { label: 'Fecha Término', key: 'fecha_termino' },
]

// Si mostramos eliminados, agregamos columna extra
const columns = computed(() =>
  props.trashed
    ? [...baseColumns, { label: 'Eliminado el', key: 'deleted_at' }]
    : baseColumns
)

// Acciones según trashed o no
const actions = computed(() =>
  props.trashed
    ? [
        {
          label: 'Restaurar',
          class: 'text-green-500 hover:text-green-700 transition',
          actionName: 'restore',
        },
      ]
    : [
        {
          label: 'Ver Equipos',
          class: 'text-blue-400 hover:text-blue-600 transition',
          actionName: 'verEquipos',
        },
        {
          label: 'Editar',
          class: 'text-blue-400 hover:text-blue-600 transition',
          actionName: 'edit',
        },
        {
          label: 'Eliminar',
          class: 'text-red-500 hover:text-red-700 transition',
          actionName: 'delete',
        },
      ]
)

// Botones arriba, según trashed
const buttons = computed(() =>
  props.trashed
    ? [
        {
          label: '← Volver a activos',
          href: '/admin/temporada-competencias',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
    : [
        {
          label: '+ Crear Temporada Competencia',
          href: '/admin/temporada-competencias/create',
          colorClass: 'bg-red-600 hover:bg-red-700',
        },
        {
          label: '🗑️ Ver Eliminadas',
          href: '/admin/temporada-competencias/trashed',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
)

// Manejo de acciones
function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/temporada-competencias/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la temporada competencia "${row.nombre}"?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) router.delete(`/admin/temporada-competencias/${row.id}`)
    })
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la temporada competencia "${row.nombre}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) router.post(`/admin/temporada-competencias/${row.id}/restore`)
    })
  } else if (actionName === 'verEquipos') {
    router.get(`/admin/temporada-competencias/${row.id}`)
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>
      {{ trashed ? 'Temporada Competencias Eliminadas' : 'Temporada Competencias' }}
    </template>

    <div class="p-6 space-y-6">
      <div
        v-if="localSuccess"
        class="p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ localSuccess }}
      </div>

      <div
        class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6"
      >
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Temporada Competencias Eliminadas' : 'Temporada Competencias' }}
        </h1>

        <ActionButtons :buttons="buttons" />
      </div>

      <BaseTable
        :columns="columns"
        :rows="temporadaCompetencias.data"
        :meta="temporadaCompetencias.meta"
        :links="temporadaCompetencias.links"
        :actions="actions"
        v-model:filterText="search"
        v-model:perPage="perPage"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
