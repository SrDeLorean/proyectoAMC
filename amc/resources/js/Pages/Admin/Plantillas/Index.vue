<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/BaseTable.vue'
import { computed } from 'vue'

const props = defineProps({
  plantillas: Object,
  success: String,
  trashed: Boolean
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Equipo', key: 'equipo.nombre' },
  { label: 'Foto', key: 'jugador.foto', isImage: true }, // columna para la foto
  { label: 'Jugador', key: 'jugador.name' },
  { label: 'Posición', key: 'posicion' },
  { label: 'Número', key: 'numero' }
]

const actions = computed(() => {
  if (props.trashed) {
    return [
      {
        label: 'Restaurar',
        class: 'text-green-400 hover:text-green-600 transition',
        actionName: 'restore'
      }
    ]
  } else {
    return [
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
  }
})

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/plantillas/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la plantilla del jugador ${row.jugador.name}?`,
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
  } else if (actionName === 'restore') {
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
    <template #title>
      {{ trashed ? 'Plantillas Eliminadas' : 'Plantillas' }}
    </template>

    <div class="p-6">
      <!-- Mensaje flash -->
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Plantillas eliminadas' : 'Plantillas' }}
        </h1>

        <div class="flex gap-4">
          <template v-if="!trashed">
            <a
              href="/admin/plantillas/create"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
            >
              + Crear Plantilla
            </a>
            <a
              href="/admin/plantillas/trashed"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            >
              🗑️ Plantillas eliminadas
            </a>
          </template>
          <template v-else>
            <a
              href="/admin/plantillas"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            >
              ← Volver a activos
            </a>
          </template>
        </div>
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
