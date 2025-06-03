<script setup>
const props = defineProps({
  nombreLiga: { type: String, required: true },
  equipos: { type: Array, required: true }, // Array con objetos: { nombre, logo, puntos, pj, gf, gc }
});
</script>

<template>
  <div class="bg-gray-950 text-white px-4 py-8 max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center text-red-600">
      Estadísticas por Equipo - {{ nombreLiga }}
    </h2>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border-collapse">
        <thead>
          <tr class="bg-gray-800 text-left text-sm uppercase text-gray-400">
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Equipo</th>
            <th class="px-4 py-2 text-center">PJ</th>
            <th class="px-4 py-2 text-center">GF</th>
            <th class="px-4 py-2 text-center">GC</th>
            <th class="px-4 py-2 text-center">DIF</th>
            <th class="px-4 py-2 text-center">PTS</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(equipo, index) in equipos"
            :key="equipo.nombre"
            class="border-b border-gray-700 hover:bg-gray-800"
          >
            <td class="px-4 py-2">{{ index + 1 }}</td>
            <td class="px-4 py-2 flex items-center space-x-2">
              <img :src="equipo.logo" alt="logo" class="w-6 h-6 object-contain" />
              <span class="font-semibold">{{ equipo.nombre }}</span>
            </td>
            <td class="px-4 py-2 text-center">{{ equipo.pj }}</td>
            <td class="px-4 py-2 text-center">{{ equipo.gf }}</td>
            <td class="px-4 py-2 text-center">{{ equipo.gc }}</td>
            <td class="px-4 py-2 text-center">{{ equipo.gf - equipo.gc }}</td>
            <td class="px-4 py-2 text-center font-bold text-white">{{ equipo.puntos }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
