<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const props = defineProps({
  calendario: Array,
  jornadasDisponibles: Array,
  jornadaSeleccionada: [Number, String, null],
  competenciaId: Number,
  temporadaCompetenciaId: Number,
})

const jornada = ref(props.jornadaSeleccionada ?? '')

watch(jornada, (newJornada) => {
  const jornadaParam = newJornada === '' ? null : newJornada
  router.get(
    route('competencias.show', props.competenciaId),
    {
      temporada_id: props.temporadaCompetenciaId,
      jornada: jornadaParam,
    },
    {
      preserveState: true,
      replace: true,
    }
  )
})

function irADetalleCalendario(id) {
  router.visit(route('calendario.show', id))
}
</script>

<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
      <div class="w-full sm:w-44">
        <label
          for="jornada-select"
          class="block mb-1 text-white font-semibold select-none"
        >
          Filtrar por Jornada
        </label>
        <select
          id="jornada-select"
          v-model="jornada"
          class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-white
                 focus:outline-none focus:ring-2 focus:ring-red-600 transition"
        >
          <option :value="''" key="todas">Todas las jornadas</option>
          <option
            v-for="j in jornadasDisponibles"
            :key="'jornada-' + j"
            :value="j"
          >
            Jornada {{ j }}
          </option>
        </select>
      </div>
    </div>

    <div
      v-if="calendario.length > 0"
      class="overflow-x-auto rounded-xl border border-gray-700 shadow-lg"
    >
      <table class="min-w-full text-white text-left bg-gray-900 table-auto">
        <thead
          class="bg-red-700 text-white uppercase text-xs tracking-wide select-none"
        >
          <tr>
            <th class="px-3 py-2 font-semibold text-center w-10">Jornada</th>
            <th class="px-3 py-2 font-semibold max-w-[120px] truncate">Equipo Local</th>
            <th class="px-3 py-2 font-semibold w-12">Logo Local</th>
            <th class="px-3 py-2 font-semibold text-center w-16">Resultado</th>
            <th class="px-3 py-2 font-semibold w-12">Logo Visitante</th>
            <th class="px-3 py-2 font-semibold max-w-[120px] truncate">Equipo Visitante</th>
            <th class="px-3 py-2 font-semibold w-24">Fecha</th>
            <th class="px-3 py-2 font-semibold w-20">Hora</th>
          </tr>
        </thead>
        <tbody>
        <tr
            v-for="partido in calendario"
            :key="partido.id"
            class="border-t border-gray-700 hover:bg-gray-800 transition cursor-pointer"
            @click="irADetalleCalendario(partido.id)"
        >
            <td
            class="px-3 py-2 font-mono text-gray-300 text-center whitespace-nowrap"
            >
            {{ partido.jornada }}
            </td>

            <td
            class="px-3 py-2 max-w-[120px] truncate whitespace-nowrap"
            :title="partido.equipoLocal?.nombre ?? 'N/D'"
            >
            {{ partido.equipoLocal?.nombre ?? 'N/D' }}
            </td>

            <td class="px-3 py-2 text-center">
            <img
                v-if="partido.equipoLocal?.logo"
                :src="partido.equipoLocal.logo.startsWith('/') ? partido.equipoLocal.logo : '/' + partido.equipoLocal.logo"
                alt="Logo equipo local"
                class="w-8 h-8 sm:w-10 sm:h-10 object-contain rounded border border-red-600 mx-auto"
                loading="lazy"
            />
            </td>

            <td
            class="px-3 py-2 text-center font-bold text-red-400 whitespace-nowrap"
            >
            {{ partido.goles_equipo_local ?? '-' }} - {{ partido.goles_equipo_visitante ?? '-' }}
            </td>

            <td class="px-3 py-2 text-center">
            <img
                v-if="partido.equipoVisitante?.logo"
                :src="partido.equipoVisitante.logo.startsWith('/') ? partido.equipoVisitante.logo : '/' + partido.equipoVisitante.logo"
                alt="Logo equipo visitante"
                class="w-8 h-8 sm:w-10 sm:h-10 object-contain rounded border border-red-600 mx-auto"
                loading="lazy"
            />
            </td>

            <td
            class="px-3 py-2 max-w-[120px] truncate whitespace-nowrap"
            :title="partido.equipoVisitante?.nombre ?? 'N/D'"
            >
            {{ partido.equipoVisitante?.nombre ?? 'N/D' }}
            </td>

            <td class="px-3 py-2 text-center whitespace-nowrap text-sm sm:text-base">
            {{ partido.fecha}}
            </td>

            <td class="px-3 py-2 text-center whitespace-nowrap text-sm sm:text-base">
            {{ partido.hora }}
            </td>
        </tr>
        </tbody>
      </table>
    </div>

    <p
      v-else
      class="text-gray-400 text-center font-semibold mt-12 px-4 sm:px-0"
    >
      No hay partidos disponibles para esta jornada.
    </p>
  </div>
</template>
