<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
  estadisticas: {
    type: Array,
    default: () => []
  }
})

function irAlPerfil() {
  router.visit(`/entrenador/dashboard/${props.user.id}`)
}
</script>

<template>
  <EntrenadorLayout title="Dashboard">
    <template #header>
      <h1 class="text-3xl font-extrabold text-white">Panel del Entrenador</h1>
    </template>

    <div class="px-4 py-6 space-y-10 text-white max-w-7xl mx-auto">

      <!-- Panel Datos Usuario -->
      <div
        class="bg-gray-800 rounded-2xl p-6 shadow-xl flex flex-col md:flex-row justify-between items-center gap-6 border-2 border-red-600"
      >
        <div class="space-y-2 text-lg">
          <p><strong>👤 Nombre:</strong> {{ user?.name ?? 'Sin nombre' }}</p>
          <p><strong>📧 Email:</strong> {{ user?.email ?? 'Sin email' }}</p>
          <p><strong>🎖 Rol:</strong> {{ user?.role ?? 'Sin rol' }}</p>
        </div>
        <button
          @click="irAlPerfil"
          class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition"
        >
          Ver Perfil
        </button>
      </div>

      <!-- Estadísticas -->
      <div>
        <h2 class="text-2xl font-semibold text-white border-b border-red-600 pb-2 mb-4">
          📊 Estadísticas de tus jugadores
        </h2>

        <div v-if="estadisticas.length" class="grid gap-6 max-h-[600px] overflow-auto pr-2">
          <div
            v-for="item in estadisticas"
            :key="item.temporada_competencia_id"
            class="bg-gray-900 rounded-xl p-6 shadow border border-red-600 hover:shadow-lg transition"
          >
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-2 text-base">
              <div><strong>📅 Temporada:</strong> {{ item.temporada }}</div>
              <div><strong>🏆 Competencia:</strong> {{ item.competencia }}</div>
              <div><strong>🎽 Partidos Jugados:</strong> {{ item.partidos_jugados }}</div>
              <div><strong>⚽ Goles:</strong> {{ item.goles }}</div>
              <div><strong>🅰️ Asistencias:</strong> {{ item.asistencias }}</div>
              <div><strong>⏱ Minutos Jugados:</strong> {{ item.minutos_jugados }}</div>
              <div><strong>⭐ Calificación Promedio:</strong> {{ item.calificacion_promedio }}</div>
            </div>
          </div>
        </div>

        <div v-else class="text-gray-400 italic">
          Aún no hay estadísticas registradas para tus jugadores.
        </div>
      </div>
    </div>
  </EntrenadorLayout>
</template>
