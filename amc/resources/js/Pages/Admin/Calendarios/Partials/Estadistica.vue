<template>
  <div class="p-6 space-y-6">
    <h2 class="text-2xl font-bold">OCR Estadísticas de Imagen</h2>

    <input type="file" @change="handleFileChange" accept="image/*" />

    <button
      @click="subirImagen"
      :disabled="!imagen || loading"
      class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
    >
      Subir Imagen
    </button>

    <div v-if="loading">Procesando imagen...</div>

    <div v-if="error" class="text-red-500">{{ error }}</div>

    <div v-if="result" class="space-y-4">
      <h3 class="text-xl font-semibold">Imágenes procesadas:</h3>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <p>Original</p>
          <img :src="`/${result.imagen_original_path}`" alt="Original" class="max-w-full" />
        </div>
        <div>
          <p>Procesada</p>
          <img :src="`/${result.imagen_procesada_path}`" alt="Procesada" class="max-w-full" />
        </div>
        <div v-for="(ruta, key) in result.imagen_cortes" :key="key">
          <p>{{ key }}</p>
          <img :src="`/${ruta}`" alt="Corte" class="max-w-full" />
        </div>
      </div>

      <h3 class="text-xl font-semibold mt-6">Datos de Equipos:</h3>

      <div class="grid grid-cols-2 gap-6">
        <!-- Equipo Izquierdo -->
        <div class="bg-gray-100 p-4 rounded">
          <h4 class="font-bold mb-2">Equipo Izquierdo</h4>
          <p><strong>Nombre:</strong> {{ result.datos.equipo_izquierdo.nombre || 'N/D' }}</p>

          <div v-if="result.datos.equipo_izquierdo.stats && Object.keys(result.datos.equipo_izquierdo.stats).length">
            <h5 class="font-semibold mt-3">Estadísticas:</h5>
            <ul class="list-disc list-inside">
              <li v-for="(valor, clave) in result.datos.equipo_izquierdo.stats" :key="clave">
                {{ clave }}: {{ valor }}
              </li>
            </ul>
          </div>

          <div
            v-if="result.datos.equipo_izquierdo.detalles && result.datos.equipo_izquierdo.detalles.lineas && result.datos.equipo_izquierdo.detalles.lineas.length"
          >
            <h5 class="font-semibold mt-3">Detalles:</h5>
            <ul class="list-disc list-inside">
              <li
                v-for="(linea, index) in result.datos.equipo_izquierdo.detalles.lineas"
                :key="index"
              >
                {{ linea }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Equipo Derecho -->
        <div class="bg-gray-100 p-4 rounded">
          <h4 class="font-bold mb-2">Equipo Derecho</h4>
          <p><strong>Nombre:</strong> {{ result.datos.equipo_derecho.nombre || 'N/D' }}</p>

          <div v-if="result.datos.equipo_derecho.stats && Object.keys(result.datos.equipo_derecho.stats).length">
            <h5 class="font-semibold mt-3">Estadísticas:</h5>
            <ul class="list-disc list-inside">
              <li v-for="(valor, clave) in result.datos.equipo_derecho.stats" :key="clave">
                {{ clave }}: {{ valor }}
              </li>
            </ul>
          </div>

          <div
            v-if="result.datos.equipo_derecho.detalles && result.datos.equipo_derecho.detalles.lineas && result.datos.equipo_derecho.detalles.lineas.length"
          >
            <h5 class="font-semibold mt-3">Detalles:</h5>
            <ul class="list-disc list-inside">
              <li
                v-for="(linea, index) in result.datos.equipo_derecho.detalles.lineas"
                :key="index"
              >
                {{ linea }}
              </li>
            </ul>
          </div>
        </div>
      </div>
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

function handleFileChange(e) {
  const file = e.target.files[0]
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
  });

  console.log('Datos recibidos:', res.data);
  result.value = res.data;
} catch (e) {
  error.value = e.response?.data?.error || 'Error inesperado';
} finally {
    loading.value = false
  }
}
</script>

<style scoped>
img {
  border: 1px solid #ccc;
  padding: 4px;
  background: white;
}
</style>
