<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import ActionButtons from '@/Components/ActionButtons.vue'

const props = defineProps({
  equipos: Array,
  success: String,
  trashed: Boolean,
})

const localSuccess = ref(props.success)

watch(() => props.success, (newVal) => {
  localSuccess.value = newVal
  if (newVal) {
    setTimeout(() => {
      localSuccess.value = ''
    }, 3000)
  }
})

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
  { label: 'Acciones', key: 'actions', sortable: false },
]

const actions = computed(() => {
  return props.trashed
    ? [
        {
          label: 'Restaurar',
          class: 'text-green-400 hover:text-green-600 transition',
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
})

const buttons = computed(() => {
  return props.trashed
    ? [
        {
          label: '← Volver a activos',
          href: '/admin/equipos',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
    : [
        {
          label: '+ Crear Equipo',
          href: '/admin/equipos/create',
          colorClass: 'bg-red-600 hover:bg-red-700',
        },
        {
          label: '🗑️ Equipos eliminados',
          href: '/admin/equipos/trashed',
          colorClass: 'bg-gray-700 hover:bg-gray-800',
        },
      ]
})

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
      <!-- Mensaje flash -->
      <div
        v-if="localSuccess"
        class="mb-4 p-3 bg-green-600 text-white rounded shadow transition-opacity duration-500"
      >
        {{ localSuccess }}
      </div>

      <!-- Encabezado: título + botones -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-white">
          {{ trashed ? 'Equipos eliminados' : 'Lista de equipos' }}
        </h1>

        <ActionButtons :buttons="buttons" />
      </div>

      <!-- Tabla de datos -->
      <BaseTable
        :columns="columns"
        :rows="equipos"
        :actions="actions"
        @action="onTableAction"
      />
    </div>
  </AdminLayout>
</template>
