<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const props = defineProps({
  estadisticaLocal: Object,
  estadisticaVisitante: Object,
  local: Object,
  visitante: Object,
  calendario: Object,
})

const imagenLocal = ref(null)
const imagenVisitante = ref(null)
const loadingLocal = ref(false)
const loadingVisitante = ref(false)

function handleFileChange(e, equipo) {
  const file = e.target.files && e.target.files[0]
  if (file) {
    if (equipo === 'local') imagenLocal.value = file
    else if (equipo === 'visitante') imagenVisitante.value = file
  }
}

function submitEstadisticas(equipo) {
  // Validaciones para evitar error al leer .id de undefined
  if (!props.calendario || !props.calendario.id) {
    alert('Error: Calendario no está definido.')
    return
  }

  if (equipo === 'local' && (!props.local || !props.local.id)) {
    alert('Error: Equipo local no está definido.')
    return
  }

  if (equipo === 'visitante' && (!props.visitante || !props.visitante.id)) {
    alert('Error: Equipo visitante no está definido.')
    return
  }

  let imagen, loading

  if (equipo === 'local') {
    if (!imagenLocal.value) return
    imagen = imagenLocal.value
    loadingLocal.value = true
  } else if (equipo === 'visitante') {
    if (!imagenVisitante.value) return
    imagen = imagenVisitante.value
    loadingVisitante.value = true
  } else {
    return
  }

  const formData = new FormData()
  formData.append('imagen', imagen)
  formData.append('calendario_id', props.calendario.id)
  formData.append('equipo_id', equipo === 'local' ? props.local.id : props.visitante.id)

  router.post(route('admin.estadisticas.subir'), formData, {
    preserveScroll: true,
    onFinish: () => {
      if (equipo === 'local') {
        imagenLocal.value = null
        loadingLocal.value = false
      } else if (equipo === 'visitante') {
        imagenVisitante.value = null
        loadingVisitante.value = false
      }
    },
  })
}
</script>

<template>
  <div
    class="bg-gradient-to-br from-black via-gray-900 to-black text-white rounded-2xl p-8 max-w-7xl mx-auto space-y-10 shadow-xl"
  >
    <div class="text-center text-3xl font-extrabold tracking-wide text-red-500">
      Estadísticas del Partido
    </div>

    <div class="grid grid-cols-3 items-start text-center text-sm font-medium gap-6">
      <!-- Local -->
      <div class="space-y-6">
        <div class="text-2xl font-bold text-red-400">{{ local?.nombre ?? 'Local' }}</div>

        <div v-if="estadisticaLocal" class="space-y-4">
          <div class="flex flex-col items-center">
            <div
              class="w-20 h-20 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold"
            >
              {{ estadisticaLocal.regates_exito ?? 0 }}%
            </div>
            <p class="mt-2 text-gray-300 text-sm">Tasa de éxito en regates</p>
          </div>
          <div class="flex flex-col items-center">
            <div
              class="w-20 h-20 border-4 border-yellow-400 rounded-full flex items-center justify-center text-2xl font-bold"
            >
              {{ estadisticaLocal.precision_tiros ?? 0 }}%
            </div>
            <p class="mt-2 text-gray-300 text-sm">Precisión en tiros</p>
          </div>
          <div class="flex flex-col items-center">
            <div
              class="w-20 h-20 border-4 border-green-400 rounded-full flex items-center justify-center text-2xl font-bold"
            >
              {{ estadisticaLocal.precision_pases ?? 0 }}%
            </div>
            <p class="mt-2 text-gray-300 text-sm">Precisión en pases</p>
          </div>
        </div>

        <form
          v-else
          @submit.prevent="submitEstadisticas('local')"
          class="flex flex-col items-center gap-3"
          enctype="multipart/form-data"
        >
          <input
            type="file"
            accept="image/*"
            @change="e => handleFileChange(e, 'local')"
            class="text-xs text-gray-300 file:mr-4 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700"
            :disabled="loadingLocal || !local?.id || !calendario?.id"
          />
          <button
            type="submit"
            :disabled="loadingLocal || !local?.id || !calendario?.id"
            class="bg-red-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loadingLocal ? 'Subiendo...' : 'Subir Estadísticas' }}
          </button>
        </form>
      </div>

      <!-- Centro -->
      <div
        class="text-sm space-y-3 text-gray-300 bg-gray-800/30 rounded-xl p-4 shadow-inner"
      >
        <div
          v-for="(label, key) in {
            posesion: '% de posesión',
            recuperacion_balon: 'Recuperación balones (seg)',
            tiros: 'Tiros',
            goles_esperados: 'Goles esperados (xG)',
            pases: 'Pases',
            entradas: 'Entradas',
            entradas_exito: 'Entradas con éxito',
            recuperaciones: 'Recuperaciones',
            atajadas: 'Atajadas',
            faltas_cometidas: 'Faltas cometidas',
            fuera_de_juego: 'Fueras de juego',
            tiros_esquina: 'Tiros de esquina',
            tiros_libres: 'Tiros libres',
            penales: 'Penales',
            tarjetas_amarillas: 'Tarjetas amarillas',
          }"
          :key="key"
          class="grid grid-cols-3 items-center gap-2"
        >
          <span class="text-white font-semibold">{{ estadisticaLocal?.[key] ?? 0 }}</span>
          <strong class="text-sm text-red-400">{{ label }}</strong>
          <span class="text-white font-semibold">{{ estadisticaVisitante?.[key] ?? 0 }}</span>
        </div>
      </div>

      <!-- Visitante -->
      <div class="space-y-6">
        <div class="text-2xl font-bold text-red-400">{{ visitante?.nombre ?? 'Visitante' }}</div>

        <div v-if="estadisticaVisitante" class="space-y-4">
          <div class="flex flex-col items-center">
            <div
              class="w-20 h-20 border-4 border-cyan-400 rounded-full flex items-center justify-center text-2xl font-bold"
            >
              {{ estadisticaVisitante.regates_exito ?? 0 }}%
            </div>
            <p class="mt-2 text-gray-300 text-sm">Tasa de éxito en regates</p>
          </div>
          <div class="flex flex-col items-center">
            <div
              class="w-20 h-20 border-4 border-yellow-400 rounded-full flex items-center justify-center text-2xl font-bold"
            >
              {{ estadisticaVisitante.precision_tiros ?? 0 }}%
            </div>
            <p class="mt-2 text-gray-300 text-sm">Precisión en tiros</p>
          </div>
          <div class="flex flex-col items-center">
            <div
              class="w-20 h-20 border-4 border-green-400 rounded-full flex items-center justify-center text-2xl font-bold"
            >
              {{ estadisticaVisitante.precision_pases ?? 0 }}%
            </div>
            <p class="mt-2 text-gray-300 text-sm">Precisión en pases</p>
          </div>
        </div>

        <form
          v-else
          @submit.prevent="submitEstadisticas('visitante')"
          class="flex flex-col items-center gap-3"
          enctype="multipart/form-data"
        >
          <input
            type="file"
            accept="image/*"
            @change="e => handleFileChange(e, 'visitante')"
            class="text-xs text-gray-300 file:mr-4 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700"
            :disabled="loadingVisitante || !visitante?.id || !calendario?.id"
          />
          <button
            type="submit"
            :disabled="loadingVisitante || !visitante?.id || !calendario?.id"
            class="bg-red-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loadingVisitante ? 'Subiendo...' : 'Subir Estadísticas' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
