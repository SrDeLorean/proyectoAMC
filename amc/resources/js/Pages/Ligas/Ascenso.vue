<script setup>
import WelcomeLayout from '@/Layouts/GuestLayout.vue';
import ResultadosLiga from '@/Components/Liga/ResultadosLiga.vue';

const jornadasAscenso = [
  { id: 1, name: 'Jornada 1' },
  { id: 2, name: 'Jornada 2' },
  { id: 3, name: 'Jornada 3' },
];

const resultadosAscenso = [
  {
    id: 1,
    jornadaId: 1,
    equipoLocal: { name: 'Ascenso Team A', logo: '/images/ascenso-a.png' },
    equipoVisitante: { name: 'Ascenso Team B', logo: '/images/ascenso-b.png' },
    golesLocal: 1,
    golesVisitante: 1,
    estado: 'Finalizado',
    fechaHora: '2025-06-08T17:30:00',
  },
  {
    id: 2,
    jornadaId: 2,
    equipoLocal: { name: 'Ascenso Team C', logo: '/images/ascenso-c.png' },
    equipoVisitante: { name: 'Ascenso Team D', logo: '/images/ascenso-d.png' },
    golesLocal: null,
    golesVisitante: null,
    estado: 'Próximo',
    fechaHora: '2025-06-15T17:30:00',
  },
  // Más resultados aquí...
];
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <ResultadosLiga
        nombreLiga="ASCENSO (División 2)"
        :jornadas="jornadasAscenso"
        :resultados="resultadosAscenso"
      />
    </template>
  </WelcomeLayout>
</template>
