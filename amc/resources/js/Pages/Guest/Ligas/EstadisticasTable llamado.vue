<script setup>
import WelcomeLayout from '@/Layouts/GuestLayout.vue'
import EstadisticasTable from '@/Components/Liga/EstadisticasTable.vue'

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
    clubName: 'Real Madrid', // nombre del club para la columna adicional
  },
  {
    rank: 2,
    firstName: 'Erling',
    lastName: 'Haaland',
    playerImage: '/images/jugadores/haaland.png',
    playerName: 'Erling Haaland',
    statValue: 22,
    clubLogo: '/images/equipos/manchestercity.png', // logo para top 2 jugador
    clubName: 'Manchester City', // nombre del club para la columna adicional
  },
  {
    rank: 3,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png', // logo para top 3 jugador
    clubName: 'FC Barcelona', // nombre del club para la columna adicional
  },
  {
    rank: 4,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png', // logo para top 3 jugador
    clubName: 'FC Barcelona', // nombre del club para la columna adicional
  },
  {
    rank: 5,
    firstName: 'Robert',
    lastName: 'Lewandowski',
    playerImage: '/images/jugadores/lewandowski.png',
    playerName: 'Robert Lewandowski',
    statValue: 20,
    clubLogo: '/images/equipos/barcelona.png', // logo para top 3 jugador
    clubName: 'FC Barcelona', // nombre del club para la columna adicional
  },
]
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <div class="py-8 px-4">

          <EstadisticasTable
            title="Goles"
            :players="golesPlayers"
            backgroundColor="#ff003c"
            clubLogo="/images/psg-logo.png"
          />

      </div>
    </template>
  </WelcomeLayout>
</template>
