<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  calendario: Array,
  jornadasDisponibles: Array,
  jornadaSeleccionada: [Number, String, null],
  competenciaId: Number,
  temporadaCompetenciaId: Number,
})

const emit = defineEmits(['update:jornadaSeleccionada'])

const selectedJornada = ref(props.jornadaSeleccionada)

// Sincroniza prop a local cuando cambie desde padre
watch(() => props.jornadaSeleccionada, (newVal) => {
  selectedJornada.value = newVal
})

// Emitir evento al cambiar selección
function onChangeJornada(event) {
  emit('update:jornadaSeleccionada', event.target.value)
}
</script>

<template>
  <div>
    <label for="fase-select" class="block mb-2 text-white font-semibold">
      Selecciona Jornada
    </label>
    <select
      id="fase-select"
      :value="selectedJornada"
      @change="onChangeJornada"
      class="mb-4 rounded px-3 py-2 bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
    >
      <option value="">Todas</option>
      <option
        v-for="jornada in jornadasDisponibles"
        :key="jornada"
        :value="jornada"
      >
        Jornada {{ jornada }}
      </option>
    </select>

    <!-- Aquí puedes renderizar el calendario filtrado según selectedJornada -->
    <div>
      <div
        v-for="partido in calendario"
        :key="partido.id"
        class="mb-4 p-4 bg-gray-700 rounded text-white"
      >
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-4">
            <img :src="`/${partido.equipoLocal.logo}`" alt="Logo local" class="w-8 h-8" />
            <span>{{ partido.equipoLocal.nombre }}</span>
          </div>
          <span class="font-bold">
            {{ partido.goles_equipo_local ?? 0 }} - {{ partido.goles_equipo_visitante ?? 0 }}
          </span>
          <div class="flex items-center gap-4">
            <span>{{ partido.equipoVisitante.nombre }}</span>
            <img :src="`/${partido.equipoVisitante.logo}`" alt="Logo visitante" class="w-8 h-8" />
          </div>
        </div>
        <div class="text-sm text-gray-300 mt-1">
          Fecha: {{ new Date(partido.fecha).toLocaleDateString('es-ES') }}
          Hora: {{ partido.hora }}
          Jornada: {{ partido.jornada }}
        </div>
      </div>
    </div>
  </div>
</template>
