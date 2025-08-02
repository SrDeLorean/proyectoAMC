<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  temporadas: Array,
  success: String
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' },
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
    label: '← Volver a Temporadas Activas',
    href: '/admin/temporadas',
    colorClass: 'bg-gray-700 hover:bg-gray-800',
    title: 'Temporadas activas'
  }
]

function onTableAction({ actionName, row }) {
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la temporada "${row.nombre}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/admin/temporadas/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Temporadas Eliminadas</template>
    <div class="p-6">
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-white">Temporadas Eliminadas</h1>
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
        :rows="temporadas"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
