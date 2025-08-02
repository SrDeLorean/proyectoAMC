<script setup>
import { computed } from 'vue'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
  activos: Array,
  inactivos: Array
})

const torneosActivos = computed(() => props.activos || [])
const torneosInactivos = computed(() => props.inactivos || [])

const verDetalle = (id) => {
  router.visit(`/entrenador/competencias/${id}`)
}
</script>

<template>
  <EntrenadorLayout>
    <Head title="Mis Competencias" />

    <div class="p-8 dark:bg-gray-900 dark:text-gray-100 min-h-screen max-w-5xl mx-auto">
      <h1 class="text-3xl font-extrabold mb-6 border-b border-gray-700 pb-3">Torneos Activos</h1>

      <div v-if="torneosActivos.length" class="space-y-6">
        <div
          v-for="torneo in torneosActivos"
          :key="torneo.id"
          class="bg-white dark:bg-gray-800 dark:border-gray-700 rounded-xl shadow-lg p-6 border"
        >
          <div class="flex justify-between items-center mb-3">
            <div>
              <h2 class="text-xl font-semibold leading-tight">
                {{ torneo.temporada_competencia?.competencia?.nombre ?? 'Sin nombre de competencia' }}
              </h2>
              <p class="text-gray-600 dark:text-gray-400 mt-1">
                Temporada: <span class="font-medium">{{ torneo.temporada_competencia?.temporada?.nombre ?? 'Sin nombre de temporada' }}</span>
              </p>
            </div>
            <span class="inline-block bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-semibold px-3 py-1 rounded-full">
              Activo
            </span>
          </div>

          <button
            @click="verDetalle(torneo.id)"
            class="bg-blue-600 hover:bg-blue-700 transition-colors duration-200 text-white text-sm font-semibold px-5 py-2 rounded-lg shadow-md"
          >
            Ver Detalle
          </button>
        </div>
      </div>
      <div v-else>
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No tienes torneos activos.</p>
      </div>

      <h1 class="text-3xl font-extrabold mt-12 mb-6 border-b border-gray-700 pb-3">Torneos Inactivos</h1>

      <div v-if="torneosInactivos.length" class="space-y-6">
        <div
          v-for="torneo in torneosInactivos"
          :key="torneo.id"
          class="bg-gray-100 dark:bg-gray-800 dark:border-gray-700 rounded-xl shadow p-6 border"
        >
          <div class="flex justify-between items-center mb-3">
            <div>
              <h2 class="text-xl font-semibold leading-tight">
                {{ torneo.temporada_competencia?.competencia?.nombre ?? 'Sin nombre de competencia' }}
              </h2>
              <p class="text-gray-600 dark:text-gray-400 mt-1">
                Temporada: <span class="font-medium">{{ torneo.temporada_competencia?.temporada?.nombre ?? 'Sin nombre de temporada' }}</span>
              </p>
            </div>
            <span class="inline-block bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-semibold px-3 py-1 rounded-full">
              Inactivo
            </span>
          </div>
        </div>
      </div>
      <div v-else>
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No tienes torneos inactivos.</p>
      </div>
    </div>
  </EntrenadorLayout>
</template>
