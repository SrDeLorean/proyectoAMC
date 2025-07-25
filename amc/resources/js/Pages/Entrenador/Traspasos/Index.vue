<template>
  <EntrenadorLayout title="Solicitudes de Fichajes">
    <template #header>
      <h1 class="text-2xl font-bold text-gray-100 mb-6">Solicitudes de Fichajes</h1>
    </template>

    <div class="bg-gray-800 shadow rounded-lg p-6">
      <Link
        href="/entrenador/traspasos/create"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4 inline-block"
      >
        Crear nueva solicitud
      </Link>

      <table class="min-w-full divide-y divide-gray-700 rounded">
        <thead class="bg-gray-700">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-300">Jugador</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-300">Equipo Origen</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-300">Equipo Destino</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-300">Estado</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-300">Fecha</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-300">Motivo</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <tr v-for="t in traspasos" :key="t.id" class="hover:bg-gray-700">
            <td class="px-4 py-2 text-gray-200">{{ t.jugador.name }}</td>
            <td class="px-4 py-2 text-gray-200">{{ t.equipo_origen?.nombre || 'Libre' }}</td>
            <td class="px-4 py-2 text-gray-200">{{ t.equipo_destino.nombre }}</td>
            <td class="px-4 py-2 text-gray-200 capitalize">{{ t.estado }}</td>
            <td class="px-4 py-2 text-gray-200">{{ new Date(t.fecha_traspaso).toLocaleDateString() }}</td>
            <td class="px-4 py-2 text-gray-200">{{ t.motivo }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </EntrenadorLayout>
</template>

<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
  traspasos: Array,
})
</script>
