<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  jugador: Object,
})

const redes = computed(() => {
  return [
    { nombre: 'Instagram', icono: '📸', enlace: props.jugador.instagram },
    { nombre: 'Twitch', icono: '🎮', enlace: props.jugador.twitch },
    { nombre: 'YouTube', icono: '📺', enlace: props.jugador.youtube },
  ].filter(r => r.enlace)
})
</script>

<template>
  <GuestLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-gray-200 py-10 px-4 sm:px-10">
      <div class="max-w-4xl mx-auto bg-gray-900 border border-red-600 rounded-2xl shadow-lg p-6">
        <!-- Nombre -->
        <h1 class="text-3xl font-bold text-red-600 drop-shadow mb-4">{{ jugador.name }}</h1>

        <div class="flex flex-col md:flex-row gap-6">
          <!-- Foto -->
          <img
            :src="jugador.foto"
            alt="Foto del jugador"
            class="w-48 h-48 rounded-full object-cover border-4 border-red-600 shadow-red-600 shadow"
          />

          <!-- Info -->
          <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm sm:text-base">
            <div><strong class="text-red-600">Posición:</strong> {{ jugador.posicion }}</div>
            <div><strong class="text-red-600">Nacionalidad:</strong> {{ jugador.nacionalidad }}</div>
            <div><strong class="text-red-600">Altura:</strong> {{ jugador.altura }} cm</div>
            <div><strong class="text-red-600">Peso:</strong> {{ jugador.peso }} kg</div>
            <div>
              <strong class="text-red-600">Equipo:</strong>
              <span v-if="jugador.equipo">
                <a :href="`/equipos/${jugador.equipo_id}`" class="hover:underline hover:text-red-500">
                  {{ jugador.equipo }}
                </a>
              </span>
              <span v-else class="text-gray-400 italic">Libre</span>
            </div>
          </div>
        </div>

        <!-- Redes -->
        <div v-if="redes.length" class="mt-6">
          <h2 class="text-xl text-red-600 font-semibold mb-2">Redes Sociales</h2>
          <ul class="space-y-2">
            <li v-for="r in redes" :key="r.nombre">
              <a :href="r.enlace" target="_blank" class="text-gray-400 hover:text-red-500 hover:underline">
                {{ r.icono }} {{ r.nombre }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
