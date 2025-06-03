<script setup>
import { ref } from 'vue';
import { computed } from 'vue';

const props = defineProps({
  nombreLiga: { type: String, required: true },
  jornadas: { type: Array, required: true },
  resultados: { type: Array, required: true },
});

const selectedJornada = ref(props.jornadas[0]?.id || null);

const resultadosFiltrados = computed(() =>
  props.resultados.filter(r => r.jornadaId === selectedJornada.value)
);
</script>

<template>
  <div class="min-h-[400px] bg-gray-950 text-white px-4 py-8 max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center text-red-600">{{ nombreLiga }}</h1>

    <!-- Selector de Jornada -->
    <div class="flex justify-center mb-8 space-x-2">
      <button
        v-for="jornada in jornadas"
        :key="jornada.id"
        @click="selectedJornada = jornada.id"
        :class="[
          'px-3 py-1 rounded-md font-semibold text-sm transition-colors',
          selectedJornada === jornada.id
            ? 'bg-red-600 text-white shadow-md'
            : 'bg-gray-800 text-gray-300 hover:bg-red-700 hover:text-white'
        ]"
      >
        {{ jornada.name }}
      </button>
    </div>

    <!-- Lista de Resultados -->
    <div class="space-y-4">
      <div
        v-for="partido in resultadosFiltrados"
        :key="partido.id"
        class="flex flex-col sm:flex-row items-center justify-between bg-gray-900 rounded-md shadow p-3"
      >
        <!-- Equipos -->
        <div class="flex items-center space-x-3 w-full sm:w-1/3 justify-end sm:justify-start">
          <img :src="partido.equipoLocal.logo" alt="" class="w-8 h-8 object-contain" />
          <span class="font-semibold text-sm">{{ partido.equipoLocal.name }}</span>
        </div>

        <!-- Resultado y Estado -->
        <div class="flex flex-col items-center w-full sm:w-1/3">
          <div class="text-2xl font-bold leading-none">
            <span>{{ partido.golesLocal !== null ? partido.golesLocal : '-' }}</span>
            <span class="mx-1">-</span>
            <span>{{ partido.golesVisitante !== null ? partido.golesVisitante : '-' }}</span>
          </div>
          <div
            :class="[
              'mt-0.5 text-xs font-semibold',
              partido.estado === 'En Juego' ? 'text-green-400' :
              partido.estado === 'Próximo' ? 'text-yellow-400' : 'text-gray-400'
            ]"
          >
            {{ partido.estado }}
          </div>
        </div>

        <!-- Equipo Visitante -->
        <div class="flex items-center space-x-3 w-full sm:w-1/3 justify-start sm:justify-end">
          <span class="font-semibold text-sm">{{ partido.equipoVisitante.name }}</span>
          <img :src="partido.equipoVisitante.logo" alt="" class="w-8 h-8 object-contain" />
        </div>

        <!-- Fecha y hora -->
        <div class="w-full mt-3 sm:mt-0 sm:w-auto text-center text-gray-400 text-xs">
          {{ new Date(partido.fechaHora).toLocaleString('es-CL', { dateStyle: 'short', timeStyle: 'short' }) }}
        </div>
      </div>
    </div>
  </div>
</template>
