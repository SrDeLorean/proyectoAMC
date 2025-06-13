<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  plantillas: Object,  // se espera objeto paginado
  success: String
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Equipo', key: 'equipo.nombre' },
  { label: 'Foto', key: 'jugador.foto', isImage: true },
  { label: 'Jugador', key: 'jugador.name' },
  { label: 'Posición', key: 'posicion' },
  { label: 'Número', key: 'numero' },
  { label: 'Eliminado el', key: 'deleted_at' }
]

const actions = [
  {
    label: 'Restaurar',
    class: 'text-green-500 hover:text-green-700 transition',
    actionName: 'restore'
  }
]

function onTableAction({ actionName, row }) {
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la plantilla del jugador ${row.jugador.name}?`,
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
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Plantillas Eliminadas</template>
    <div class="p-6">

      <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Plantillas Eliminadas</h1>
        <a
          href="/admin/plantillas"
          class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
        >
          ← Volver a Plantillas Activas
        </a>
      </div>

      <!-- Mensaje flash -->
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <BaseTable
        :columns="columns"
        :rows="plantillas.data"
        :actions="actions"
        @action="onTableAction"
      >
        <template #cell-jugador.foto="{ row }">
          <img
            :src="row.jugador.foto"
            alt="Foto jugador"
            class="w-10 h-10 rounded-full object-cover"
          />
        </template>
      </BaseTable>

    </div>
  </AdminLayout>
</template>
