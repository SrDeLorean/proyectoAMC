<script setup>
import { ref } from 'vue'
import axios from 'axios'

const imagen = ref(null)
const loading = ref(false)
const error = ref('')

const textoCompleto = ref('')
const equipoIzquierdo = ref(null)
const equipoDerecho = ref(null)

function handleFileChange(e) {
  imagen.value = e.target.files?.[0] || null
}

async function procesarImagen() {
  if (!imagen.value) {
    alert('Debes seleccionar una imagen primero.')
    return
  }

  loading.value = true
  error.value = ''
  textoCompleto.value = ''
  equipoIzquierdo.value = null
  equipoDerecho.value = null

  const formData = new FormData()
  formData.append('imagen', imagen.value)

  try {
    const res = await axios.post('/admin/estadisticas/subir', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    textoCompleto.value = res.data.texto_completo || ''
    equipoIzquierdo.value = res.data.datos_equipo_izquierdo || null
    equipoDerecho.value = res.data.datos_equipo_derecho || null

  } catch (err) {
    error.value = err.response?.data?.error || 'Error desconocido'
  } finally {
    loading.value = false
    imagen.value = null
  }
}

const estadisticasNombres = [
  'goles',
  'posesion',
  'tasa_de_exito_en_regates',
  'precision_en_tiros',
  'precision_de_pases',
  'recuperacion_de_balon',
  'tiros',
  'goles_esperados',
  'pases',
  'entradas',
  'entradas_con_exito',
  'recuperaciones',
  'atajadas',
  'faltas_cometidas',
  'fueras_de_lugar',
  'tiros_de_esquina',
  'tiros_libres',
  'penales',
  'tarjetas_amarillas',
]
</script>

<template>
  <div class="max-w-4xl mx-auto p-6 bg-gray-900 text-white rounded-xl shadow space-y-6">
    <h2 class="text-2xl font-bold text-red-500 text-center">Subir Imagen para OCR</h2>

    <input
      type="file"
      accept="image/*"
      @change="handleFileChange"
      :disabled="loading"
      class="block w-full text-sm text-gray-300 file:py-2 file:px-4 file:border-0 file:rounded-full file:bg-red-600 file:text-white file:font-semibold hover:file:bg-red-700 cursor-pointer"
    />

    <button
      @click="procesarImagen"
      :disabled="loading || !imagen"
      class="w-full bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-2 rounded-lg transition"
    >
      {{ loading ? 'Procesando...' : 'Procesar Imagen' }}
    </button>

    <div v-if="error" class="text-red-500 font-semibold text-center">{{ error }}</div>

    <section v-if="textoCompleto" class="bg-gray-800 rounded p-4 text-sm whitespace-pre-wrap">
      <strong class="block text-green-400 mb-2">Texto extraído:</strong>
      {{ textoCompleto }}
    </section>

    <div v-if="equipoIzquierdo && equipoDerecho" class="bg-black text-white p-6 rounded-xl shadow-lg">
      <h2 class="text-2xl font-bold text-center text-red-500 mb-6">Estadísticas del Partido</h2>

      <div class="grid grid-cols-3 gap-4 text-center">
        <!-- Equipo Izquierdo -->
        <div class="space-y-2 text-right pr-2 border-r border-gray-700">
          <div class="font-bold text-lg">{{ equipoIzquierdo.nombre || 'Equipo Izquierdo' }}</div>
          <div v-for="stat in estadisticasNombres" :key="'izq-'+stat">
            {{ equipoIzquierdo[stat] ?? '-' }}
          </div>
        </div>

        <!-- Nombres estadisticas -->
        <div class="space-y-2 text-gray-400 font-medium">
          <div>&nbsp;</div>
          <div>Goles</div>
          <div>Posesión</div>
          <div>Tasa de éxito en regates</div>
          <div>Precisión en tiros</div>
          <div>Precisión de pases</div>
          <div>Recuperación de balón</div>
          <div>Tiros</div>
          <div>Goles esperados</div>
          <div>Pases</div>
          <div>Entradas</div>
          <div>Entradas con éxito</div>
          <div>Recuperaciones</div>
          <div>Atajadas</div>
          <div>Faltas cometidas</div>
          <div>Fueras de lugar</div>
          <div>Tiros de esquina</div>
          <div>Tiros libres</div>
          <div>Penales</div>
          <div>Tarjetas amarillas</div>
        </div>

        <!-- Equipo Derecho -->
        <div class="space-y-2 text-left pl-2 border-l border-gray-700">
          <div class="font-bold text-lg">{{ equipoDerecho.nombre || 'Equipo Derecho' }}</div>
          <div v-for="stat in estadisticasNombres" :key="'der-'+stat">
            {{ equipoDerecho[stat] ?? '-' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
