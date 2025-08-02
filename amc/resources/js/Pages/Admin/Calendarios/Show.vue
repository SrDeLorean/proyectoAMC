<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

import Lineup from '@/Components/Liga/Lineup.vue'
import Clasificacion from '@/Components/Liga/Clasificacion.vue'
import EnVivo from '@/Components/Liga/EnVivo.vue'
import Estadistica from '@/Pages/Admin/Calendarios/Partials/Estadistica.vue'

const props = defineProps({
  calendario: Object,
  tablaClasificacion: Array,
  plantillaLocal: Array,
  plantillaVisitante: Array,
  estadistica_local: Object,
  estadistica_visitante: Object,
})

const local = computed(() => props.calendario.equipo_local || {})
const visitante = computed(() => props.calendario.equipo_visitante || {})

const localColor = computed(() => local.value.color_primario || '#007A33')
const visitanteColor = computed(() => visitante.value.color_primario || '#D71920')

const activeTab = ref('enVivo')

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

const twitchChannel = computed(() => props.calendario.twitch_channel || null)

// Cambiado a goles_equipo_local y goles_equipo_visitante:
const golesLocal = ref(props.calendario.goles_equipo_local ?? 0)
const golesVisitante = ref(props.calendario.goles_equipo_visitante ?? 0)

// Sincronizar refs con props.calendario cuando cambie (por si recarga)
watch(
  () => props.calendario,
  (newVal) => {
    golesLocal.value = newVal.goles_equipo_local ?? 0
    golesVisitante.value = newVal.goles_equipo_visitante ?? 0
  },
  { immediate: true, deep: true }
)

// Modal control
const showModal = ref(false)

// Guardar resultado vía PUT a backend
const guardarResultado = () => {
  router.put(
    route('admin.calendarios.update', props.calendario.id),
    {
      goles_equipo_local: golesLocal.value,
      goles_equipo_visitante: golesVisitante.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        showModal.value = false
      }
    }
  )
}
</script>

<template>
  <AdminLayout>
    <template #title>Partido: {{ local.nombre }} vs {{ visitante.nombre }}</template>

    <div
      class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-8 bg-gray-800 rounded-xl shadow space-y-6 text-white border-2 border-red-600"
    >
      <div class="text-gray-400 text-sm uppercase tracking-widest mb-4 font-semibold text-center">
        Jornada {{ calendario.jornada }} — Fecha:
        {{ new Date(calendario.fecha).toLocaleDateString() }}
      </div>

      <!-- Marcador con botón y modal -->
      <div class="flex items-center justify-center gap-6 md:gap-10 mb-8 flex-wrap">
        <!-- Botón cargar resultado -->
        <button
          @click="showModal = true"
          class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-1 px-2 rounded shadow transition duration-200"
        >
          Cargar resultado
        </button>

        <!-- Local -->
        <div class="flex flex-col items-center w-20">
          <img
            :src="local.logo.startsWith('/') ? local.logo : '/' + local.logo"
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
            :src="visitante.logo.startsWith('/') ? visitante.logo : '/' + visitante.logo"
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

      <!-- Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center"
      >
        <div
          class="bg-gray-900 p-6 rounded-xl w-full max-w-md space-y-4 border border-red-600"
        >
          <h2 class="text-xl font-bold text-white text-center mb-2">
            Cargar resultado del partido
          </h2>

          <div class="space-y-3">
            <div>
              <label
                class="text-sm font-medium text-gray-300 mb-1 block"
                >Goles {{ local.nombre }}</label
              >
              <input
                type="number"
                min="0"
                v-model.number="golesLocal"
                class="w-full bg-gray-800 text-white rounded px-3 py-2 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-600"
              />
            </div>
            <div>
              <label
                class="text-sm font-medium text-gray-300 mb-1 block"
                >Goles {{ visitante.nombre }}</label
              >
              <input
                type="number"
                min="0"
                v-model.number="golesVisitante"
                class="w-full bg-gray-800 text-white rounded px-3 py-2 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-600"
              />
            </div>
          </div>

          <div class="flex justify-between mt-4">
            <button
              @click="showModal = false"
              class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded"
            >
              Cancelar
            </button>
            <button
              @click="guardarResultado"
              class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded"
            >
              Guardar
            </button>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <nav
        class="flex gap-10 border-b border-gray-700 mb-6 justify-center flex-wrap"
      >
        <button
          @click="activeTab = 'enVivo'"
          :class="
            activeTab === 'enVivo'
              ? 'border-b-4 border-red-600 text-red-600'
              : 'text-gray-400 hover:text-red-600'
          "
          class="pb-2 text-lg font-semibold transition-colors"
        >
          En vivo
        </button>
        <button
          @click="activeTab = 'alineacion'"
          :class="
            activeTab === 'alineacion'
              ? 'border-b-4 border-red-600 text-red-600'
              : 'text-gray-400 hover:text-red-600'
          "
          class="pb-2 text-lg font-semibold transition-colors"
        >
          Alineación
        </button>
        <button
          @click="activeTab = 'estadisticas'"
          :class="
            activeTab === 'estadisticas'
              ? 'border-b-4 border-red-600 text-red-600'
              : 'text-gray-400 hover:text-red-600'
          "
          class="pb-2 text-lg font-semibold transition-colors"
        >
          Estadísticas
        </button>
        <button
          @click="activeTab = 'clasificacion'"
          :class="
            activeTab === 'clasificacion'
              ? 'border-b-4 border-red-600 text-red-600'
              : 'text-gray-400 hover:text-red-600'
          "
          class="pb-2 text-lg font-semibold transition-colors"
        >
          Clasificación
        </button>
      </nav>

      <section>
        <div v-if="activeTab === 'enVivo'">
          <EnVivo :channel-name="twitchChannel" />
        </div>

        <div v-if="activeTab === 'alineacion'">
          <Lineup
            :local="local"
            :visitante="visitante"
            :plantilla-local="plantillaLocal"
            :plantilla-visitante="plantillaVisitante"
          />
        </div>

        <div v-if="activeTab === 'estadisticas'">
          <Estadistica
            :calendario="calendario"
            :local="local"
            :visitante="visitante"
            :estadisticaLocal="estadistica_local"
            :estadisticaVisitante="estadistica_visitante"
          />
        </div>

        <div v-if="activeTab === 'clasificacion'">
          <Clasificacion
            :equipos="tablaClasificacion"
            :participantes="participantes"
          />
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
