<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'

const props = defineProps({
  user: Object,
  estadisticas: Array
})
</script>

<template>
  <EntrenadorLayout title="Dashboard">
    <template #header>
      <h1 class="text-2xl font-bold text-gray-100">Resumen de tu Plantel</h1>
    </template>

    <div class="p-6 space-y-6 text-white">
      <div class="bg-gray-800 p-4 rounded shadow">
        <p><strong>Nombre:</strong> {{ user.name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <p><strong>Rol:</strong> {{ user.role }}</p>
      </div>

      <div>
        <h2 class="text-xl font-semibold mb-4">Estadísticas de tus jugadores</h2>

        <div v-if="estadisticas.length">
          <div
            v-for="item in estadisticas"
            :key="item.temporada_competencia_id"
            class="bg-gray-900 p-4 rounded mb-4 shadow"
          >
            <p><strong>Temporada:</strong> {{ item.temporada }}</p>
            <p><strong>Competencia:</strong> {{ item.competencia }}</p>
            <p><strong>Partidos Jugados:</strong> {{ item.partidos_jugados }}</p>
            <p><strong>Goles:</strong> {{ item.goles }}</p>
            <p><strong>Asistencias:</strong> {{ item.asistencias }}</p>
            <p><strong>Minutos Jugados:</strong> {{ item.minutos_jugados }}</p>
            <p><strong>Calificación Promedio:</strong> {{ item.calificacion_promedio }}</p>
          </div>
        </div>

        <div v-else class="text-gray-300">
          Aún no hay estadísticas registradas para tus jugadores.
        </div>
      </div>
    </div>
  </EntrenadorLayout>
</template>
