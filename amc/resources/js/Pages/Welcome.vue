<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import WelcomeLayout from '@/Layouts/GuestLayout.vue'

import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import { Navigation, Pagination } from 'swiper/modules'

const parentHost = ref('')
onMounted(() => {
  parentHost.value = window.location.hostname
})

const props = defineProps({
  competencias: {
    type: Array,
    default: () => []
  }
})

function goToCompetencia(id) {
  router.get(route('competencias.show', id))
}
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <!-- Hero Section -->
      <section
        class="bg-gradient-to-br from-gray-900 via-black to-gray-800 text-gray-200 py-20 px-4 sm:px-6 md:px-12 text-center select-none max-w-7xl mx-auto"
      >
        <h1
          class="text-5xl sm:text-6xl md:text-7xl font-extrabold mb-6 tracking-wide text-red-600 drop-shadow-[0_0_12px_rgba(220,38,38,0.8)] leading-tight"
        >
          Comunidad AMC
        </h1>
        <p
          class="max-w-xl sm:max-w-2xl mx-auto text-base sm:text-lg md:text-xl text-gray-400 leading-relaxed mb-14 font-light"
        >
          Donde los jugadores se convierten en leyendas. Únete a nuestras competencias,
          comparte tu pasión y forma parte de la comunidad gamer más poderosa de habla hispana.
        </p>

        <div
          class="flex flex-col sm:flex-row justify-center gap-8 max-w-xs sm:max-w-md mx-auto"
        >
          <a
            href="/register"
            class="bg-red-600 hover:bg-red-700 transition-transform duration-300 rounded-full py-4 px-12 sm:px-14 font-bold shadow-lg shadow-red-900/70 transform hover:scale-110"
          >
            Únete ahora
          </a>
          <a
            href="/noticias"
            class="border-2 border-gray-600 hover:border-red-600 text-gray-400 hover:text-red-600 transition-colors duration-300 rounded-full py-4 px-12 sm:px-14 font-semibold"
          >
            Ver noticias
          </a>
        </div>
      </section>

      <!-- Carrusel de Competencias -->
      <section class="max-w-7xl mx-auto my-20 px-4 sm:px-6 md:px-12">
        <h2
          class="text-3xl sm:text-4xl font-extrabold text-center text-red-600 mb-12 drop-shadow-[0_2px_8px_rgba(220,38,38,0.6)] tracking-wide"
        >
          Torneos Activos
        </h2>

        <Swiper
          :modules="[Navigation, Pagination]"
          :slides-per-view="1"
          :space-between="20"
          navigation
          pagination
          :breakpoints="{
            640: { slidesPerView: 2, spaceBetween: 24 },
            1024: { slidesPerView: 3, spaceBetween: 32 }
          }"
          class="py-6 max-w-full touch-auto"
        >
          <SwiperSlide
            v-for="competencia in competencias"
            :key="competencia.id"
            class="cursor-pointer"
          >
            <div
              @click="goToCompetencia(competencia.id)"
              class="relative rounded-xl overflow-hidden shadow-lg transform transition duration-300 hover:scale-[1.03] hover:shadow-red-600 min-h-[280px]"
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

              <div
                class="relative z-10 flex flex-col items-center justify-center p-12 space-y-6 min-h-[280px]"
              >
                <img
                  :src="`/${competencia.logo}`"
                  alt="Logo competencia"
                  class="w-24 h-24 sm:w-28 sm:h-28 object-contain transition-transform duration-300 hover:scale-110"
                  loading="lazy"
                  draggable="false"
                />
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </section>

      <!-- Twitch Stream Section -->
      <section
        class="bg-gradient-to-tr from-gray-800 via-gray-900 to-black py-20 px-6 sm:px-12 rounded-3xl shadow-2xl mt-20 max-w-5xl mx-auto select-none"
      >
        <h2
          class="text-3xl sm:text-4xl font-extrabold mb-10 text-center text-red-600 drop-shadow-[0_2px_12px_rgba(220,38,38,0.75)] tracking-wide"
        >
          ¡Mira nuestras transmisiones en vivo!
        </h2>

        <div
          class="aspect-video w-full rounded-3xl overflow-hidden shadow-2xl border-4 border-red-600 mx-auto transition-transform duration-300 hover:scale-[1.04]"
        >
          <iframe
            :src="`https://player.twitch.tv/?channel=comunidadamc&parent=${parentHost}`"
            frameborder="0"
            allowfullscreen
            scrolling="no"
            class="w-full h-full"
          ></iframe>
        </div>
      </section>
    </template>
  </WelcomeLayout>
</template>
