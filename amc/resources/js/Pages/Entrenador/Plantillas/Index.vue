<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'
import Swal from 'sweetalert2'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
  plantillas: {
    type: Array,
    default: () => [],
  },
})

// Columnas
const columns = [
  { label: 'Foto', key: 'jugador.foto', type: 'image' },
  { label: 'Jugador', key: 'jugador.name', default: '—' },
  { label: 'Equipo', key: 'equipo.nombre', default: 'Sin equipo' },
  { label: 'Rol', key: 'rol', default: '—' },
  { label: 'Posición', key: 'posicion', default: '—' },
  { label: 'Número', key: 'numero', default: '—' },
  { label: 'Titular', key: 'titular', default: 'No' },
]

// Acciones disponibles
const actions = computed(() => [
  { label: 'Editar', actionName: 'edit' },
  { label: 'Liberar', actionName: 'delete' },
])

// Manejador de acciones
function onTableAction({ actionName, row }) {
  if (actionName === 'edit') {
    router.visit(route('entrenador.plantillas.edit', row.id))
  }

  if (actionName === 'delete') {
    const nombre = row.jugador?.name || 'este jugador'

    Swal.fire({
      title: `¿Liberar a ${nombre}?`,
      text: 'Una vez liberado, solo podrá volver a tu club a través del mercado de traspasos.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e30613',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, liberar',
      cancelButtonText: 'Cancelar',
      focusCancel: true,
    }).then(result => {
      if (result.isConfirmed) {
        router.delete(route('entrenador.plantillas.destroy', row.id), {
          onSuccess: () => {
            Swal.fire({
              icon: 'success',
              title: 'Jugador liberado',
              text: `${nombre} ha sido removido correctamente.`,
              timer: 2000,
              showConfirmButton: false,
            })
          },
        })
      }
    })
  }
}
</script>

<template>
  <EntrenadorLayout>
    <template #title>Mis Jugadores</template>

    <div class="p-6 text-white">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Plantilla - Mis Jugadores</h1>
      </div>

      <BaseTable
        :columns="columns"
        :rows="plantillas"
        :actions="actions"
        @action="onTableAction"
        class="shadow-lg rounded-lg overflow-hidden"
      />
    </div>
  </EntrenadorLayout>
</template>
