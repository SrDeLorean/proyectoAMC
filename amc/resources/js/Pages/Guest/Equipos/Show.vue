<script setup>
import { computed, reactive, ref, onMounted, onBeforeUnmount } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import FormacionCanchaHorizontal from '@/Components/Equipo/FormacionCanchaHorizontal.vue'
import FormacionCanchaVertical from '@/Components/Equipo/FormacionCanchaVertical.vue'
import PlantillaEquipo from '@/Components/Equipo/PlantillaEquipo.vue'
import EstadisticaEquipo from '@/Components/Equipo/EstadisticaEquipo.vue'
import EstadisticaJugadores from '@/Components/Equipo/EstadisticaJugadores.vue'

const props = defineProps({
  equipo: Object,
  plantilla: Array,
  estadistica_equipo: Object,
  estadistica_jugadores: Array,
})

const erroresImagenes = reactive({})
const erroresBanderas = reactive({})

function handleImageError(id) {
  erroresImagenes[id] = true
}
function handleFlagError(id) {
  erroresBanderas[id] = true
}

const activeTab = ref('plantilla')
const windowWidth = ref(window.innerWidth)

function onResize() {
  windowWidth.value = window.innerWidth
}

onMounted(() => {
  window.addEventListener('resize', onResize)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', onResize)
})

const esPantallaGrande = computed(() => windowWidth.value >= 768)
</script>

<template>
  <GuestLayout>
    <template #title>Equipo</template>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
      <div
        v-if="equipo"
        class="rounded p-6 shadow text-white flex flex-col md:flex-row md:items-start gap-4"
        :style="{ backgroundColor: equipo.color_primario || '#1F2937' }"
      >
        <div class="flex-1 text-center md:text-left">
          <h2 class="text-2xl sm:text-3xl font-bold truncate">{{ equipo.nombre }}</h2>
        </div>

        <div class="mx-auto md:mx-0">
          <div class="w-32 sm:w-40 aspect-square flex items-center justify-center">
            <img
              v-if="equipo.logo"
              :src="equipo.logo.startsWith('http') ? equipo.logo : (equipo.logo.startsWith('/') ? equipo.logo : `/${equipo.logo}`)"
              alt="Logo equipo"
              class="w-full h-full object-contain"
            />
            <div v-else class="text-gray-300 text-sm italic text-center py-10">Sin logo</div>
          </div>
        </div>

        <div class="flex-1 text-center md:text-right space-y-1">
          <p class="text-lg font-semibold">Dueño: {{ equipo.propietario?.name || '--' }}</p>
          <p class="text-lg font-semibold">Entrenador: {{ equipo.entrenador?.name || '--' }}</p>
        </div>
      </div>

      <nav
        class="flex overflow-x-auto no-scrollbar border-b border-gray-700 mb-6 -mx-4 px-4 sm:mx-0 sm:px-0"
        role="tablist"
        aria-label="Navegación de secciones"
      >
        <button
          @click="activeTab = 'plantilla'"
          :class="[
            'whitespace-nowrap py-2 px-4 font-semibold rounded-t',
            activeTab === 'plantilla'
              ? 'bg-red-600 text-white shadow'
              : 'text-gray-400 hover:text-red-600 hover:border-b-2 hover:border-red-600'
          ]"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'plantilla'"
          tabindex="0"
        >
          Plantilla
        </button>
        <button
          @click="activeTab = 'alineacion'"
          :class="[
            'whitespace-nowrap py-2 px-4 font-semibold rounded-t',
            activeTab === 'alineacion'
              ? 'bg-red-600 text-white shadow'
              : 'text-gray-400 hover:text-red-600 hover:border-b-2 hover:border-red-600'
          ]"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'alineacion'"
          tabindex="-1"
        >
          Alineación
        </button>
        <button
          @click="activeTab = 'estadisticaEquipo'"
          :class="[
            'whitespace-nowrap py-2 px-4 font-semibold rounded-t',
            activeTab === 'estadisticaEquipo'
              ? 'bg-red-600 text-white shadow'
              : 'text-gray-400 hover:text-red-600 hover:border-b-2 hover:border-red-600'
          ]"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'estadisticaEquipo'"
          tabindex="-1"
        >
          Estadística Equipo
        </button>
        <button
          @click="activeTab = 'estadisticaJugadores'"
          :class="[
            'whitespace-nowrap py-2 px-4 font-semibold rounded-t',
            activeTab === 'estadisticaJugadores'
              ? 'bg-red-600 text-white shadow'
              : 'text-gray-400 hover:text-red-600 hover:border-b-2 hover:border-red-600'
          ]"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'estadisticaJugadores'"
          tabindex="-1"
        >
          Estadística Jugador
        </button>
      </nav>

      <div>
        <PlantillaEquipo
          v-if="activeTab === 'plantilla'"
          :jugadores="plantilla"
          :key="'plantilla'"
        />

        <component
          v-if="activeTab === 'alineacion'"
          :is="esPantallaGrande ? FormacionCanchaHorizontal : FormacionCanchaVertical"
          :formacion="equipo?.formacion?.nombre"
          :plantilla="plantilla"
          :key="esPantallaGrande ? 'alineacion-horizontal' : 'alineacion-vertical'"
        />

        <EstadisticaEquipo
          v-if="activeTab === 'estadisticaEquipo'"
          :estadistica="estadistica_equipo"
          :key="'estadisticaEquipo'"
        />

        <EstadisticaJugadores
          v-if="activeTab === 'estadisticaJugadores'"
          :jugadores="plantilla"
          :key="'estadisticaJugadores'"
        />
      </div>
    </div>
  </GuestLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
