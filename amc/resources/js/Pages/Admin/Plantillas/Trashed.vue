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
    label: 'Restaurar',
    class: 'text-green-400 hover:text-green-600 transition',
    actionName: 'restore'
  },
  {
    label: 'Eliminar Definitivamente',
    class: 'text-red-600 hover:text-red-800 transition',
    actionName: 'forceDelete'
  }
]

function onTableAction({ actionName, row }) {
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar plantilla #${row.id}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/admin/plantillas/${row.id}/restore`)
      }
    })
  } else if (actionName === 'forceDelete') {
    Swal.fire({
      title: `¿Eliminar permanentemente plantilla #${row.id}?`,
      text: 'Esta acción es irreversible',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/plantillas/${row.id}/force-delete`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Plantillas Eliminadas</template>
    <div class="p-6">
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Plantillas Eliminadas</h1>
        <div>
          <a
            href="/admin/plantillas"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
          >
            ← Volver al listado
          </a>
        </div>
      </div>

      <BaseTable
        :columns="columns"
        :rows="plantillas.data"
        :actions="actions"
        @action="onTableAction"
      />

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
