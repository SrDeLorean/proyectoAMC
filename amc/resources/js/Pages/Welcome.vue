<script setup>
import { ref, onMounted } from 'vue'
import WelcomeLayout from '@/Layouts/GuestLayout.vue'
import EstadisticasJugador from '@/Components/Liga/EstadisticasJugador.vue'

const parentHost = ref('')
onMounted(() => {
  parentHost.value = window.location.hostname
})

const golesPlayers = [
  {
    rank: 1,
    firstName: 'Kylian',
    lastName: 'Mbappé',
    playerImage: '/images/jugadores/mbappe.png',
    playerName: 'Kylian Mbappé',
    statValue: 28,
    clubLogo: '/images/equipos/realmadrid.png',
  },
  {
    rank: 2,
    firstName: 'Erling',
    lastName: 'Haaland',
    playerImage: '/images/jugadores/haaland.png',
    playerName: 'Erling Haaland',
    statValue: 22,
    clubLogo: '/images/equipos/manchestercity.png',
  },
  {
    rank: 3,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png',
  },
]

const asistenciasPlayers = [
  {
    rank: 1,
    firstName: 'Erling',
    lastName: 'Haaland',
    playerImage: '/images/jugadores/haaland.png',
    playerName: 'Erling Haaland',
    statValue: 40,
    clubLogo: '/images/equipos/manchestercity.png',
  },
  {
    rank: 2,
    firstName: 'Kylian',
    lastName: 'Mbappé',
    playerImage: '/images/jugadores/mbappe.png',
    playerName: 'Kylian Mbappé',
    statValue: 30,
    clubLogo: '/images/equipos/realmadrid.png',
  },
  {
    rank: 3,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png',
  },
]

const pasesPlayers = [
  {
    rank: 1,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 1500,
    clubLogo: '/images/equipos/barcelona.png',
  },
  {
    rank: 2,
    firstName: 'Erling',
    lastName: 'Haaland',
    playerImage: '/images/jugadores/haaland.png',
    playerName: 'Erling Haaland',
    statValue: 1256,
    clubLogo: '/images/equipos/manchestercity.png',
  },
  {
    rank: 3,
    firstName: 'Kylian',
    lastName: 'Mbappé',
    playerImage: '/images/jugadores/mbappe.png',
    playerName: 'Kylian Mbappé',
    statValue: 1100,
    clubLogo: '/images/equipos/realmadrid.png',
  },
]
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <!-- Hero Section -->
      <section
        class="bg-gradient-to-br from-gray-900 via-black to-gray-800 text-white py-20 px-6 text-center"
      >
        <h1
          class="text-6xl font-extrabold mb-6 tracking-widest text-red-600 drop-shadow-lg"
        >
          Comunidad AMC
        </h1>
        <p
          class="max-w-3xl mx-auto text-lg sm:text-xl text-gray-300 leading-relaxed mb-10"
        >
          Donde los jugadores se convierten en leyendas. Únete a nuestras competencias,
          comparte tu pasión y forma parte de la comunidad gamer más poderosa de habla hispana.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-6 max-w-sm mx-auto">
          <a
            href="/register"
            class="bg-red-600 hover:bg-red-700 transition rounded-full py-3 px-8 font-semibold shadow-lg shadow-red-700/50"
            >Únete ahora</a
          >
          <a
            href="/noticias"
            class="bg-transparent border border-gray-600 hover:border-red-600 text-gray-300 hover:text-red-600 transition rounded-full py-3 px-8 font-semibold"
            >Ver noticias</a
          >
        </div>
      </section>

      <!-- Twitch Stream Section -->
      <section
        class="bg-gradient-to-tr from-gray-800 via-gray-900 to-black py-16 px-6 rounded-xl shadow-2xl mt-20"
      >
        <h2
          class="text-3xl font-bold mb-8 text-center text-red-600 drop-shadow-md tracking-wide"
        >
          ¡Mira nuestras transmisiones en vivo!
        </h2>

        <div
          class="aspect-w-16 aspect-h-9 w-full rounded-xl overflow-hidden shadow-2xl border-4 border-red-600 mx-auto"
          style="max-width: 900px;"
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

      <!-- Estadísticas Jugadores -->
      <div class="py-8 px-4 mt-20">
        <div
          class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 max-w-7xl mx-auto"
        >
          <EstadisticasJugador
            title="Goles"
            :players="golesPlayers"
            backgroundColor="#ff003c"
            clubLogo="/images/psg-logo.png"
            moreLink="/estadisticas/goles"
          />
          <EstadisticasJugador
            title="Asistencias"
            :players="asistenciasPlayers"
            backgroundColor="#0099ff"
            clubLogo="/images/manchester-city-logo.png"
            moreLink="/estadisticas/asistencias"
          />
          <EstadisticasJugador
            title="Pases"
            :players="pasesPlayers"
            backgroundColor="#00cc66"
            clubLogo="/images/real-madrid-logo.png"
            moreLink="/estadisticas/pases"
          />
        </div>
      </div>
    </template>
  </WelcomeLayout>
</template>
