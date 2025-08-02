<script setup>
import { router, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  equipos: Array,
  success: String,
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' },
  { label: 'Color Primario', key: 'color_primario' },
  { label: 'Color Secundario', key: 'color_secundario' },
  { label: 'Eliminado el', key: 'deleted_at' },
  { label: 'Acciones', key: 'actions', sortable: false }
]

function makeButtons(row) {
  return [
    {
      label: 'Restaurar',
      colorClass: 'bg-green-500 hover:bg-green-700',
      onClick: () => onTableAction({ actionName: 'restore', row }),
      icon: '♻️',
    }
  ]
}

function onTableAction({ actionName, row }) {
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar el equipo "${row.nombre ?? 'desconocido'}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        router.post(`/admin/equipos/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Equipos Eliminados</template>

    <div class="p-6">
      <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Equipos Eliminados</h1>
        <Link
          href="/admin/equipos"
          class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
        >
          ← Volver a Equipos Activos
        </Link>
      </div>

      <div
        v-if="success"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ success }}
      </div>

      <BaseTable :columns="columns" :rows="equipos">
        <template #cell-color_primario="{ row }">
          <div
            :style="{ backgroundColor: row.color_primario }"
            class="w-6 h-6 rounded border border-gray-400"
            title="Color Primario"
          ></div>
        </template>

        <template #cell-color_secundario="{ row }">
          <div
            :style="{ backgroundColor: row.color_secundario }"
            class="w-6 h-6 rounded border border-gray-400"
            title="Color Secundario"
          ></div>
        </template>

        <template #cell-actions="{ row }">
          <ActionButtons :buttons="makeButtons(row)" />
        </template>
      </BaseTable>
    </div>
  </AdminLayout>
</template>
