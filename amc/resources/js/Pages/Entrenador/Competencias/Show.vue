<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  torneo: Object,
  partidos: Array,
})

const partidosList = computed(() => props.partidos || [])

const irAReportarPartido = (id) => {
  router.visit(`/entrenador/reportes/create/${id}`)
}

const todasFotosLocalSubidas = (partido) =>
  partido.foto_rendimiento_local &&
  partido.foto_lista_id_local &&
  partido.foto_rendimiento_jugadores_local

const todasFotosVisitanteSubidas = (partido) =>
  partido.foto_rendimiento_visitante &&
  partido.foto_lista_id_visitante &&
  partido.foto_rendimiento_jugadores_visitante
</script>

<template>
  <EntrenadorLayout>
    <div class="p-8 dark:bg-gray-900 dark:text-gray-100 min-h-screen max-w-5xl mx-auto">
      <h1 class="text-4xl font-extrabold mb-8 border-b border-gray-700 pb-4">
        {{ torneo.temporadaCompetencia?.competencia?.nombre ?? 'Competencia no disponible' }} -
        Temporada: {{ torneo.temporadaCompetencia?.temporada?.nombre ?? 'Temporada no disponible' }}
      </h1>

      <h2 class="text-2xl font-semibold mb-6">Partidos</h2>

      <div v-if="partidosList.length" class="space-y-6">
        <div
          v-for="partido in partidosList"
          :key="partido.id"
          class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4"
        >
          <div class="flex items-center space-x-6 flex-1 min-w-0">
            <!-- Equipo local -->
            <div class="flex items-center space-x-3 min-w-[140px]">
              <img
                :src="partido.equipo_local?.logo
                  ? (partido.equipo_local.logo.startsWith('/') ? partido.equipo_local.logo : '/' + partido.equipo_local.logo)
                  : '/images/placeholder.png'"
                alt="Logo equipo local"
                class="w-12 h-12 object-contain rounded"
                loading="lazy"
              />
              <span class="text-lg font-medium truncate">{{ partido.equipo_local?.nombre ?? 'Equipo local' }}</span>
            </div>

            <!-- Marcador -->
            <div class="text-2xl font-extrabold flex justify-center w-24">
              {{ partido.goles_equipo_local ?? 0 }} - {{ partido.goles_equipo_visitante ?? 0 }}
            </div>

            <!-- Equipo visitante -->
            <div class="flex items-center space-x-3 min-w-[140px] justify-end md:justify-start">
              <span class="text-lg font-medium truncate">{{ partido.equipo_visitante?.nombre ?? 'Equipo visitante' }}</span>
              <img
                :src="partido.equipo_visitante?.logo
                  ? (partido.equipo_visitante.logo.startsWith('/') ? partido.equipo_visitante.logo : '/' + partido.equipo_visitante.logo)
                  : '/images/placeholder.png'"
                alt="Logo equipo visitante"
                class="w-12 h-12 object-contain rounded"
                loading="lazy"
              />
            </div>
          </div>

          <!-- Info jornada y fecha -->
          <div class="text-sm text-gray-600 dark:text-gray-400 mt-3 md:mt-0 whitespace-nowrap">
            Jornada: <span class="font-semibold">{{ partido.jornada }}</span> -
            Fecha: <span class="font-semibold">{{ new Date(partido.fecha).toLocaleDateString() }}</span>
          </div>

          <!-- Botón reportar partido -->
          <button
            @click="irAReportarPartido(partido.id)"
            class="mt-3 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-lg shadow-md transition-colors duration-200"
            title="Reportar partido"
            aria-label="Reportar partido"
          >
            Reportar Partido
          </button>

          <!-- Indicador de fotos subidas por equipo -->
          <div class="mt-2 space-y-4 text-sm w-full md:w-auto md:ml-6">
            <div>
              <strong>Fotos Local:</strong>
              <ul class="list-disc ml-5 text-green-600 dark:text-green-400">
                <li v-if="partido.foto_rendimiento_local">Rendimiento partido</li>
                <li v-if="partido.foto_lista_id_local">Listado ID partido</li>
                <li v-if="partido.foto_rendimiento_jugadores_local">Rendimientos individuales</li>
                <li
                  v-if="!partido.foto_rendimiento_local && !partido.foto_lista_id_local && !partido.foto_rendimiento_jugadores_local"
                  class="text-red-600 dark:text-red-400"
                >
                  Ninguna foto subida
                </li>
              </ul>
              <div v-if="todasFotosLocalSubidas(partido)" class="text-green-700 dark:text-green-400 font-semibold mt-1">
                Todas las fotos del equipo local están subidas ✅
              </div>
              <div v-else class="text-red-700 dark:text-red-400 font-semibold mt-1">
                Faltan fotos por subir para el equipo local ❌
              </div>
            </div>

            <div>
              <strong>Fotos Visitante:</strong>
              <ul class="list-disc ml-5 text-green-600 dark:text-green-400">
                <li v-if="partido.foto_rendimiento_visitante">Rendimiento partido</li>
                <li v-if="partido.foto_lista_id_visitante">Listado ID partido</li>
                <li v-if="partido.foto_rendimiento_jugadores_visitante">Rendimientos individuales</li>
                <li
                  v-if="!partido.foto_rendimiento_visitante && !partido.foto_lista_id_visitante && !partido.foto_rendimiento_jugadores_visitante"
                  class="text-red-600 dark:text-red-400"
                >
                  Ninguna foto subida
                </li>
              </ul>
              <div v-if="todasFotosVisitanteSubidas(partido)" class="text-green-700 dark:text-green-400 font-semibold mt-1">
                Todas las fotos del equipo visitante están subidas ✅
              </div>
              <div v-else class="text-red-700 dark:text-red-400 font-semibold mt-1">
                Faltan fotos por subir para el equipo visitante ❌
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-16 text-gray-500 dark:text-gray-400 text-lg">
        No hay partidos registrados para este torneo.
      </div>
    </div>
  </EntrenadorLayout>
</template>
