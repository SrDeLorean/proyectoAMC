<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { computed, ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  competencias: Object,  // paginadas
  success: String,
  trashed: Boolean
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

const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' }
]

const columns = computed(() =>
  props.trashed
    ? [...baseColumns, { label: 'Eliminado el', key: 'deleted_at' }]
    : baseColumns
)

const actions = computed(() =>
  props.trashed
    ? [
        {
          label: 'Restaurar',
          class: 'text-green-500 hover:text-green-700 transition',
          actionName: 'restore'
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
)

const buttons = computed(() =>
  props.trashed
    ? [
        {
          label: '← Volver a activos',
          href: '/admin/temporada-competencias',
          colorClass: 'bg-gray-700 hover:bg-gray-800'
        }
      ]
    : [
        {
          label: '+ Crear Temporada Competencia',
          href: '/admin/temporada-competencias/create',
          colorClass: 'bg-red-600 hover:bg-red-700'
        },
        {
          label: '🗑️ Ver Eliminadas',
          href: '/admin/temporada-competencias/trashed',
          colorClass: 'bg-gray-700 hover:bg-gray-800'
        }
      ]
)

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/competencias/${row.id}/edit`)
  }

  if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la competencia "${row.nombre}"?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/admin/competencias/${row.id}`)
      }
    })
  }

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
        router.post(`/admin/competencias/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>
      {{ trashed ? 'Competencias Eliminadas' : 'Competencias' }}
    </template>

    <div class="p-6 space-y-6">
      <!-- Mensaje flash -->
      <div
        v-if="localSuccess"
        class="p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ localSuccess }}
      </div>

      <!-- Título + botones -->
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Competencias Eliminadas' : 'Competencias' }}
        </h1>
        <ActionButtons :buttons="buttons" />
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
