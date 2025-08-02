<script setup>
const props = defineProps({
  equipos: {
    type: Array,
    default: () => []
  },
  participantes: {
    type: Array,
    default: () => []
  }
})

function getParticipante(id) {
  return props.participantes.find(p => p.id === id)
}

// Luminancia para decidir color texto
function getLuminance(hexColor) {
  if (!hexColor) return 0
  const hex = hexColor.replace('#', '')
  const r = parseInt(hex.substring(0, 2), 16) / 255
  const g = parseInt(hex.substring(2, 4), 16) / 255
  const b = parseInt(hex.substring(4, 6), 16) / 255

  const RsRGB = r <= 0.03928 ? r / 12.92 : ((r + 0.055) / 1.055) ** 2.4
  const GsRGB = g <= 0.03928 ? g / 12.92 : ((g + 0.055) / 1.055) ** 2.4
  const BsRGB = b <= 0.03928 ? b / 12.92 : ((b + 0.055) / 1.055) ** 2.4

  return 0.2126 * RsRGB + 0.7152 * GsRGB + 0.0722 * BsRGB
}

function isDarkColor(hexColor) {
  return getLuminance(hexColor) < 0.5
}

function esParticipante(id) {
  return props.participantes.some(p => p.id === id)
}
</script>

<template>
  <div class="overflow-x-auto rounded-lg shadow-md border border-gray-700">
    <table class="w-full min-w-[700px] text-sm text-center">
      <thead class="bg-gray-900 uppercase text-xs text-gray-400 select-none">
        <tr>
          <th class="px-3 py-3 whitespace-nowrap">#</th>
          <th class="px-4 py-3 text-left">Equipo</th>
          <th class="px-3 py-3 whitespace-nowrap">P</th>
          <th class="px-3 py-3 whitespace-nowrap hidden sm:table-cell">J</th>
          <th class="px-3 py-3 whitespace-nowrap">G</th>
          <th class="px-3 py-3 whitespace-nowrap hidden sm:table-cell">E</th>
          <th class="px-3 py-3 whitespace-nowrap hidden sm:table-cell">P</th>
          <th class="px-3 py-3 whitespace-nowrap">GF</th>
          <th class="px-3 py-3 whitespace-nowrap hidden sm:table-cell">GC</th>
          <th class="px-3 py-3 whitespace-nowrap">DG</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(equipo, index) in equipos"
          :key="equipo.id"
          :style="{
            backgroundColor: esParticipante(equipo.equipo?.id)
              ? (equipo.equipo?.color_primario || getParticipante(equipo.equipo?.id)?.color_primario || '#cccccc')
              : undefined
          }"
          :class="{
            'bg-gray-800': !esParticipante(equipo.equipo?.id),
            'text-white': esParticipante(equipo.equipo?.id) && isDarkColor(equipo.equipo?.color_primario || getParticipante(equipo.equipo?.id)?.color_primario || '#cccccc'),
            'text-gray-900': esParticipante(equipo.equipo?.id) && !isDarkColor(equipo.equipo?.color_primario || getParticipante(equipo.equipo?.id)?.color_primario || '#cccccc')
          }"
        >
          <td class="px-3 py-2 font-bold whitespace-nowrap">{{ index + 1 }}</td>

          <td class="px-4 py-2 text-left truncate max-w-xs flex items-center gap-2" :title="equipo.equipo?.nombre">
            <img
              v-if="equipo.equipo?.logo"
              :src="equipo.equipo.logo.startsWith('/') ? equipo.equipo.logo : '/' + equipo.equipo.logo"
              alt="Logo"
              class="w-6 h-6 rounded-full object-contain ring-1 ring-white/30 bg-white/10"
            />
            <span class="truncate">{{ equipo.equipo?.nombre }}</span>
          </td>

          <td
            class="px-3 py-2 font-semibold whitespace-nowrap"
            :class="esParticipante(equipo.equipo?.id)
              ? (isDarkColor(equipo.equipo?.color_primario || getParticipante(equipo.equipo?.id)?.color_primario || '#cccccc') ? 'text-white' : 'text-gray-900')
              : 'text-red-500'"
          >
            {{ equipo.puntos }}
          </td>
          <td class="px-3 py-2 whitespace-nowrap hidden sm:table-cell">{{ equipo.partidos_jugados }}</td>
          <td class="px-3 py-2 whitespace-nowrap">{{ equipo.victorias }}</td>
          <td class="px-3 py-2 whitespace-nowrap hidden sm:table-cell">{{ equipo.empates }}</td>
          <td class="px-3 py-2 whitespace-nowrap hidden sm:table-cell">{{ equipo.derrotas }}</td>
          <td class="px-3 py-2 whitespace-nowrap">{{ equipo.goles_a_favor }}</td>
          <td class="px-3 py-2 whitespace-nowrap hidden sm:table-cell">{{ equipo.goles_en_contra }}</td>
          <td
            class="px-3 py-2 font-semibold whitespace-nowrap"
            :class="{
              'text-green-400': equipo.diferencia_goles > 0,
              'text-red-400': equipo.diferencia_goles < 0,
              'text-white': equipo.diferencia_goles === 0
            }"
          >
            {{ equipo.diferencia_goles }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
