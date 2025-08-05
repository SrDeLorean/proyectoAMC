<script setup>
const props = defineProps({
  estadistica: {
    type: Object,
    default: () => ({}), // evita undefined
  },
  colorEquipo: {
    type: String,
    default: '#dc2626', // rojo por defecto si no viene prop
  },
})

// Función para mostrar valor o 'N/A' si no existe o es null/undefined
function mostrarValor(valor) {
  return valor !== null && valor !== undefined && valor !== '' ? valor : 'N/A'
}

// Computed para sombra dinámica según colorEquipo
const sombraColor = `0 0 3px ${props.colorEquipo}`
</script>

<template>
  <div
    class="bg-gray-900 p-4 rounded-xl shadow-lg max-w-md mx-auto"
    :style="{ border: `1px solid ${colorEquipo}` }"
  >
    <h2
      class="text-xl font-bold mb-4"
      :style="{ color: colorEquipo, textShadow: sombraColor }"
    >
      Estadísticas del Equipo
    </h2>
    <div class="text-white space-y-2">
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Goles Esperados</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.goles_esperados) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Posesión (%)</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.posesion) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Tasa Éxito en Regates (%)</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.tasa_exito_regates) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Precisión en Tiros (%)</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.precision_tiros) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Precisión en Pases (%)</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.precision_pases) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Recuperación de Balón</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.recuperacion_balon) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Tiros</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.tiros) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Pases</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.pases) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Entradas</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.entradas) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Entradas con Éxito</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.entradas_con_exito) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Recuperaciones</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.recuperaciones) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Atajadas</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.atajadas) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Faltas Cometidas</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.faltas_cometidas) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Fuera de Juego</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.fuera_de_juego) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Tiros de Esquina</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.tiros_de_esquina) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Tiros Libres</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.tiros_libres) }}</span>
      </div>
      <div class="flex justify-between border-b border-gray-700 pb-1">
        <span>Penales</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.penales) }}</span>
      </div>
      <div class="flex justify-between">
        <span>Tarjetas Amarillas</span>
        <span class="text-gray-300">{{ mostrarValor(estadistica.tarjetas_amarillas) }}</span>
      </div>
    </div>
  </div>
</template>
