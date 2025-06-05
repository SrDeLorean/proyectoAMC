<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/BaseTable.vue'

const props = defineProps({
  plantillas: Object,
  success: String
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Equipo', key: 'equipo.nombre' },
  { label: 'Jugador', key: 'jugador.name' },
  { label: 'Posición', key: 'posicion' },
  { label: 'Número', key: 'numero' },
]

const actions = [
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

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/plantillas/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar plantilla #${row.id}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/plantillas/${row.id}`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Plantillas</template>
    <div class="p-6">
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Plantillas</h1>
        <div class="flex gap-4">
          <a
            href="/admin/plantillas/create"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
          >
            + Crear Plantilla
          </a>
          <a
            href="/admin/plantillas/trashed"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            title="Plantillas eliminadas"
          >
            🗑️ Plantillas eliminadas
          </a>
        </div>
      </div>

      <BaseTable
        :columns="columns"
        :rows="plantillas.data"
        :actions="actions"
        @action="onTableAction"
      />

      <!-- Paginación básica -->
      <div class="mt-4 flex justify-center text-white">
        <button
          v-if="plantillas.prev_page_url"
          @click.prevent="router.get(plantillas.prev_page_url)"
          class="mr-2 px-3 py-1 bg-gray-700 rounded hover:bg-gray-600"
        >
          Anterior
        </button>
        <button
          v-if="plantillas.next_page_url"
          @click.prevent="router.get(plantillas.next_page_url)"
          class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600"
        >
          Siguiente
        </button>
      </div>
    </div>
  </AdminLayout>
</template>
