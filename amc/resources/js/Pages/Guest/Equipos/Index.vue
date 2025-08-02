<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  equipos: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <GuestLayout>
    <section class="max-w-6xl mx-auto px-4 py-12 select-none">
      <h1 class="text-4xl font-extrabold text-center text-red-600 mb-12 drop-shadow-[0_0_10px_rgba(220,38,38,0.8)] tracking-wide">
        Clubes
      </h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <Link
          v-for="equipo in equipos"
          :key="equipo.id"
          :href="`/equipos/${equipo.id}`"
          class="cursor-pointer rounded-xl p-4 flex items-center justify-between transition-all duration-300 shadow-md hover:scale-[1.02]"
          :style="{
            background: hovered === equipo.id ? equipo.color_primario : 'linear-gradient(to bottom right, #1f2937, #000000, #1f2937)',
            color: hovered === equipo.id ? '#fff' : '',
          }"
          @mouseenter="hovered = equipo.id"
          @mouseleave="hovered = null"
        >
          <!-- IZQUIERDA -->
          <div class="flex-1 pr-4">
            <h2 class="text-xl font-extrabold text-gray-200 mb-2">
              {{ equipo.nombre }}
            </h2>
            <p class="text-sm text-gray-400 mb-1">
              <span class="text-gray-500 font-semibold">Dueño:</span>
              {{ equipo.propietario?.name || 'Sin dueño' }}
            </p>
            <p class="text-sm text-gray-400">
              <span class="text-gray-500 font-semibold">DT:</span>
              {{ equipo.entrenador?.name || 'Sin DT' }}
            </p>
          </div>

          <!-- DERECHA: Logo grande -->
          <div class="flex-shrink-0">
            <img
              :src="`/${equipo.logo}`"
              :alt="equipo.nombre"
              class="w-20 h-20 sm:w-24 sm:h-24 object-contain"
              loading="lazy"
              draggable="false"
            />
          </div>
        </Link>
      </div>
    </section>
  </GuestLayout>
</template>

<script>
import { ref } from 'vue'

const hovered = ref(null)
</script>
