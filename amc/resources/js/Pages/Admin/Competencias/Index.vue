<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { computed, ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  competencias: Object,  // paginadas
  filters: Object,
  success: String,
  trashed: Boolean,
})

// Filtros reactivos
const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

let timeout = null
watch([search, perPage], ([newSearch, newPerPage]) => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(
      '/admin/competencias',
      { search: newSearch, perPage: newPerPage },
      { preserveState: true, replace: true }
    )
  }, 300)
})

// Columnas
const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Logo', key: 'logo', isImage: true },
  { label: 'Nombre', key: 'nombre' },
]
const columns = computed(() =>
  props.trashed
    ? [...baseColumns, { label: 'Eliminado el', key: 'deleted_at' }]
    : baseColumns
)

// Acciones
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

// Botones
const buttons = computed(() =>
  props.trashed
    ? [
        {
          label: '← Volver a activas',
          href: '/admin/competencias',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
    : [
        {
          label: '+ Crear Competencia',
          href: '/admin/competencias/create',
          colorClass: 'bg-red-600 hover:bg-red-700',
        },
        {
          label: '🗑️ Ver Eliminadas',
          href: '/admin/competencias/trashed',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
)

// Acciones tabla
function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/competencias/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la competencia "${row.nombre}"?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/competencias/${row.id}`)
      }
    })
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la competencia "${row.nombre}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/admin/competencias/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>
      {{ trashed ? 'Competencias Eliminadas' : 'Competencias' }}
    </template>

    <div class="p-6 space-y-6">
      <!-- Flash éxito -->
      <div
        v-if="success"
        class="p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ success }}
      </div>

      <!-- Título + botones -->
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Competencias Eliminadas' : 'Competencias' }}
        </h1>
        <ActionButtons :buttons="buttons" />
      </div>

      <!-- Tabla -->
      <BaseTable
        :columns="columns"
        :rows="competencias.data"
        :meta="competencias.meta"
        :links="competencias.links"
        :actions="actions"
        v-model:filterText="search"
        v-model:perPage="perPage"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
