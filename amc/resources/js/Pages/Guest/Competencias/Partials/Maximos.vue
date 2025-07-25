<script setup>
const props = defineProps({ maximos: Array })
</script>

<template>
  <div>
    <template v-if="maximos.length > 0">
      <div
        v-for="jugador in maximos"
        :key="jugador.id"
        class="bg-gray-900 p-4 rounded-md border border-red-600 shadow text-white"
      >
        <p class="font-semibold">{{ jugador.nombre }}</p>
        <p class="text-gray-400 text-sm">{{ jugador.equipo }}</p>
        <p class="text-sm">Goles: {{ jugador.goles }}</p>
      </div>
    </template>
    <template v-else>
      <div
        class="bg-gray-900 p-8 rounded-md border-2 border-red-600 shadow text-center text-red-500 font-bold uppercase tracking-wide text-lg select-none"
      >
        Próximamente
      </div>
    </template>
  </div>
</template>
