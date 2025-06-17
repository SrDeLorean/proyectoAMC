<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { computed, ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  plantillas: Object,
  success: String,
  trashed: Boolean,
})

const localSuccess = ref(props.success)
watch(() => props.success, val => {
  localSuccess.value = val
  if (val) {
    setTimeout(() => (localSuccess.value = ''), 3000)
  }
})

const baseColumns = [
  { label: 'ID', key: 'id' },
  { label: 'Equipo', key: 'equipo.nombre' },
  { label: 'Foto', key: 'jugador.foto', isImage: true },
  { label: 'Jugador', key: 'jugador.name' },
  { label: 'Posición', key: 'posicion' },
  { label: 'Número', key: 'numero' },
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
          actionName: 'restore',
        },
      ]
    : [
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
)

const buttons = computed(() =>
  props.trashed
    ? [
        {
          label: '← Volver a activos',
          href: '/admin/plantillas',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
    : [
        {
          label: '+ Crear Plantilla',
          href: '/admin/plantillas/create',
          colorClass: 'bg-red-600 hover:bg-red-700',
        },
        {
          label: '🗑️ Ver Eliminadas',
          href: '/admin/plantillas/trashed',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
)

function onTableAction({ actionName, row }) {
  const jugador = row.jugador?.name || 'jugador desconocido'

  if (actionName === 'edit') {
    router.get(`/admin/plantillas/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar la plantilla del jugador ${jugador}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) router.delete(`/admin/plantillas/${row.id}`)
    })
  } else if (actionName === 'restore') {
    Swal.fire({
      title: `¿Restaurar la plantilla del jugador ${jugador}?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, restaurar',
      cancelButtonText: 'Cancelar',
    }).then(result => {
      if (result.isConfirmed) router.post(`/admin/plantillas/${row.id}/restore`)
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>
      {{ trashed ? 'Plantillas Eliminadas' : 'Plantillas' }}
    </template>

    <div class="p-6 space-y-6">
      <div
        v-if="localSuccess"
        class="p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ localSuccess }}
      </div>

      <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Plantillas Eliminadas' : 'Plantillas' }}
        </h1>

        <ActionButtons :buttons="buttons" />
      </div>

      <BaseTable
        :columns="columns"
        :rows="plantillas.data"
        :actions="actions"
        @action="onTableAction"
      >
        <template #cell-jugador\.foto="{ row }">
          <img
            v-if="row.jugador?.foto"
            :src="row.jugador.foto"
            alt="Foto jugador"
            class="w-10 h-10 rounded-full object-cover"
          />
          <div v-else class="w-10 h-10 bg-gray-600 rounded-full"></div>
        </template>
      </BaseTable>
    </div>
  </AdminLayout>
</template>
