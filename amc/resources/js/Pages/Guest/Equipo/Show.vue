<script setup>
import AppLayout from '@/Layouts/GuestLayout.vue'

const props = defineProps({
  equipo: Object,
})
</script>

<template>
  <AppLayout>
    <template #header>
      <h2 class="font-bold text-3xl text-gray-900 dark:text-white tracking-wide">
        {{ equipo.nombre }}
      </h2>
    </template>

    <div
      class="w-full max-w-3xl mx-auto p-6 bg-gray-800 rounded-xl shadow text-white border border-red-600"
    >
      <div class="flex flex-col sm:flex-row items-center gap-8 mb-8">
        <img
          :src="`/${equipo.logo}`"
          alt="Logo equipo"
          class="w-48 h-48 object-contain rounded-xl shadow-md"
        />

        <div class="flex flex-col space-y-4 text-gray-300 max-w-lg">
          <p><span class="font-semibold text-white">Descripción:</span> {{ equipo.descripcion || 'Sin descripción disponible' }}</p>

          <div>
            <h3 class="font-semibold text-white mb-2">Redes sociales:</h3>
            <ul class="flex space-x-4">
              <li v-if="equipo.instagram">
                <a href="https://{{ equipo.instagram }}" target="_blank" class="text-pink-500 hover:underline">Instagram</a>
              </li>
              <li v-if="equipo.twitch">
                <a href="https://{{ equipo.twitch }}" target="_blank" class="text-purple-600 hover:underline">Twitch</a>
              </li>
              <li v-if="equipo.youtube">
                <a href="https://{{ equipo.youtube }}" target="_blank" class="text-red-600 hover:underline">YouTube</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
