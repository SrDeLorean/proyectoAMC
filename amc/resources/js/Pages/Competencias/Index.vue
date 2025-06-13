<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  competencias: Object,  // paginadas
  success: String,
  trashed: Boolean     // true si estamos en /trashed
})

// Columnas base para competencias (id y nombre)
const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' }
]

// Si está en papelera, agregamos columna de eliminado
const columns = computed(() =>
  props.trashed
    ? [...baseColumns, { label: 'Eliminado el', key: 'deleted_at' }]
    : baseColumns
)

// Acciones: editar y eliminar, o restaurar si está en papelera
const actions = computed(() => {
  return props.trashed
    ? [
        {
          label: 'Restaurar',
          class: 'text-green-500 hover:text-green-700 transition',
          actionName: 'restore'
        }
      ]
    : [
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
})

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/competencias/${row.id}/edit`)
  }

  if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la competencia "${row.nombre}"?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/competencias/${row.id}`)
      }
    })
  }

  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la competencia "${row.nombre}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/competencias/${row.id}/restore`)
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
      <!-- Mensaje de éxito -->
      <div v-if="success" class="p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <!-- Encabezado + Botones -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Competencias Eliminadas' : 'Competencias' }}
        </h1>
        <div class="flex gap-3">
          <template v-if="trashed">
            <a
              href="/competencias"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            >
              ← Volver a activos
            </a>
          </template>
          <template v-else>
            <a
              href="/competencias/create"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
            >
              + Crear Competencia
            </a>
            <a
              href="/competencias/trashed"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            >
              🗑️ Ver Eliminadas
            </a>
          </template>
        </div>
      </div>

      <!-- Tabla -->
      <BaseTable
        :columns="columns"
        :rows="competencias"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
