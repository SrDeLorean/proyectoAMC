<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  competencias: Array,
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

function onTableAction({ actionName, row }) {
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
    <template #title>Competencias Eliminadas</template>

    <div class="p-6">
      <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Competencias Eliminadas</h1>
        <a
          href="/competencias"
          class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
        >
          ← Volver a Competencias Activas
        </a>
      </div>

      <!-- Mensaje flash -->
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <BaseTable
        :columns="columns"
        :rows="competencias"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
