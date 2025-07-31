<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import JugadorLayout from '@/Layouts/JugadorLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  equipo: Object,
  plantilla: Array,
  mensaje: String,
})

const columns = [
  { label: 'ID', key: 'id' },
  { label: 'Foto', key: 'jugador.foto', isImage: true },
  { label: 'Nombre', key: 'jugador.name' },
  { label: 'Posición', key: 'posicion' },
  { label: 'Número', key: 'numero' },
]

const perPage = ref(10)
const search = ref('')
</script>

<template>
  <JugadorLayout>
    <template #title>Mi Equipo</template>

    <div class="p-6 space-y-6">

      <!-- Si no tiene equipo asignado -->
      <div v-if="!equipo" class="text-center text-gray-300 bg-gray-800 p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Sin equipo asignado</h2>
        <p>{{ mensaje || 'Aún no formas parte de un equipo. Espera a ser asignado por un entrenador.' }}</p>
      </div>

      <!-- Información del equipo -->
      <template v-else>
        <div class="bg-gray-900 rounded p-4 text-gray-100 shadow-lg">
          <h2 class="text-2xl font-bold">{{ equipo.nombre }}</h2>
          <p class="text-sm mt-1 text-gray-300">Ciudad: {{ equipo.ciudad }}</p>
          <p class="text-sm text-gray-300">Estadio: {{ equipo.estadio }}</p>
        </div>

        <!-- Tabla de plantilla -->
        <div>
          <h3 class="text-xl text-gray-100 font-semibold mb-4">Jugadores Inscritos</h3>
          <BaseTable
            :columns="columns"
            :rows="plantilla"
            :meta="null"
            :links="null"
            :actions="[]"
            v-model:filterText="search"
            v-model:perPage="perPage"
            class="bg-gray-800 rounded shadow-md"
          >
            <template #cell-jugador\.foto="{ row }">
              <img
                v-if="row.jugador?.foto"
                :src="row.jugador.foto"
                alt="Foto jugador"
                class="w-10 h-10 rounded-full object-cover"
              />
              <div v-else class="w-10 h-10 bg-gray-700 rounded-full"></div>
            </template>
          </BaseTable>
        </div>
      </template>
    </div>
  </JugadorLayout>
</template>
