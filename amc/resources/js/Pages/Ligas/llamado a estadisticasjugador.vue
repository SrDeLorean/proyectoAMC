<script setup>
import WelcomeLayout from '@/Layouts/GuestLayout.vue'
import EstadisticasJugador from '@/Components/Liga/EstadisticasJugador.vue'

// Datos simulados para 3 categorías, cada uno con 3 jugadores (top 1 + 2)
const golesPlayers = [
  {
    rank: 1,
    firstName: 'Kylian',
    lastName: 'Mbappé',
    playerImage: '/images/jugadores/mbappe.png',
    playerName: 'Kylian Mbappé',
    statValue: 28,
    clubLogo: '/images/equipos/realmadrid.png', // logo para top 1 jugador
  },
  {
    rank: 2,
    firstName: 'Erling',
    lastName: 'Haaland',
    playerImage: '/images/jugadores/haaland.png',
    playerName: 'Erling Haaland',
    statValue: 22,
    clubLogo: '/images/equipos/manchestercity.png', // logo para top 2 jugador
  },
  {
    rank: 3,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png', // logo para top 3 jugador
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
    clubLogo: '/images/equipos/manchestercity.png', // logo para top 2 jugador
  },
  {
    rank: 2,
    firstName: 'Kylian',
    lastName: 'Mbappé',
    playerImage: '/images/jugadores/mbappe.png',
    playerName: 'Kylian Mbappé',
    statValue: 30,
    clubLogo: '/images/equipos/realmadrid.png', // logo para top 1 jugador
  },
  {
    rank: 3,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png', // logo para top 3 jugador
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
    clubLogo: '/images/equipos/barcelona.png', // logo para top 3 jugador
  },
  {
    rank: 2,
    firstName: 'Erling',
    lastName: 'Haaland',
    playerImage: '/images/jugadores/haaland.png',
    playerName: 'Erling Haaland',
    statValue: 1256,
    clubLogo: '/images/equipos/manchestercity.png', // logo para top 2 jugador
  },
  {
    rank: 3,
    firstName: 'Kylian',
    lastName: 'Mbappé',
    playerImage: '/images/jugadores/mbappe.png',
    playerName: 'Kylian Mbappé',
    statValue: 1100,
    clubLogo: '/images/equipos/realmadrid.png', // logo para top 1 jugador
  },

]
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <div class="space-y-8 max-w-3xl mx-auto py-8">
        <EstadisticasJugador
          title="Goles"
          :players="golesPlayers"
          backgroundColor="#ff003c"
          clubLogo="/images/psg-logo.png"
        />
        <EstadisticasJugador
          title="Asistencias"
          :players="asistenciasPlayers"
          backgroundColor="#0099ff"
          clubLogo="/images/manchester-city-logo.png"
        />
        <EstadisticasJugador
          title="Pases"
          :players="pasesPlayers"
          backgroundColor="#00cc66"
          clubLogo="/images/real-madrid-logo.png"
        />
      </div>
    </template>
  </WelcomeLayout>
</template>
