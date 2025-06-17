<script setup>
const props = defineProps({
  local: {
    type: Object,
    required: true
  },
  visitante: {
    type: Object,
    required: true
  }
})

// Esperamos que cada equipo tenga esta estructura:
// {
//   nombre: String,
//   jugadores: {
//     titular: Array,   // jugadores de la alineación titular
//     suplente: Array,  // jugadores en banca
//   },
//   director_tecnico: String
// }
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white">
    <!-- Equipo Local -->
    <section>
      <h3 class="text-2xl font-bold mb-4">{{ local.nombre }}</h3>

      <div class="mb-6">
        <h4 class="font-semibold text-lg mb-2">Once Inicial</h4>
        <ul class="list-disc list-inside space-y-1">
          <li v-for="jugador in local.jugadores.titular" :key="jugador.id">
            {{ jugador.numero }} - {{ jugador.nombre }}
          </li>
        </ul>
      </div>

      <div class="mb-6">
        <h4 class="font-semibold text-lg mb-2">Banca</h4>
        <ul class="list-disc list-inside space-y-1">
          <li v-for="jugador in local.jugadores.suplente" :key="jugador.id">
            {{ jugador.numero }} - {{ jugador.nombre }}
          </li>
        </ul>
      </div>

      <div>
        <h4 class="font-semibold text-lg mb-1">Director Técnico</h4>
        <p>{{ local.director_tecnico }}</p>
      </div>
    </section>

    <!-- Equipo Visitante -->
    <section>
      <h3 class="text-2xl font-bold mb-4">{{ visitante.nombre }}</h3>

      <div class="mb-6">
        <h4 class="font-semibold text-lg mb-2">Once Inicial</h4>
        <ul class="list-disc list-inside space-y-1">
          <li v-for="jugador in visitante.jugadores.titular" :key="jugador.id">
            {{ jugador.numero }} - {{ jugador.nombre }}
          </li>
        </ul>
      </div>

      <div class="mb-6">
        <h4 class="font-semibold text-lg mb-2">Banca</h4>
        <ul class="list-disc list-inside space-y-1">
          <li v-for="jugador in visitante.jugadores.suplente" :key="jugador.id">
            {{ jugador.numero }} - {{ jugador.nombre }}
          </li>
        </ul>
      </div>

      <div>
        <h4 class="font-semibold text-lg mb-1">Director Técnico</h4>
        <p>{{ visitante.director_tecnico }}</p>
      </div>
    </section>
  </div>
</template>
