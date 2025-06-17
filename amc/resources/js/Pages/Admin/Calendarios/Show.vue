<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'

import Lineup from '@/Components/Equipo/Lineup.vue'
import Clasificacion from '@/Components/Liga/Clasificacion.vue'

const props = defineProps({
  calendario: Object,
  tablaClasificacion: {
    type: Array,
    default: () => []
  },
})

const local = computed(() => props.calendario.equipo_local || {})
const visitante = computed(() => props.calendario.equipo_visitante || {})

const golesLocal = computed(() => props.calendario.goles_local ?? 0)
const golesVisitante = computed(() => props.calendario.goles_visitante ?? 0)

const localColor = computed(() => local.value.color_primario || '#007A33')
const visitanteColor = computed(() => visitante.value.color_primario || '#D71920')

const activeTab = ref('enVivo')

// IDs de los participantes
const participantes = computed(() => [
  {
    id: props.calendario.equipo_local?.id,
    color_primario: props.calendario.equipo_local?.color_primario || '#007A33'
  },
  {
    id: props.calendario.equipo_visitante?.id,
    color_primario: props.calendario.equipo_visitante?.color_primario || '#D71920'
  }
])
</script>

<template>
  <AdminLayout>
    <template #title>Partido: {{ local.nombre }} vs {{ visitante.nombre }}</template>

    <div
      class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-8 bg-gray-800 rounded-xl shadow space-y-6 text-white border-2 border-red-600"
    >
      <div
        class="text-gray-400 text-sm uppercase tracking-widest mb-4 font-semibold text-center"
      >
        Jornada {{ calendario.jornada }} — Fecha:
        {{ new Date(calendario.fecha).toLocaleDateString() }}
      </div>

      <div
        class="flex items-center justify-center gap-10 md:gap-20 mb-8 flex-wrap"
      >
        <!-- Local -->
        <div class="flex flex-col items-center w-20">
          <img
            :src="`/${local.logo}`"
            alt="Logo equipo local"
            class="w-[80px] min-w-[80px] h-auto object-contain flex-shrink-0"
          />
          <h2
            class="mt-3 text-white text-xl md:text-2xl font-bold tracking-tight truncate max-w-full text-center"
            :title="local.nombre"
          >
            {{ local.nombre }}
          </h2>
        </div>

        <!-- Marcador -->
        <div class="flex items-center gap-8 md:gap-12 select-none">
          <div
            class="w-16 h-16 rounded-full flex items-center justify-center text-white font-extrabold text-4xl shadow-lg"
            :style="{ backgroundColor: localColor }"
          >
            {{ golesLocal }}
          </div>
          <span class="text-5xl md:text-6xl font-bold text-gray-400">-</span>
          <div
            class="w-16 h-16 rounded-full flex items-center justify-center text-white font-extrabold text-4xl shadow-lg"
            :style="{ backgroundColor: visitanteColor }"
          >
            {{ golesVisitante }}
          </div>
        </div>

        <!-- Visitante -->
        <div class="flex flex-col items-center w-20">
          <img
            :src="`/${visitante.logo}`"
            alt="Logo equipo visitante"
            class="w-[80px] min-w-[80px] h-auto object-contain flex-shrink-0"
          />
          <h2
            class="mt-3 text-white text-xl md:text-2xl font-bold tracking-tight truncate max-w-full text-center"
            :title="visitante.nombre"
          >
            {{ visitante.nombre }}
          </h2>
        </div>
      </div>

      <nav
        class="flex gap-10 border-b border-gray-700 mb-6 justify-center flex-wrap"
      >
        <button
          @click="activeTab = 'enVivo'"
          :class="activeTab === 'enVivo' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-400 hover:text-red-600'"
          class="pb-2 text-lg font-semibold transition-colors"
        >
          En vivo
        </button>
        <button
          @click="activeTab = 'alineacion'"
          :class="activeTab === 'alineacion' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-400 hover:text-red-600'"
          class="pb-2 text-lg font-semibold transition-colors"
        >
          Alineación
        </button>
        <button
          @click="activeTab = 'estadisticas'"
          :class="activeTab === 'estadisticas' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-400 hover:text-red-600'"
          class="pb-2 text-lg font-semibold transition-colors"
        >
          Estadísticas
        </button>
        <button
          @click="activeTab = 'clasificacion'"
          :class="activeTab === 'clasificacion' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-400 hover:text-red-600'"
          class="pb-2 text-lg font-semibold transition-colors"
        >
          Clasificación
        </button>
      </nav>

      <section>
        <div
          v-if="activeTab === 'enVivo'"
          class="text-gray-400 text-center py-20 border border-gray-800 rounded-lg select-none"
        >
          <p>
            Contenido para: <strong>En Vivo</strong>
          </p>
        </div>

        <div v-if="activeTab === 'alineacion'">
          <Lineup :local="local" :visitante="visitante" />
        </div>

        <div
          v-if="activeTab === 'estadisticas'"
          class="text-gray-400 text-center py-20 border border-gray-800 rounded-lg select-none"
        >
          <p>
            Contenido para: <strong>Estadísticas</strong>
          </p>
        </div>

        <div v-if="activeTab === 'clasificacion'">
          <Clasificacion :equipos="tablaClasificacion" :participantes="participantes" />
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
