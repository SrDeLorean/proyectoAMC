<template>
  <EntrenadorLayout>
    <template #header>
      <h1 class="text-2xl font-bold">Plantilla - Mis Jugadores</h1>
    </template>

    <table class="table-auto w-full text-white mt-4">
      <thead>
        <tr>
          <th>Jugador</th>
          <th>Equipo</th>
          <th>Rol</th>
          <th>Posición</th>
          <th>Número</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="plantilla in plantillas" :key="plantilla.id">
          <td>{{ plantilla.jugador.name }}</td>
          <td>{{ plantilla.equipo?.nombre || 'Sin equipo' }}</td>
          <td>{{ plantilla.rol }}</td>
          <td>{{ plantilla.posicion }}</td>
          <td>{{ plantilla.numero }}</td>
          <td>
            <button
              @click="irEditar(plantilla.id)"
              class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
            >
              Editar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </EntrenadorLayout>
</template>

<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  plantillas: Array,
})

function irEditar(id) {
  router.visit(route('entrenador.plantillas.edit', id))
}
</script>
