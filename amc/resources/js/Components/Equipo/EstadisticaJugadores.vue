<script setup>
const props = defineProps({
  jugadores: {
    type: Array,
    default: () => [],
  },
})

// Función para mostrar valor o 'N/A' si es null, undefined o vacío
function mostrarValor(valor) {
  return valor !== null && valor !== undefined && valor !== '' ? valor : 'N/A'
}
</script>

<template>
  <div
    class="bg-gray-900 p-4 rounded-xl shadow-lg border border-red-600 max-w-full mx-auto overflow-x-auto"
    style="min-width: 320px"
  >
    <h2 class="text-red-600 text-xl font-bold mb-4 drop-shadow-[0_0_3px_#f00] whitespace-nowrap">
      Estadísticas de Jugadores
    </h2>

    <template v-if="jugadores.length > 0">
      <table class="min-w-full border-collapse border border-gray-700 text-white text-sm table-auto">
        <thead>
          <tr class="bg-red-600">
            <th class="border border-gray-600 p-2 text-center min-w-[48px]">Foto</th>
            <th class="border border-gray-600 p-2 text-left min-w-[140px]">Jugador</th>
            <th class="border border-gray-600 p-2 text-left min-w-[80px]">Posición</th>
            <th class="border border-gray-600 p-2 text-left min-w-[80px]">Calificación</th>
            <th class="border border-gray-600 p-2 text-center min-w-[60px]">Goles</th>
            <th class="border border-gray-600 p-2 text-center min-w-[60px]">Asistencias</th>
            <th class="border border-gray-600 p-2 text-center min-w-[60px]">Tiros</th>
            <th class="border border-gray-600 p-2 text-center min-w-[100px]">Precisión tiros (%)</th>
            <th class="border border-gray-600 p-2 text-center min-w-[60px]">Pases</th>
            <th class="border border-gray-600 p-2 text-center min-w-[100px]">Precisión pases (%)</th>
            <th class="border border-gray-600 p-2 text-center min-w-[70px]">Regates</th>
            <th class="border border-gray-600 p-2 text-center min-w-[130px]">Tasa éxito entradas (%)</th>
            <th class="border border-gray-600 p-2 text-center min-w-[80px]">Fueras de juego</th>
            <th class="border border-gray-600 p-2 text-center min-w-[90px]">Faltas cometidas</th>
            <th class="border border-gray-600 p-2 text-center min-w-[110px]">Posesión ganada</th>
            <th class="border border-gray-600 p-2 text-center min-w-[110px]">Posesión perdida</th>
            <th class="border border-gray-600 p-2 text-center min-w-[100px]">Minutos jugados</th>
            <th class="border border-gray-600 p-2 text-center min-w-[130px]">Distancia total (m)</th>
            <th class="border border-gray-600 p-2 text-center min-w-[130px]">Distancia carrera (m)</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="jugador in jugadores"
            :key="jugador.jugador.id"
            class="hover:bg-gray-800"
          >
            <td class="border border-gray-600 p-1 text-center">
              <img
                v-if="jugador.jugador.foto"
                :src="jugador.jugador.foto"
                alt="Foto jugador"
                class="w-8 h-8 rounded-full object-cover mx-auto border border-white"
              />
              <div
                v-else
                class="w-8 h-8 rounded-full bg-gray-700 mx-auto border border-white flex items-center justify-center text-xs text-gray-400"
              >
                N/A
              </div>
            </td>
            <td class="border border-gray-600 p-2 truncate max-w-[160px]">
              {{ jugador.jugador.id_ea || 'Nombre desconocido' }}
            </td>
            <td class="border border-gray-600 p-2 text-left">{{ mostrarValor(jugador.posicion) }}</td>
            <td class="border border-gray-600 p-2 text-left">{{ mostrarValor(jugador.jugador.calificacion) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.goles) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.asistencias) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.tiros) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.precision_tiros) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.pases) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.precision_pases) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.regates) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.tasa_exito_entradas) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.fueras_de_juego) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.faltas_cometidas) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.posesion_ganada) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.posesion_perdida) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.minutos_jugados_vs_equipo) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.distancia_total_vs_equipo) }}</td>
            <td class="border border-gray-600 p-2 text-center">{{ mostrarValor(jugador.jugador.distancia_carrera_vs_equipo) }}</td>
          </tr>
        </tbody>
      </table>
    </template>

    <p
      v-else
      class="text-gray-400 text-center italic mt-6"
    >
      No hay jugadores inscritos en el equipo.
    </p>
  </div>
</template>
