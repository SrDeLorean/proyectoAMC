<script setup>
import { ref, computed } from 'vue'
import WelcomeLayout from '@/Layouts/GuestLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  competencias: Array,
})

const competenciaDestacada = computed(() =>
  props.competencias.length > 0 ? props.competencias[0] : null
)

const ligasRestantes = computed(() =>
  props.competencias.length > 1 ? props.competencias.slice(1) : []
)
</script>

<template>
  <WelcomeLayout>
    <div class="min-h-screen bg-gray-950 text-white px-6 py-12 flex flex-col items-center">
      <h1 class="text-4xl font-bold mb-10 text-center text-red-500">Competencias AMC</h1>

      <!-- Competencia destacada -->
      <Link
        v-if="competenciaDestacada"
        :href="competenciaDestacada.route"
        class="w-full max-w-6xl mb-16 group block"
      >
        <div class="rounded-xl overflow-hidden shadow-2xl bg-black p-4 transition-all duration-300 group-hover:scale-105">
          <img
            :src="competenciaDestacada.image"
            :alt="competenciaDestacada.name"
            class="w-full h-[28rem] object-contain object-center"
          />
        </div>
        <h2 class="text-3xl font-bold mt-6 text-center group-hover:text-red-500 transition-colors">
          {{ competenciaDestacada.name }}
        </h2>
        <p class="text-md text-gray-400 text-center mt-2">{{ competenciaDestacada.description }}</p>
      </Link>

      <!-- Otras ligas -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 w-full max-w-6xl">
        <Link
          v-for="competencia in ligasRestantes"
          :key="competencia.id"
          :href="competencia.route"
          class="flex flex-col items-center group cursor-pointer"
        >
          <div class="rounded-lg overflow-hidden shadow-lg w-full h-60 bg-gray-800 transition-transform duration-300 group-hover:scale-105">
            <img
              :src="competencia.image"
              :alt="competencia.name"
              class="w-full h-full object-cover"
            />
          </div>
          <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-red-500 transition-colors">
            {{ competencia.name }}
          </h3>
          <p class="text-sm text-gray-400 text-center mt-1">{{ competencia.description }}</p>
        </Link>
      </div>
    </div>
  </WelcomeLayout>
</template>
