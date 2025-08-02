<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  plantillas: Object, // paginado
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

const buttons = [
  {
    label: '← Volver a Plantillas Activas',
    href: '/admin/plantillas',
    colorClass: 'bg-gray-700 hover:bg-gray-800',
    title: 'Plantillas activas'
  }
]

function onTableAction({ actionName, row }) {
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la plantilla del jugador ${row.jugador?.name || 'desconocido'}?`,
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
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-white">Plantillas Eliminadas</h1>
        <ActionButtons :buttons="buttons" />
      </div>

      <div
        v-if="success"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
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
            v-if="row.jugador?.foto"
            :src="row.jugador.foto"
            alt="Foto jugador"
            class="w-10 h-10 rounded-full object-cover"
          />
          <div v-else class="w-10 h-10 bg-gray-600 rounded-full"></div>
        </template>
      </BaseTable>
    </div>
  </AdminLayout>
</template>
