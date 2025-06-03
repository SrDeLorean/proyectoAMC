<script setup>
import { ref } from 'vue';

const props = defineProps({
  nombreLiga: { type: String, required: true },
  estadisticas: { type: Array, required: true },
  // estadisticas = [
  //   {
  //     categoria: 'Goles',
  //     jugadores: [
  //       { nombre, equipo, logoEquipo, fotoJugador, cantidad },
  //       ...
  //     ]
  //   },
  //   ...
  // ]
});

const listasCompletas = ref({});

// Inicializar estados para control de mostrar lista completa por categoría
props.estadisticas.forEach(stat => {
  listasCompletas.value[stat.categoria] = false;
});

function toggleLista(categoria) {
  listasCompletas.value[categoria] = !listasCompletas.value[categoria];
}

const LIMITE_CORTO = 5;
</script>

<template>
  <div class="max-w-7xl mx-auto px-6 py-10 bg-gray-900 text-white rounded-lg shadow-xl grid gap-8 grid-cols-1 md:grid-cols-3">
    <h2 class="col-span-full text-center text-4xl font-extrabold mb-10 text-red-600 tracking-wide">
      Estadísticas de Jugadores - {{ nombreLiga }}
    </h2>

    <section
      v-for="categoria in estadisticas"
      :key="categoria.categoria"
      class="bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col"
    >
      <!-- Título -->
      <h3 class="text-2xl font-semibold mb-6 border-b border-red-600 pb-3 uppercase tracking-widest text-red-500">
        {{ categoria.categoria }}
      </h3>

      <!-- Jugador Destacado -->
      <div class="flex items-center gap-6 mb-6">
        <!-- Logo club -->
        <img
          :src="categoria.jugadores[0].logoEquipo"
          alt="Logo equipo"
          class="w-14 h-14 object-contain rounded"
        />
        <!-- Foto jugador y nombre -->
        <div class="flex items-center gap-4 flex-1 min-w-0">
          <img
            :src="categoria.jugadores[0].fotoJugador"
            alt="Foto jugador"
            class="w-20 h-20 rounded-full border-2 border-red-600 object-cover flex-shrink-0"
          />
          <h4
            class="text-2xl font-bold truncate hover:underline cursor-pointer"
            :title="categoria.jugadores[0].nombre"
          >
            {{ categoria.jugadores[0].nombre }}
          </h4>
        </div>
        <!-- Cantidad -->
        <div class="text-red-600 font-extrabold text-5xl font-mono select-none" style="font-feature-settings: 'tnum'">
          {{ categoria.jugadores[0].cantidad }}
        </div>
      </div>

      <!-- Lista de jugadores -->
      <div class="overflow-x-auto flex-1">
        <table class="w-full text-left border-separate border-spacing-y-1">
          <thead>
            <tr class="text-gray-400 uppercase text-xs font-semibold tracking-wide">
              <th class="pl-2 pr-4 py-2 w-[40px]">#</th>
              <th class="pr-4 py-2 min-w-[150px]">Jugador</th>
              <th class="pr-4 py-2 w-[40px]"></th>
              <th class="pr-4 py-2 min-w-[120px]">Equipo</th>
              <th class="pr-2 py-2 w-[60px] text-right">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(jugador, idx) in categoria.jugadores.slice(1, listasCompletas[categoria.categoria] ? undefined : LIMITE_CORTO + 1)"
              :key="jugador.nombre"
              class="bg-gray-700 hover:bg-gray-600 rounded-md cursor-default align-middle"
            >
              <td class="pl-2 pr-4 py-3 font-mono text-gray-300 font-semibold">
                {{ idx + 2 }}
              </td>
              <td class="pr-4 py-3 truncate font-semibold">
                {{ jugador.nombre }}
              </td>
              <td class="pr-4 py-3">
                <img
                  :src="jugador.fotoJugador"
                  alt="Foto jugador"
                  class="w-8 h-8 rounded-full object-cover border border-gray-600"
                />
              </td>
              <td class="pr-4 py-3 flex items-center gap-2 truncate">
                <img
                  :src="jugador.logoEquipo"
                  alt="Logo equipo"
                  class="w-6 h-6 object-contain flex-shrink-0"
                />
                <span class="uppercase text-gray-300 tracking-wide truncate">{{ jugador.equipo }}</span>
              </td>
              <td
                class="pr-2 py-3 font-bold text-red-600 text-right font-mono"
                style="font-feature-settings: 'tnum'"
              >
                {{ jugador.cantidad }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Botón mostrar lista completa -->
      <button
        @click="toggleLista(categoria.categoria)"
        class="mt-4 w-full text-center text-red-600 hover:text-red-400 font-semibold focus:outline-none"
        aria-label="Mostrar lista completa o menos"
      >
        {{ listasCompletas[categoria.categoria] ? 'Mostrar menos' : 'Mostrar lista completa' }}
      </button>
    </section>
  </div>
</template>
