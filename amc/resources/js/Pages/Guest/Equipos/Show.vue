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

const colorEquipo = computed(() => props.equipo?.color_primario || '#dc2626')

function onMouseEnter(tab, event) {
  if (activeTab.value !== tab) {
    event.currentTarget.style.color = colorEquipo.value
  }
}
function onMouseLeave(tab, event) {
  if (activeTab.value !== tab) {
    event.currentTarget.style.color = '#9ca3af'
  }
}

const sombraActivo = computed(() => `0 0 8px ${colorEquipo.value}`)
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
        class="flex overflow-x-auto no-scrollbar border-b mb-6 -mx-4 px-4 sm:mx-0 sm:px-0"
        :style="{ borderColor: colorEquipo }"
        role="tablist"
        aria-label="Navegación de secciones"
      >
        <button
          @click="activeTab = 'plantilla'"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'plantilla'"
          tabindex="0"
          :style="activeTab === 'plantilla'
            ? { backgroundColor: colorEquipo, color: 'white', boxShadow: sombraActivo }
            : { color: '#9ca3af', borderBottom: '2px solid transparent' }"
          @mouseenter="(e) => onMouseEnter('plantilla', e)"
          @mouseleave="(e) => onMouseLeave('plantilla', e)"
          class="whitespace-nowrap py-2 px-4 font-semibold rounded-t"
        >
          Plantilla
        </button>

        <button
          @click="activeTab = 'alineacion'"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'alineacion'"
          tabindex="-1"
          :style="activeTab === 'alineacion'
            ? { backgroundColor: colorEquipo, color: 'white', boxShadow: sombraActivo }
            : { color: '#9ca3af', borderBottom: '2px solid transparent' }"
          @mouseenter="(e) => onMouseEnter('alineacion', e)"
          @mouseleave="(e) => onMouseLeave('alineacion', e)"
          class="whitespace-nowrap py-2 px-4 font-semibold rounded-t"
        >
          Alineación
        </button>

        <button
          @click="activeTab = 'estadisticaEquipo'"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'estadisticaEquipo'"
          tabindex="-1"
          :style="activeTab === 'estadisticaEquipo'
            ? { backgroundColor: colorEquipo, color: 'white', boxShadow: sombraActivo }
            : { color: '#9ca3af', borderBottom: '2px solid transparent' }"
          @mouseenter="(e) => onMouseEnter('estadisticaEquipo', e)"
          @mouseleave="(e) => onMouseLeave('estadisticaEquipo', e)"
          class="whitespace-nowrap py-2 px-4 font-semibold rounded-t"
        >
          Estadística Equipo
        </button>

        <button
          @click="activeTab = 'estadisticaJugadores'"
          type="button"
          role="tab"
          :aria-selected="activeTab === 'estadisticaJugadores'"
          tabindex="-1"
          :style="activeTab === 'estadisticaJugadores'
            ? { backgroundColor: colorEquipo, color: 'white', boxShadow: sombraActivo }
            : { color: '#9ca3af', borderBottom: '2px solid transparent' }"
          @mouseenter="(e) => onMouseEnter('estadisticaJugadores', e)"
          @mouseleave="(e) => onMouseLeave('estadisticaJugadores', e)"
          class="whitespace-nowrap py-2 px-4 font-semibold rounded-t"
        >
          Estadística Jugador
        </button>
      </nav>

      <div>
        <PlantillaEquipo
          v-if="activeTab === 'plantilla'"
          :jugadores="plantilla"
          :colorEquipo="colorEquipo"
          :key="'plantilla'"
        />

        <component
          v-if="activeTab === 'alineacion'"
          :is="esPantallaGrande ? FormacionCanchaHorizontal : FormacionCanchaVertical"
          :formacion="equipo?.formacion?.nombre"
          :plantilla="plantilla"
          :colorEquipo="colorEquipo"
          :key="esPantallaGrande ? 'alineacion-horizontal' : 'alineacion-vertical'"
        />

        <EstadisticaEquipo
          v-if="activeTab === 'estadisticaEquipo'"
          :estadistica="estadistica_equipo"
          :colorEquipo="colorEquipo"
          :key="'estadisticaEquipo'"
        />

        <EstadisticaJugadores
          v-if="activeTab === 'estadisticaJugadores'"
          :jugadores="plantilla"
          :colorEquipo="colorEquipo"
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
