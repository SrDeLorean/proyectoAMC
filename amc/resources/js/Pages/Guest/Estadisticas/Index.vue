<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import TopJugador from './Partials/TopJugador.vue'

const props = defineProps({
  topEstadisticas: Object,
})

const etiquetasMetricas = {
  calificacion: 'Calificación',
  goles: 'Goles',
  asistencias: 'Asistencias',
  tiros: 'Tiros',
  precision_tiros: 'Precisión Tiros',
  pases: 'Pases',
  precision_pases: 'Precisión Pases',
  regates: 'Regates',
  tasa_exito_entradas: 'Tasa Éxito Entradas',
  fueras_de_juego: 'Fueras de Juego',
  faltas_cometidas: 'Faltas Cometidas',
  posesion_ganada: 'Posesión Ganada',
  posesion_perdida: 'Posesión Perdida',
  minutos_jugados_vs_equipo: 'Minutos Jugados',
  distancia_total_vs_equipo: 'Distancia Total',
  distancia_carrera_vs_equipo: 'Distancia Carrera',
}

function getClubLogo(clubLogo) {
  return clubLogo || 'https://via.placeholder.com/50x50?text=Libre'
}

const estadisticasList = Object.entries(props.topEstadisticas).map(([metrica, data]) => {
  const players = data.players.map(player => ({
    rank: player.rank,
    nombre: player.nombre,
    playerName: player.nombre,
    playerImage: player.playerImage || '',
    clubLogo: getClubLogo(player.clubLogo),
    statValue: player.statValue ?? 0,
  }))

  return {
    title: data.title || etiquetasMetricas[metrica] || metrica,
    players,
    backgroundColor: data.clubColor || '#b91c1c',
    moreLink: `/estadisticas/jugadores?metrica=${metrica}`,
  }
})
</script>

<template>
  <GuestLayout>
    <section class="max-w-6xl mx-auto px-4 py-12 select-none">
      <h1
        class="text-4xl font-extrabold text-center text-red-600 mb-12 drop-shadow-[0_0_10px_rgba(220,38,38,0.8)] tracking-wide"
      >
        Estadísticas Destacadas
      </h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <TopJugador
          v-for="statBlock in estadisticasList"
          :key="statBlock.title"
          :title="statBlock.title"
          :players="statBlock.players"
          :backgroundColor="statBlock.backgroundColor"
          :moreLink="statBlock.moreLink"
        />
      </div>
    </section>
  </GuestLayout>
</template>
