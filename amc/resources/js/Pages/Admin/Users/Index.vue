<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  users: Object,         // paginación: data, links, meta
  filters: Object,       // { search, perPage }
  success: String,
  trashed: Boolean,
})

// Columnas
const columns = [
  { label: 'ID', key: 'id' },
  { label: 'ID EA', key: 'id_ea' },
  { label: 'Foto', key: 'foto' },
  { label: 'Nombre', key: 'name' },
  { label: 'Email', key: 'email' },
  { label: 'Rol', key: 'role' },
]

// Acciones
const actions = computed(() => {
  return props.trashed
    ? [{ label: 'Restaurar', actionName: 'restore' }]
    : [
        { label: 'Editar', actionName: 'edit' },
        { label: 'Eliminar', actionName: 'delete' },
      ]
})

// Botones superiores
const buttons = computed(() => {
  return props.trashed
    ? [{ label: '← Volver a activos', href: '/admin/usuarios', colorClass: 'bg-gray-700 hover:bg-gray-800' }]
    : [
        { label: '+ Crear Usuario', href: '/admin/usuarios/create', colorClass: 'bg-red-600 hover:bg-red-700' },
        { label: '🗑️ Usuarios eliminados', href: '/admin/usuarios/trashed', colorClass: 'bg-gray-700 hover:bg-gray-800' },
      ]
})

// Filtros reactivos
const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

// Búsqueda y paginación (debounced)
let timeout = null
watch([search, perPage], ([newSearch, newPerPage]) => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(
      '/admin/usuarios',
      { search: newSearch, perPage: newPerPage },
      { preserveState: true, replace: true }
    )
  }, 300)
})

// Acción de tabla
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
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar a ${row.name}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        router.post(`/admin/usuarios/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Usuarios</template>

    <div class="p-6">
      <!-- Mensaje de éxito -->
      <div
        v-if="success"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ success }}
      </div>

      <!-- Título y botones -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Usuarios eliminados' : 'Lista de usuarios' }}
        </h1>
        <ActionButtons :buttons="buttons" />
      </div>

      <!-- Tabla -->
      <BaseTable
        :columns="columns"
        :rows="users.data"
        :meta="users.meta"
        :links="users.links"
        :actions="actions"
        v-model:filterText="search"
        v-model:perPage="perPage"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
