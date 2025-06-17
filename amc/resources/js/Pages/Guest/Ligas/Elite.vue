<script setup>
import WelcomeLayout from '@/Layouts/GuestLayout.vue';
import ResultadosLiga from '@/Components/Liga/ResultadosLiga.vue';

const jornadasElite = [
  { id: 1, name: 'Jornada 1' },
  { id: 2, name: 'Jornada 2' },
];

const resultadosElite = [
  {
    id: 1,
    jornadaId: 1,
    equipoLocal: { name: 'Elite Team A', logo: '/images/elite-a.png' },
    equipoVisitante: { name: 'Elite Team B', logo: '/images/elite-b.png' },
    golesLocal: 3,
    golesVisitante: 2,
    estado: 'Finalizado',
    fechaHora: '2025-06-09T18:00:00',
  },
  // Más resultados...
];


</script>

<template>
  <WelcomeLayout>
    <template #default>
      <ResultadosLiga
        nombreLiga="ELITE (División 1)"
        :jornadas="jornadasElite"
        :resultados="resultadosElite"
      />
    </template>
  </WelcomeLayout>
</template>
