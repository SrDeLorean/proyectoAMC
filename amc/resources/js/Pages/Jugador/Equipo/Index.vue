<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import JugadorLayout from '@/Layouts/JugadorLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  equipo: Object,
  plantilla: Array,
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
      <!-- Información del equipo -->
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
    </div>
  </JugadorLayout>
</template>
