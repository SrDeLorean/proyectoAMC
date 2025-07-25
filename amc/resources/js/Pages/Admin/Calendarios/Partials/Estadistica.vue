<template>
  <div
    class="bg-gradient-to-br from-black via-gray-900 to-black text-white rounded-2xl p-8 max-w-7xl mx-auto space-y-10 shadow-xl"
  >
    <h2 class="text-center text-3xl font-extrabold tracking-wide text-red-500">
      OCR Estadísticas de Imagen
    </h2>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <input
        type="file"
        @change="handleFileChange"
        accept="image/*"
        class="text-gray-300 file:mr-4 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700"
        :disabled="loading"
      />
      <button
        @click="subirImagen"
        :disabled="!imagen || loading"
        class="px-6 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {{ loading ? 'Procesando...' : 'Subir Imagen' }}
      </button>
    </div>

    <div v-if="error" class="mt-4 text-center text-red-500 font-semibold">
      {{ error }}
    </div>

    <div
      v-if="result"
      class="mt-8 overflow-auto max-h-[600px] border border-gray-700 rounded-lg shadow-inner"
    >
      <table class="min-w-full border-collapse border border-gray-700 text-white">
        <thead class="bg-gray-800 sticky top-0">
          <tr>
            <th class="border border-gray-600 px-4 py-2 text-left text-red-400">Métrica</th>
            <th class="border border-gray-600 px-4 py-2 text-left bg-gray-900">
              {{ result.datos?.equipo_izquierdo?.nombre || 'Equipo Izquierdo' }}
            </th>
            <th class="border border-gray-600 px-4 py-2 text-left bg-gray-900">
              {{ result.datos?.equipo_derecho?.nombre || 'Equipo Derecho' }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(metrica, index) in metricasOrdenadas"
            :key="index"
            class="hover:bg-gray-700 transition"
          >
            <td class="border border-gray-600 px-4 py-2 font-semibold">
              {{ labels[metrica] || metrica.replace(/_/g, ' ') }}
            </td>
            <td class="border border-gray-600 px-4 py-2">
              {{ getDatoEquipo('equipo_izquierdo', metrica) }}
            </td>
            <td class="border border-gray-600 px-4 py-2">
              {{ getDatoEquipo('equipo_derecho', metrica) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue'
import axios from 'axios'

const imagen = ref(null)
const result = ref(null)
const loading = ref(false)
const error = ref(null)

// Orden y claves exactas que coinciden con los datos que tienes
const metricasOrdenadas = [
  'posesion',
  'recuperacion_balon',
  'tiros',
  'goles_esperados',
  'pases',
  'entradas',
  'entradas_con_exito',
  'recuperaciones',
  'atajadas',
  'faltas_cometidas',
  'fuera_de_lugar',
  'tiros_de_esquina',
  'tiros_libres',
  'penales',
  'tarjetas_amarillas',
  'regates_exitosos',
  'precision_tiros',
  'precision_pases',
]

// Labels para mostrar en tabla, con texto más amigable
const labels = {
  posesion: '% de posesión',
  recuperacion_balon: 'Recuperación de balón (seg.)',
  tiros: 'Tiros',
  goles_esperados: 'Goles esperados',
  pases: 'Pases',
  entradas: 'Entradas',
  entradas_con_exito: 'Entradas con éxito',
  recuperaciones: 'Recuperaciones',
  atajadas: 'Atajadas',
  faltas_cometidas: 'Faltas cometidas',
  fuera_de_lugar: 'Fueras de lugar',
  tiros_de_esquina: 'Tiros de esquina',
  tiros_libres: 'Tiros libres',
  penales: 'Penales',
  tarjetas_amarillas: 'Tarjetas amarillas',
  regates_exitosos: 'Tasa de éxito en regates',
  precision_tiros: 'Precisión en tiros',
  precision_pases: 'Precisión en pases',
}

function getDatoEquipo(equipo, metrica) {
  if (!result.value?.datos?.[equipo]) return 'N/D'

  if (metrica === 'nombre') {
    return result.value.datos[equipo]?.nombre || 'N/D'
  }

  const stats = result.value.datos[equipo]?.stats || {}

  if (metrica in stats) {
    return stats[metrica] ?? 'N/D'
  } else if (metrica === 'tarjetas_amarillas' && 'tarjetas_amarillas' in stats) {
    return stats['tarjetas_amarillas'] ?? 'N/D'
  }

  return 'N/D'
}

function handleFileChange(e) {
  const file = e.target.files?.[0]
  if (file) {
    imagen.value = file
    result.value = null
    error.value = null
  }
}

async function subirImagen() {
  if (!imagen.value) return

  loading.value = true
  error.value = null

  const formData = new FormData()
  formData.append('imagen', imagen.value)

  try {
    const res = await axios.post('/admin/estadisticas/subir', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    result.value = res.data
  } catch (e) {
    error.value = e.response?.data?.error || 'Error inesperado'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
  max-width: 900px;
}

th,
td {
  border: 1px solid #4b5563; /* gray-600 */
  padding: 0.5rem 1rem;
  text-align: left;
}

th {
  background-color: #1f2937; /* gray-800 */
}

tbody tr:hover {
  background-color: #374151; /* gray-700 */
  transition: background-color 0.2s ease-in-out;
}
</style>
