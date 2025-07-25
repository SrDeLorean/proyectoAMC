<script setup>
import { ref, onMounted } from 'vue';
import WelcomeLayout from '@/Layouts/GuestLayout.vue';
import { Link } from '@inertiajs/vue3'; // ✅ IMPORT CORRECTO

const parentHost = ref('');

onMounted(() => {
  parentHost.value = window.location.hostname;
});

const competencias = [
  {
    id: 1,
    name: 'PRO LEAGUE',
    description: 'La máxima competencia profesional.',
    image: '/images/amc-proleague-cuadrado.png',
    route: '/ligas/pro-league',
  },
  {
    id: 2,
    name: 'ELITE (División 1)',
    description: 'Nivel competitivo con alto rendimiento.',
    image: '/images/amc-elite-cuadrado.png',
    route: '/ligas/elite',
  },
  {
    id: 3,
    name: 'ASCENSO (División 2)',
    description: 'Para equipos en busca del ascenso.',
    image: '/images/amc-ascenso-cuadrado.png',
    route: '/ligas/ascenso',
  },
  {
    id: 4,
    name: 'ANFA (División 3)',
    description: 'Fútbol amateur con reconocimiento.',
    image: '/images/amc-anfa-cuadrado.png',
    route: '/ligas/anfa',
  },
];
</script>


<template>
  <WelcomeLayout>
    <template #default>
      <div class="min-h-screen bg-gray-950 text-white px-6 py-12 flex flex-col items-center">
        <h1 class="text-4xl font-bold mb-10 text-center">Competencias AMC</h1>

        <!-- PRO LEAGUE destacada -->
        <Link :href="competencias[0].route" class="w-full max-w-6xl mb-16 group block">
          <div class="rounded-lg overflow-hidden shadow-2xl bg-black p-4 transition-transform group-hover:scale-105">
            <img
              :src="competencias[0].image"
              :alt="competencias[0].name"
              class="w-full h-[28rem] object-contain object-center"
            />
          </div>
          <h2 class="text-3xl font-bold mt-6 text-center group-hover:text-red-500 transition-colors">
            {{ competencias[0].name }}
          </h2>
          <p class="text-md text-gray-400 text-center mt-2">{{ competencias[0].description }}</p>
        </Link>

        <!-- Ligas menores -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 w-full max-w-6xl">
          <Link
            v-for="competencia in competencias.slice(1)"
            :key="competencia.id"
            :href="competencia.route"
            class="flex flex-col items-center group cursor-pointer"
          >
            <div class="rounded-lg overflow-hidden shadow-md w-full h-60 transition-transform group-hover:scale-105">
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
    </template>
  </WelcomeLayout>
</template>
