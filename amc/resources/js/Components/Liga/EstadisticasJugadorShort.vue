<script setup>
defineProps({
  nombreLiga: String,
  estadisticas: Array, // <- este prop es un array
});
</script>

<template>
  <section class="bg-[#111111] text-white py-8">
    <div class="max-w-7xl mx-auto px-4 space-y-8">
      <h2 class="text-2xl font-bold text-center uppercase">{{ nombreLiga }} - Estadísticas</h2>

      <!-- Iteramos sobre las categorías de estadísticas -->
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div v-for="(categoria, index) in estadisticas" :key="index" class="bg-zinc-900 rounded-2xl p-4 shadow-lg">
          <h3 class="text-xl font-semibold text-red-600 mb-4">{{ categoria.categoria }}</h3>

          <!-- Encabezado -->
          <div class="grid grid-cols-12 items-center text-xs font-bold text-gray-400 px-2 mb-2">
            <div class="col-span-1">#</div>
            <div class="col-span-2">Foto</div>
            <div class="col-span-5">Nombre</div>
            <div class="col-span-2 text-right">Club</div>
            <div class="col-span-2 text-right">Cant.</div>
          </div>

          <!-- Lista de jugadores -->
          <div v-for="(jugador, j) in categoria.jugadores.slice(0, 5)" :key="j" class="grid grid-cols-12 items-center text-sm text-white py-2 border-t border-gray-800 hover:bg-zinc-800 transition">
            <div class="col-span-1">{{ j + 1 }}</div>
            <div class="col-span-2">
              <img :src="jugador.fotoJugador" alt="foto" class="w-8 h-8 rounded-full object-cover" />
            </div>
            <div class="col-span-5">{{ jugador.nombre }}</div>
            <div class="col-span-2 flex justify-end">
              <img :src="jugador.logoEquipo" alt="club" class="w-6 h-6 object-contain" />
            </div>
            <div class="col-span-2 text-right font-semibold">{{ jugador.cantidad }}</div>
          </div>

          <!-- Botón -->
          <div class="text-center mt-4">
            <button class="text-sm text-red-500 hover:underline">Mostrar lista completa</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
