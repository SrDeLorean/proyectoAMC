// TOTW.vue
<script setup>
const props = defineProps({
  jugadores: {
    type: Array,
    default: () => []
  }
})

</script>

<template>
  <div>
    <template v-if="jugadores.length > 0">
      <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
        <div
          v-for="jugador in jugadores"
          :key="jugador.id"
          class="bg-gray-900 rounded-lg border border-red-600 p-3 text-center shadow text-white"
        >
          <img
            :src="`/${jugador.imagen || 'images/jugadores/default.png'}`"
            class="w-16 h-16 object-cover rounded-full mx-auto mb-2 border border-gray-700"
            alt="Jugador"
          />
          <p class="text-sm font-semibold">{{ jugador.nombre }}</p>
          <p class="text-xs text-gray-400">{{ jugador.posicion }}</p>
        </div>
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
