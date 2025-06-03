<script setup>
import { defineProps, ref, computed } from 'vue';

const props = defineProps({
  nombreLiga: { type: String, required: true },
  goleadores: { type: Array, required: true },
});

// Filtros reactivos
const searchNombre = ref('');
const equipoSeleccionado = ref('Todos');

// Extrae equipos únicos del listado
const equiposDisponibles = computed(() => {
  const equipos = new Set(props.goleadores.map(j => j.equipo));
  return ['Todos', ...equipos];
});

// Filtra los goleadores según nombre y equipo
const goleadoresFiltrados = computed(() =>
  props.goleadores.filter(jugador => {
    const coincideNombre = jugador.nombre.toLowerCase().includes(searchNombre.value.toLowerCase());
    const coincideEquipo =
      equipoSeleccionado.value === 'Todos' || jugador.equipo === equipoSeleccionado.value;
    return coincideNombre && coincideEquipo;
  })
);
</script>

<template>
  <div class="bg-gray-950 text-white px-4 py-8 max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center text-red-600">
      Goleadores - {{ nombreLiga }}
    </h2>

    <!-- Filtros -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
      <!-- Buscar por nombre -->
      <input
        v-model="searchNombre"
        type="text"
        placeholder="Buscar jugador..."
        class="px-3 py-2 rounded-md bg-gray-800 text-white placeholder-gray-400 text-sm w-full sm:w-1/2"
      />

      <!-- Filtro por equipo -->
      <select
        v-model="equipoSeleccionado"
        class="px-3 py-2 rounded-md bg-gray-800 text-white text-sm w-full sm:w-1/3"
      >
        <option v-for="equipo in equiposDisponibles" :key="equipo" :value="equipo">
          {{ equipo }}
        </option>
      </select>
    </div>

    <!-- Tabla de Goleadores -->
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border-collapse">
        <thead>
          <tr class="bg-gray-800 text-left text-sm uppercase text-gray-400">
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Jugador</th>
            <th class="px-4 py-2">Equipo</th>
            <th class="px-4 py-2">Goles</th>
            <th class="px-4 py-2">PJ</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(jugador, index) in goleadoresFiltrados"
            :key="jugador.nombre"
            class="border-b border-gray-700 hover:bg-gray-800"
          >
            <td class="px-4 py-2">{{ index + 1 }}</td>
            <td class="px-4 py-2 font-semibold">{{ jugador.nombre }}</td>
            <td class="px-4 py-2">{{ jugador.equipo }}</td>
            <td class="px-4 py-2">{{ jugador.goles }}</td>
            <td class="px-4 py-2">{{ jugador.partidos }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
