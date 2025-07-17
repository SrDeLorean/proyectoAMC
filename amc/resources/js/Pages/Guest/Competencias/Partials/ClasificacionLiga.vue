<script setup>
const props = defineProps({ tabla: Array })
</script>

<template>
  <div class="overflow-x-auto rounded-lg border border-red-700 shadow-lg bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900">
    <table v-if="tabla.length > 0" class="min-w-full border-collapse table-auto text-white">
      <thead
        class="bg-gradient-to-r from-red-700 via-red-600 to-red-700 text-red-100 select-none shadow-md"
      >
        <tr>
          <th class="p-3 text-center font-semibold tracking-wider w-10">#</th>
          <th class="p-3 text-left font-semibold tracking-wider min-w-[200px]">Equipo</th>
          <th class="p-3 text-center font-semibold tracking-wider w-10">P</th>
          <th class="p-3 text-center font-semibold tracking-wider w-10">M</th>
          <th class="p-3 text-center font-semibold tracking-wider w-10">V</th>
          <th class="p-3 text-center font-semibold tracking-wider w-10">E</th>
          <th class="p-3 text-center font-semibold tracking-wider w-10">D</th>
          <th class="p-3 text-center font-semibold tracking-wider w-12">GP</th>
          <th class="p-3 text-center font-semibold tracking-wider w-12">GC</th>
          <th class="p-3 text-center font-semibold tracking-wider w-12">DG</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(fila, index) in tabla"
          :key="fila.id"
          class="border-b border-gray-700 cursor-pointer transition hover:bg-gray-700/60"
          :class="index % 2 === 0 ? 'bg-gray-800/70' : 'bg-gray-900/70'"
        >
          <td class="p-3 text-center font-mono font-semibold">{{ index + 1 }}</td>
          <td class="p-3 font-medium text-left flex items-center gap-3 min-w-[200px]">
            <img
              v-if="fila.equipo?.logo"
              :src="fila.equipo.logo.startsWith('/') ? fila.equipo.logo : '/' + fila.equipo.logo"
              alt="Logo equipo"
              class="w-7 h-7 object-contain rounded-md border border-red-600 shadow"
            />
            <span>{{ fila.equipo?.nombre || 'N/D' }}</span>
          </td>
          <td class="p-3 text-center font-mono">{{ fila.puntos }}</td>
          <td class="p-3 text-center font-mono">{{ fila.partidos_jugados }}</td>
          <td class="p-3 text-center font-mono">{{ fila.victorias }}</td>
          <td class="p-3 text-center font-mono">{{ fila.empates }}</td>
          <td class="p-3 text-center font-mono">{{ fila.derrotas }}</td>
          <td class="p-3 text-center font-mono">{{ fila.goles_a_favor }}</td>
          <td class="p-3 text-center font-mono">{{ fila.goles_en_contra }}</td>
          <td
            :class="[
              'p-3 text-center font-mono font-semibold',
              fila.diferencia_goles > 0 ? 'text-green-400' :
              fila.diferencia_goles < 0 ? 'text-red-400' : 'text-gray-400'
            ]"
          >
            {{ fila.diferencia_goles }}
          </td>
        </tr>
      </tbody>
    </table>

    <div
      v-else
      class="p-6 text-center text-gray-400 font-semibold select-none"
    >
      No se ha generado el torneo aún.
    </div>
  </div>
</template>
