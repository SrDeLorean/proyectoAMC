<script setup>
import { computed, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  jugadores: Array
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
  router.get(`/entrenador/dashboard/${jugadorId}`)
}
</script>

<template>
  <div>
    <h3 class="text-white text-2xl font-bold mb-4">Plantilla</h3>

    <template v-if="jugadores.length > 0">
      <div
        v-for="(jugadores, categoria) in jugadoresPorRol"
        :key="categoria"
        class="mb-6"
      >
        <h4 class="text-xl font-semibold capitalize text-white mb-2">{{ categoria }}</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <div
            v-for="jugador in jugadores"
            :key="jugador.jugador.id"
            class="bg-gray-800 p-3 rounded-lg shadow hover:bg-gray-700 transition flex items-center gap-3 cursor-pointer select-none"
            @click="irAlPerfil(jugador.jugador.id)"
            role="button"
            tabindex="0"
            @keyup.enter="irAlPerfil(jugador.jugador.id)"
          >
            <div class="w-12 h-12 relative">
              <img
                v-if="jugador.jugador.foto && !erroresImagenes[jugador.jugador.id]"
                :src="jugador.jugador.foto"
                alt="Foto jugador"
                class="w-12 h-12 rounded-full object-cover border border-white"
                @error="handleImageError(jugador.jugador.id)"
              />
              <div
                v-else
                class="absolute inset-0 rounded bg-gray-600 text-gray-300 flex items-center justify-center border border-white text-sm"
                title="Sin foto"
              >
                N/A
              </div>
            </div>

            <div class="truncate">
              <span class="font-bold text-sm">{{ jugador.numero }} - {{ jugador.jugador.name }}</span>
            </div>

            <div class="ml-auto">
              <img
                v-if="jugador.jugador.nacionalidad && !erroresBanderas[jugador.jugador.id]"
                :src="`https://flagcdn.com/w40/${jugador.jugador.nacionalidad.toLowerCase()}.png`"
                alt="Bandera"
                class="w-8 h-6 rounded border border-white"
                @error="handleFlagError(jugador.jugador.id)"
              />
              <div
                v-else
                class="w-8 h-6 rounded bg-gray-600 text-gray-300 flex items-center justify-center border border-white text-xs"
              >
                N/A
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <p v-else class="text-gray-400">No hay jugadores inscritos en este equipo.</p>
  </div>
</template>
