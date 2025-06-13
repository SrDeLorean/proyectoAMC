<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

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

      <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Usuarios Eliminados</h1>
        <a
          href="/admin/usuarios"
          class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
        >
          ← Volver a Usuarios Activos
        </a>
      </div>

      <!-- Mensaje flash -->
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
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
