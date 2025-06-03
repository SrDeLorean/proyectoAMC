<template>
    <WelcomeLayout>
        <template #default>
            <div class="container mx-auto p-4 space-y-8">
                <!-- Componente tabla -->
                <EstadisticasTable
                :title="'Estadísticas detalladas'"
                :players="players"
                moreLink="/estadisticas/detalladas"
                @playerClick="onTablePlayerClick"
                />
            </div>
        </template>
    </WelcomeLayout>
</template>

<script setup>
import EstadisticasTable from '@/components/Liga/EstadisticasTable.vue'
import WelcomeLayout from '@/Layouts/GuestLayout.vue'

// Datos ejemplo con 3 jugadores para validar el props
const players = [
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

function onTopPlayerClick() {
  console.log('Se hizo click en el componente TopPlayersCard')
}

function onTablePlayerClick(player) {
  alert(`Jugador seleccionado: ${player.firstName} ${player.lastName}`)
}
</script>

<style scoped>
.container {
  max-width: 640px;
}
</style>
