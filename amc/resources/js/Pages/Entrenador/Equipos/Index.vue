<script setup>
import { ref } from 'vue'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import BaseTable from '@/Components/Table/DataTable.vue'

const props = defineProps({
  equipo: Object,
  plantilla: Array,
  jugador: Object,
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
  <EntrenadorLayout>
    <template #title>Mi Equipo</template>

    <div class="p-6 space-y-6">
      <div v-if="equipo" class="bg-gray-800 rounded p-4 text-white shadow">
        <h2 class="text-2xl font-bold">{{ equipo.nombre }}</h2>
        <p class="text-sm mt-1" v-if="equipo.ciudad">Ciudad: {{ equipo.ciudad }}</p>
        <p class="text-sm" v-if="equipo.estadio">Estadio: {{ equipo.estadio }}</p>
        <p class="text-sm" v-if="equipo.formacion">Formación: {{ equipo.formacion.nombre }}</p>
      </div>

      <div v-else class="text-white p-4 bg-red-700 rounded">
        No tienes equipos asignados como entrenador.
      </div>

      <div v-if="plantilla.length > 0">
        <h3 class="text-xl text-white font-semibold mb-4">Jugadores Inscritos</h3>
        <BaseTable
          :columns="columns"
          :rows="plantilla"
          :meta="null"
          :links="null"
          :actions="[]"
          v-model:filterText="search"
          v-model:perPage="perPage"
        >
          <template #cell-jugador\.foto="{ row }">
            <img
              v-if="row.jugador?.foto"
              :src="row.jugador.foto"
              alt="Foto jugador"
              class="w-10 h-10 rounded-full object-cover"
            />
            <div v-else class="w-10 h-10 bg-gray-600 rounded-full"></div>
          </template>
        </BaseTable>
      </div>

      <div v-else class="text-gray-400">No hay jugadores inscritos en este equipo.</div>
    </div>
  </EntrenadorLayout>
</template>
