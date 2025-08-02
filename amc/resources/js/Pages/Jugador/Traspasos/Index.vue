<template>
  <JugadorLayout title="Ofertas de fichajes">
    <template #header>
      <h1 class="text-2xl font-bold text-gray-100 mb-6">Ofertas de fichajes - Jugador</h1>
    </template>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-gray-800 rounded-lg shadow-md divide-y divide-gray-700">
        <thead class="bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Equipo Origen</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Equipo Destino</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estado</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Fecha</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Motivo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <tr v-for="t in traspasos" :key="t.id" class="hover:bg-gray-700">
            <td class="px-6 py-4 whitespace-nowrap text-gray-200">{{ t.equipo_origen?.nombre || 'Libre' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-200">{{ t.equipo_destino.nombre }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-200 capitalize">{{ t.estado }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-200">{{ new Date(t.fecha_traspaso).toLocaleDateString() }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-200">{{ t.motivo }}</td>
            <td class="px-6 py-4 whitespace-nowrap space-x-2">
              <button
                v-if="t.estado === 'pendiente'"
                @click="aceptar(t.id)"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
              >
                Aceptar
              </button>
              <button
                v-if="t.estado === 'pendiente'"
                @click="rechazar(t.id)"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
              >
                Rechazar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </JugadorLayout>
</template>

<script setup>
import JugadorLayout from '@/Layouts/JugadorLayout.vue'
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  traspasos: Array,
});

function aceptar(id) {
  Inertia.post(`/jugador/traspasos/${id}/aceptar`);
}

function rechazar(id) {
  Inertia.post(`/jugador/traspasos/${id}/rechazar`);
}
</script>
