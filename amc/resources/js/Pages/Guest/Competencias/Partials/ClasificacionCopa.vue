<script setup>
import { computed } from 'vue'

const props = defineProps({
  calendario: {
    type: Array,
    required: true,
  },
  equipos: {
    type: Array,
    required: true,
  },
})

// Función para obtener nombre + logo por ID de equipo
function getEquipoInfo(id) {
  const equipo = props.equipos.find((e) => e.id === id)
  return {
    nombre: equipo?.nombre ?? 'Desconocido',
    logo: equipo?.logo ?? 'placeholder.png',
  }
}

// Agrupar por fase (jornada)
const fases = computed(() => {
  if (!Array.isArray(props.calendario)) return []

  const agrupado = {}

  for (const partido of props.calendario) {
    const nombreFase = partido.jornada ?? 'Fase Desconocida'
    if (!agrupado[nombreFase]) agrupado[nombreFase] = []

    agrupado[nombreFase].push({
      id: partido.id,
      local: getEquipoInfo(partido.equipo_local_id).nombre,
      visitante: getEquipoInfo(partido.equipo_visitante_id).nombre,
      logoLocal: getEquipoInfo(partido.equipo_local_id).logo,
      logoVisitante: getEquipoInfo(partido.equipo_visitante_id).logo,
      golesLocal: partido.goles_equipo_local,
      golesVisitante: partido.goles_equipo_visitante,
    })
  }

  // Convertir a array ordenado
  return Object.entries(agrupado).map(([nombre, partidos]) => ({
    nombre,
    partidos,
  }))
})
</script>

<template>
  <div class="overflow-auto bg-gray-900 p-6 min-h-screen text-white">
    <div class="grid" :style="`grid-template-columns: repeat(${fases.length}, minmax(200px, 1fr)); gap: 3rem;`">
      <template v-for="fase in fases" :key="fase.nombre">
        <div class="flex flex-col items-center">
          <h2 class="text-center text-red-500 font-bold uppercase mb-4">
            {{ fase.nombre }}
          </h2>

          <div class="flex flex-col gap-8">
            <div
              v-for="partido in fase.partidos"
              :key="partido.id"
              class="bg-gray-800 text-white p-4 rounded-xl shadow-md border border-red-700 w-56"
            >
              <div class="flex justify-between items-center mb-2">
                <span class="truncate">{{ partido.local }}</span>
                <img :src="`/${partido.logoLocal}`" class="w-6 h-6 rounded-full object-cover" />
              </div>
              <div class="flex justify-between font-semibold mb-1 text-sm">
                <span>Goles</span>
                <span class="text-red-400">{{ partido.golesLocal ?? '-' }}</span>
              </div>

              <hr class="my-2 border-red-700 opacity-50" />

              <div class="flex justify-between items-center mt-2">
                <span class="truncate">{{ partido.visitante }}</span>
                <img :src="`/${partido.logoVisitante}`" class="w-6 h-6 rounded-full object-cover" />
              </div>
              <div class="flex justify-between font-semibold text-sm">
                <span>Goles</span>
                <span class="text-red-400">{{ partido.golesVisitante ?? '-' }}</span>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>
