<script setup>
import { computed, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  jugadores: Array,
  colorEquipo: {
    type: String,
    default: '#dc2626', // rojo por defecto si no se pasa color
  },
})

const rolMap = {
  PO: 'portero', DFC: 'defensa', DFD: 'defensa', DFI: 'defensa',
  LD: 'defensa', LI: 'defensa',
  MCD: 'centrocampista', MDD: 'centrocampista', MDI: 'centrocampista', MC: 'centrocampista',
  MCI: 'centrocampista', MCR: 'centrocampista', MD: 'centrocampista',
  MI: 'centrocampista', MCO: 'centrocampista', MOI: 'centrocampista', MOD: 'centrocampista',
  EI: 'delantero', ED: 'delantero', DI: 'delantero', DD: 'delantero', DC: 'delantero',
  SDI: 'delantero', SDD: 'delantero'
}

const jugadoresPorRol = computed(() => {
  const agrupados = { portero: [], defensa: [], centrocampista: [], delantero: [] }
  props.jugadores.forEach((p) => {
    const rolGeneral = rolMap[p.posicion] || 'delantero'
    agrupados[rolGeneral].push(p)
  })
  return agrupados
})

const erroresImagenes = reactive({})
const erroresBanderas = reactive({})

function handleImageError(id) {
  erroresImagenes[id] = true
}

function handleFlagError(id) {
  erroresBanderas[id] = true
}

function irAlPerfil(jugadorId) {
  router.get(`/jugadores/${jugadorId}`)
}
</script>

<template>
  <div>
    <h3
      class="text-white text-2xl font-bold mb-6 border-b pb-2"
      :style="{ borderColor: colorEquipo }"
    >
      Plantilla
    </h3>

    <template v-if="jugadores.length > 0">
      <div
        v-for="(jugadores, categoria) in jugadoresPorRol"
        :key="categoria"
        class="mb-8"
      >
        <h4
          class="text-xl font-semibold capitalize mb-4 pl-3"
          :style="{ borderLeft: `4px solid ${colorEquipo}` }"
        >
          {{ categoria }}
        </h4>

        <template v-if="jugadores.length > 0">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            <div
              v-for="jugador in jugadores"
              :key="jugador.jugador.id"
              class="bg-gray-800 p-4 rounded-lg shadow-lg hover:bg-gray-700 transition cursor-pointer flex items-center gap-4 select-none"
              @click="irAlPerfil(jugador.jugador.id)"
              role="button"
              tabindex="0"
              @keyup.enter="irAlPerfil(jugador.jugador.id)"
              @mouseentr="$event.currentTarget.style.boxShadow = '0 0 12px ' + colorEquipo"            >
              <div class="w-14 h-14 relative flex-shrink-0">
                <img
                  v-if="jugador.jugador.foto && !erroresImagenes[jugador.jugador.id]"
                  :src="jugador.jugador.foto"
                  alt="Foto jugador"
                  class="w-14 h-14 rounded-full object-cover"
                  :style="{ border: `2px solid ${colorEquipo}` }"
                  @error="handleImageError(jugador.jugador.id)"
                />
                <div
                  v-else
                  class="absolute inset-0 rounded-full bg-gray-600 text-gray-300 flex items-center justify-center text-sm font-semibold"
                  :style="{ border: `2px solid ${colorEquipo}` }"
                  title="Sin foto"
                >
                  N/A
                </div>
              </div>

              <div class="flex flex-col flex-grow min-w-0">
                <span class="font-bold text-white truncate">
                  {{ jugador.numero }} - {{ jugador.jugador.name }}
                </span>
                <span class="text-gray-400 text-sm truncate">
                  Posición: {{ jugador.posicion || 'No definida' }}
                </span>
              </div>

              <div class="flex-shrink-0">
                <img
                  v-if="jugador.jugador.nacionalidad && !erroresBanderas[jugador.jugador.id]"
                  :src="`https://flagcdn.com/w40/${jugador.jugador.nacionalidad.toLowerCase()}.png`"
                  alt="Bandera"
                  class="w-9 h-7 rounded border shadow"
                  :style="{ borderColor: colorEquipo, boxShadow: `0 0 5px ${colorEquipo}` }"
                  @error="handleFlagError(jugador.jugador.id)"
                />
                <div
                  v-else
                  class="w-9 h-7 rounded bg-gray-600 text-gray-300 flex items-center justify-center text-xs font-semibold select-none"
                  :style="{ border: `1px solid ${colorEquipo}` }"
                >
                  N/A
                </div>
              </div>
            </div>
          </div>
        </template>

        <template v-else>
          <p class="text-gray-400 italic text-center">No hay jugadores en esta categoría.</p>
        </template>
      </div>
    </template>

    <p v-else class="text-gray-400 italic text-center mt-12">
      No hay jugadores inscritos en este equipo.
    </p>
  </div>
</template>
