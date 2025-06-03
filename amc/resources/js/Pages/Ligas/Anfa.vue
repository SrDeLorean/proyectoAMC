<script setup>
import WelcomeLayout from '@/Layouts/GuestLayout.vue';
import ResultadosLiga from '@/Components/Liga/ResultadosLiga.vue';

const jornadasAnfa = [
  { id: 1, name: 'Jornada 1' },
  { id: 2, name: 'Jornada 2' },
];

const resultadosAnfa = [
  {
    id: 1,
    jornadaId: 1,
    equipoLocal: { name: 'Anfa Team A', logo: '/images/anfa-a.png' },
    equipoVisitante: { name: 'Anfa Team B', logo: '/images/anfa-b.png' },
    golesLocal: 0,
    golesVisitante: 2,
    estado: 'Finalizado',
    fechaHora: '2025-06-07T16:00:00',
  },
  {
    id: 2,
    jornadaId: 2,
    equipoLocal: { name: 'Anfa Team C', logo: '/images/anfa-c.png' },
    equipoVisitante: { name: 'Anfa Team D', logo: '/images/anfa-d.png' },
    golesLocal: null,
    golesVisitante: null,
    estado: 'Próximo',
    fechaHora: '2025-06-14T16:00:00',
  },
  // Más resultados aquí...
];
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <ResultadosLiga
        nombreLiga="ANFA (División 3)"
        :jornadas="jornadasAnfa"
        :resultados="resultadosAnfa"
      />
    </template>
  </WelcomeLayout>
</template>
