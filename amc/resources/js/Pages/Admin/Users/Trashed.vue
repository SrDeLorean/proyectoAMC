<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'  // Importa el componente

const props = defineProps({
  users: Array,
  success: String
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'ID EA', key: 'id_ea' },
  { label: 'Foto', key: 'foto' },
  { label: 'Nombre', key: 'name' },
  { label: 'Email', key: 'email' },
  { label: 'Rol', key: 'role' },
  { label: 'Eliminado el', key: 'deleted_at' }
]

const actions = [
  {
    label: 'Restaurar',
    class: 'text-green-500 hover:text-green-700 transition',
    actionName: 'restore'
  }
]

// Botones para la parte superior derecha
const buttons = [
  {
    label: '← Volver a Usuarios Activos',
    href: '/admin/usuarios',
    colorClass: 'bg-gray-700 hover:bg-gray-800',
    title: 'Usuarios activos'
  }
]

function onTableAction({ actionName, row }) {
  if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar a ${row.name}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/admin/usuarios/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Usuarios Eliminados</template>
    <div class="p-6">
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-white">Usuarios Eliminados</h1>
        <!-- Aquí usamos ActionButtons -->
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
        :rows="users"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
