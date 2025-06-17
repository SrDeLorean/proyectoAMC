<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'  // importa el componente

const props = defineProps({
  users: Array,
  success: String,
})

const localSuccess = ref(props.success)

watch(
  () => props.success,
  (newVal) => {
    localSuccess.value = newVal

    if (newVal) {
      setTimeout(() => {
        localSuccess.value = ''
      }, 3000)
    }
  }
)

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'ID EA', key: 'id_ea' },
  { label: 'Foto', key: 'foto' },
  { label: 'Nombre', key: 'name' },
  { label: 'Email', key: 'email' },
  { label: 'Rol', key: 'role' },
]

const actions = [
  {
    label: 'Editar',
    class: 'text-blue-400 hover:text-blue-600 transition',
    actionName: 'edit',
  },
  {
    label: 'Eliminar',
    class: 'text-red-500 hover:text-red-700 transition',
    actionName: 'delete',
  },
]

// Aquí defines los botones que quieres mostrar con el nuevo componente
const buttons = [
  {
    label: '+ Crear Usuario',
    href: '/admin/usuarios/create',
    colorClass: 'bg-red-600 hover:bg-red-700',
  },
  {
    label: '🗑️ Usuarios eliminados',
    href: '/admin/usuarios/trashed',
    colorClass: 'bg-gray-700 hover:bg-gray-800',
    title: 'Usuarios eliminados',
  },
]

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/usuarios/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar a ${row.name}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        router.delete(`/admin/usuarios/${row.id}`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Lista de usuarios</template>

    <div class="p-6">
      <!-- Mensaje éxito -->
      <div
        v-if="localSuccess"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ localSuccess }}
      </div>

      <div
        class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4"
      >
        <h1 class="text-2xl font-bold text-white">Lista de usuarios</h1>

        <!-- Aquí usas el componente ActionButtons -->
        <ActionButtons :buttons="buttons" />
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
