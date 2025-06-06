<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/BaseTable.vue'

const props = defineProps({
  users: Array,
  success: String,
})

// Ref local para controlar el mensaje que se muestra
const localSuccess = ref(props.success)

// Cada vez que cambia props.success, actualizamos localSuccess y programamos limpiar después de 3s
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

function restoreUser(user) {
  Swal.fire({
    title: `¿Restaurar a ${user.name}?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#22c55e',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Sí, restaurar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(`/admin/usuarios/${user.id}/restore`)
    }
  })
}
</script>

<template>
  <AdminLayout>
    <template #title>Usuarios</template>

    <div class="p-6">
      <!-- Alerta de éxito que desaparece sola a los 3 segundos -->
      <div
        v-if="localSuccess"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ localSuccess }}
      </div>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Usuarios</h1>
        <div class="flex gap-4">
          <a
            href="/admin/usuarios/create"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
          >
            + Crear Usuario
          </a>
          <a
            href="/admin/usuarios/trashed"
            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            title="Usuarios eliminados"
          >
            🗑️ Usuarios eliminados
          </a>
        </div>
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
