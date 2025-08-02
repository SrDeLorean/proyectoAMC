<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  traspasos: Array,
})

const form = useForm()

function aprobar(id) {
  form.post(route('admin.traspasos.aprobar', id), {
    preserveScroll: true,
  })
}

function cancelar(id) {
  form.post(route('admin.traspasos.cancelar', id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <AdminLayout title="Todas las Solicitudes de Traspaso">
    <template #header>
      <h1 class="text-2xl font-bold mb-6 text-white">Todas las Solicitudes de Traspaso</h1>
    </template>

    <div class="bg-gray-900 shadow rounded-lg p-6 overflow-x-auto text-gray-200">
      <table class="min-w-full divide-y divide-gray-700">
        <thead class="bg-gray-800">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-semibold">Jugador</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Equipo Origen</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Equipo Destino</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Estado</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Fecha</th>
            <th class="px-4 py-2 text-left text-sm font-semibold">Motivo</th>
            <th class="px-4 py-2 text-center text-sm font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <tr v-for="t in traspasos" :key="t.id">
            <td class="px-4 py-2">{{ t.jugador.name }}</td>
            <td class="px-4 py-2">{{ t.equipo_origen?.nombre || 'Libre' }}</td>
            <td class="px-4 py-2">{{ t.equipo_destino.nombre }}</td>
            <td class="px-4 py-2 capitalize">{{ t.estado }}</td>
            <td class="px-4 py-2">{{ new Date(t.fecha_traspaso).toLocaleDateString() }}</td>
            <td class="px-4 py-2">{{ t.motivo }}</td>
            <td class="px-4 py-2 text-center space-x-2">
              <button
                v-if="t.estado === 'aceptado'"
                @click="aprobar(t.id)"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
              >
                Aprobar
              </button>

              <button
                v-if="t.estado !== 'cancelado' && t.estado !== 'aprobado'"
                @click="cancelar(t.id)"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
              >
                Cancelar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>
