<script setup>
import { ref, watch } from 'vue'
import AppLayout from '@/Layouts/GuestLayout.vue'
import { router } from '@inertiajs/vue3'

import TabInicio from './Partials/Inicio.vue'
import TabClasificacion from './Partials/Clasificacion.vue'
import TabMaximos from './Partials/Maximos.vue'
import TabTotw from './Partials/TOTW.vue'
import TabCalendario from './Partials/Calendario.vue'

const props = defineProps({
  competencia: Object,
  temporadasCompetencias: Array,
  temporadaCompetencia: Object,
  equipos: Array,
  clasificacion: Array,
  maximos: Array,
  totw: Array,
  calendario: Array,
  jornadasDisponibles: Array,
  jornadaSeleccionada: [Number, String, null],
})

const temporadaSelected = ref(props.temporadaCompetencia?.id ?? null)
const activeTab = ref('inicio')

watch(temporadaSelected, (newVal) => {
  router.get(route('competencias.show', props.competencia.id), {
    temporada_id: newVal,
  })
})
</script>

<template>
  <AppLayout>
    <template #header>
      <h2
        class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-white leading-tight"
      >
        {{ competencia.nombre }}
      </h2>
    </template>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-10 space-y-10">
      <!-- Tarjeta superior -->
      <div
        class="flex flex-col md:flex-row items-center bg-gray-900 rounded-3xl shadow-2xl border-2 border-red-600 p-6 sm:p-8 gap-6 sm:gap-10"
      >
        <div class="flex-shrink-0">
          <img
            :src="`/${competencia.logo}`"
            alt="Logo competencia"
            class="w-40 h-40 sm:w-52 sm:h-52 object-contain rounded-xl border-4 border-red-700 shadow-lg transition-transform duration-300 hover:scale-110"
            loading="lazy"
          />
        </div>

        <div
          class="flex-1 flex flex-col gap-6 sm:gap-8 w-full max-w-md"
        >
          <div>
            <label
              for="temporada-select"
              class="block mb-2 sm:mb-3 text-base sm:text-lg font-semibold text-gray-300 tracking-wide"
            >
              Selecciona temporada
            </label>
            <select
              id="temporada-select"
              v-model="temporadaSelected"
              class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 sm:px-5 sm:py-3 text-white
                     focus:outline-none focus:ring-4 focus:ring-red-600 transition shadow-md"
            >
              <option
                v-for="tempComp in temporadasCompetencias"
                :key="tempComp.id"
                :value="tempComp.id"
              >
                {{ tempComp.nombre }}
              </option>
            </select>
          </div>

          <div
            v-if="temporadaCompetencia"
            class="bg-gray-800 rounded-xl p-4 sm:p-5 border border-red-600 shadow-inner text-gray-300"
          >
            <h3
              class="text-xl sm:text-2xl font-bold text-red-500 mb-2 tracking-wide"
            >
              {{ temporadaCompetencia.nombre }}
            </h3>
            <p class="leading-relaxed text-sm sm:text-lg">
              <span class="font-semibold">Inicio:</span>
              {{ new Date(temporadaCompetencia.fecha_inicio).toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' }) }}
              <br />
              <span class="font-semibold">Término:</span>
              {{ new Date(temporadaCompetencia.fecha_termino).toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' }) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Navegación de pestañas tipo pills -->
      <nav
        class="flex flex-wrap justify-center gap-3 sm:gap-6"
        aria-label="Tabs"
      >
        <button
          v-for="tab in [
            { name: 'inicio', label: 'Inicio' },
            { name: 'clasificacion', label: 'Clasificación' },
            { name: 'maximos', label: 'Máximos' },
            { name: 'totw', label: 'TOTW' },
            { name: 'calendario', label: 'Calendario' },
          ]"
          :key="tab.name"
          @click="activeTab = tab.name"
          :class="[
            'px-5 sm:px-8 py-2 sm:py-3 rounded-full font-semibold uppercase tracking-widest transition duration-300 select-none focus:outline-none whitespace-nowrap',
            activeTab === tab.name
              ? 'bg-red-600 text-white shadow-lg shadow-red-700/60 scale-105'
              : 'bg-gray-700 text-gray-300 hover:bg-red-600 hover:text-white hover:shadow-md hover:shadow-red-700/40',
          ]"
        >
          {{ tab.label }}
        </button>
      </nav>

      <!-- Contenido de la pestaña -->
      <section class="mt-8 min-h-[400px]">
        <TabInicio v-show="activeTab === 'inicio'" :equipos="equipos" />
        <TabClasificacion v-show="activeTab === 'clasificacion'" :tabla="clasificacion" />
        <TabMaximos v-show="activeTab === 'maximos'" :maximos="maximos" />
        <TabTotw v-show="activeTab === 'totw'" :totw="totw" />
        <TabCalendario
          v-show="activeTab === 'calendario'"
          :calendario="calendario"
          :jornadasDisponibles="jornadasDisponibles"
          :jornadaSeleccionada="jornadaSeleccionada"
          :competenciaId="competencia.id"
          :temporadaCompetenciaId="temporadaCompetencia.id"
        />
      </section>
    </div>
  </AppLayout>
</template>

<style scoped>
select option {
  background-color: #1f2937; /* bg-gray-800 */
}
</style>
