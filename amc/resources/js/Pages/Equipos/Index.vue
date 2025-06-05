<script setup>
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/BaseTable.vue'
import { computed } from 'vue'

const props = defineProps({
  equipos: Array,
  success: String,
  trashed: Boolean
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Nombre', key: 'nombre' },
  { label: 'Descripción', key: 'descripcion' },
  { label: 'Color Primario', key: 'color_primario' },
  { label: 'Color Secundario', key: 'color_secundario' },
  { label: 'Logo', key: 'logo' },
  { label: 'Formación', key: 'formacion.nombre' }, // relación
  { label: 'Instagram', key: 'instagram' },
  { label: 'Twitch', key: 'twitch' },
  { label: 'YouTube', key: 'youtube' }
]

const actions = computed(() => {
  if (props.trashed) {
    return [
      {
        label: 'Restaurar',
        class: 'text-green-400 hover:text-green-600 transition',
        actionName: 'restore'
      }
    ]
  } else {
    return [
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
  }
})

function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.get(`/equipos/${row.id}/edit`)
  } else if (actionName === 'delete') {
    Swal.fire({
      title: `¿Eliminar al equipo ${row.nombre}?`,
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(`/equipos/${row.id}`)
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
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        router.post(`/equipos/${row.id}/restore`)
      }
    })
  }
}
</script>

<template>
  <AdminLayout>
    <template #title>Equipos</template>
    <div class="p-6">
      <!-- Mensaje flash -->
      <div v-if="success" class="mb-4 p-3 bg-green-600 text-white rounded shadow">
        {{ success }}
      </div>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Equipos eliminados' : 'Equipos' }}
        </h1>
        <div class="flex gap-4">
          <template v-if="!trashed">
            <a
              href="/equipos/create"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition"
            >
              + Crear Equipo
            </a>
            <a
              href="/equipos/trashed"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            >
              🗑️ Equipos eliminados
            </a>
          </template>
          <template v-else>
            <a
              href="/equipos"
              class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded transition"
            >
              ← Volver a activos
            </a>
          </template>
        </div>
      </div>

      <BaseTable
        :columns="columns"
        :rows="equipos"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>

