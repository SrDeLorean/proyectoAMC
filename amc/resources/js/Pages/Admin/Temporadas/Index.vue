<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  temporadas: Object,  // lista paginada u objeto con data
  success: String,
  trashed: Boolean
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' },
  { label: 'Fecha Inicio', key: 'fecha_inicio' },
  { label: 'Fecha Fin', key: 'fecha_fin' },
  ...(props.trashed ? [{ label: 'Eliminado el', key: 'deleted_at' }] : [])
]

const actions = props.trashed
  ? [
      {
        label: 'Restaurar',
        class: 'text-green-500 hover:text-green-700 transition',
        actionName: 'restore'
      },
      {
        label: 'Eliminar Definitivo',
        class: 'text-red-600 hover:text-red-800 transition',
        actionName: 'forceDelete'
      }
    ]
  : [
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

const buttons = props.trashed
  ? [
      {
        label: '← Volver a Temporadas Activas',
        href: '/admin/temporadas',
        colorClass: 'bg-gray-700 hover:bg-gray-800',
        title: 'Temporadas activas'
      }
    ]
  : [
      {
        label: '+ Crear Temporada',
        href: '/admin/temporadas/create',
        colorClass: 'bg-red-600 hover:bg-red-700'
      },
      {
        label: '🗑️ Ver Temporadas Eliminadas',
        href: '/admin/temporadas/trashed',
        colorClass: 'bg-gray-700 hover:bg-gray-800',
        title: 'Temporadas eliminadas'
      }
    ]

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/admin/temporadas/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la temporada ${row.nombre}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/temporadas/${row.id}`)
      }
    })
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la temporada ${row.nombre}?`,
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
  } else if (actionName === 'forceDelete') {
    Swal.fire({
      title: `¿Eliminar definitivamente la temporada ${row.nombre}?`,
      text: 'Esta acción es irreversible',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#b91c1c',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar definitivamente',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/temporadas/${row.id}/force-delete`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Lista de temporadas</template>

    <div class="p-6">
      <div
        class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4"
      >
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Temporadas Eliminadas' : 'Temporadas Activas' }}
        </h1>

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
        :rows="temporadas.data ?? temporadas"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
