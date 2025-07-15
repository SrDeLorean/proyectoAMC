<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  equipos: Object, // paginación con: data, links, meta
  filters: Object, // { search: '', perPage: number }
  success: String,
  trashed: Boolean,
})

// Columns
const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' },
  { label: 'Descripción', key: 'descripcion' },
  { label: 'Color Primario', key: 'color_primario' },
  { label: 'Color Secundario', key: 'color_secundario' },
  { label: 'Logo', key: 'logo' },
  { label: 'Formación', key: 'formacion.nombre' },
  { label: 'Instagram', key: 'instagram' },
  { label: 'Twitch', key: 'twitch' },
  { label: 'YouTube', key: 'youtube' },
  { label: 'Propietario', key: 'propietario.name' },
  { label: 'Entrenador', key: 'entrenador.name' },
]

// Actions
const actions = computed(() => {
  return props.trashed
    ? [{ label: 'Restaurar', actionName: 'restore' }]
    : [
        { label: 'Editar', actionName: 'edit' },
        { label: 'Eliminar', actionName: 'delete' },
      ]
})

// Buttons
const buttons = computed(() => {
  return props.trashed
    ? [{ label: '← Volver a activos', href: '/admin/equipos', colorClass: 'bg-gray-700 hover:bg-gray-800' }]
    : [
        { label: '+ Crear Equipo', href: '/admin/equipos/create', colorClass: 'bg-red-600 hover:bg-red-700' },
        { label: '🗑️ Equipos eliminados', href: '/admin/equipos/trashed', colorClass: 'bg-gray-700 hover:bg-gray-800' },
      ]
})

// Estados reactivos para filtros
const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

// Watch para búsquedas en backend con debounce simple
let timeout = null
watch([search, perPage], ([newSearch, newPerPage]) => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(
      '/admin/equipos',
      { search: newSearch, perPage: newPerPage },
      { preserveState: true, replace: true }
    )
  }, 300)
})

// Actions handler
function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/equipos/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar al equipo ${row.nombre}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        router.delete(`/admin/equipos/${row.id}`)
      }
    })
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar al equipo ${row.nombre}?`,
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
    <template #title>Equipos</template>

    <div class="p-6">
      <div
        v-if="success"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ success }}
      </div>

      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Equipos eliminados' : 'Lista de equipos' }}
        </h1>

        <ActionButtons :buttons="buttons" />
      </div>

      <BaseTable
        :columns="columns"
        :rows="equipos.data"
        :meta="equipos.meta"
        :links="equipos.links"
        :actions="actions"
        v-model:filterText="search"
        v-model:perPage="perPage"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
