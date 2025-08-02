<script setup>
import AppLayout from '@/Layouts/GuestLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  competencias: Array,
})

function goToCompetencia(id) {
  router.get(route('competencias.show', id))
}
</script>

<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
        Competencias AMC
      </h2>
    </template>

    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 max-w-7xl mx-auto">
        <div
          v-for="competencia in competencias"
          :key="competencia.id"
          class="relative cursor-pointer rounded-xl overflow-hidden shadow-lg transform transition duration-300 hover:scale-[1.03] hover:shadow-red-600"
          @click="goToCompetencia(competencia.id)"
        >
          <!-- Fondo con imagen oscurecida -->
          <div
            class="absolute inset-0 bg-black bg-opacity-70"
            :style="{ backgroundImage: `url(/${competencia.logo})`, backgroundSize: 'cover', backgroundPosition: 'center' }"
          ></div>

          <!-- Overlay de color rojo en hover -->
          <div
            class="absolute inset-0 bg-red-600 opacity-0 hover:opacity-30 transition-opacity duration-300 rounded-xl"
          ></div>

          <div class="relative z-10 flex flex-col items-center justify-center p-12 space-y-6 min-h-[280px]">
            <img
              :src="`/${competencia.logo}`"
              alt="Logo competencia"
              class="w-28 h-28 object-contain transition-transform duration-300 hover:scale-110"
              loading="lazy"
              draggable="false"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
