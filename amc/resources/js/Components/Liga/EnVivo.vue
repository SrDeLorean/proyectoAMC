<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  canal_local: String,
  canal_visitante: String
})

const clientId = 'kimne78kx3ncx6brgo4mv6wki5h1ko'

const loading = ref(true)
const error = ref(null)

const localOnline = ref(false)
const visitanteOnline = ref(false)

async function checkStream(channel) {
  try {
    const res = await fetch(`https://api.twitch.tv/kraken/streams/${channel}`, {
      headers: {
        'Accept': 'application/vnd.twitchtv.v5+json',
        'Client-ID': clientId
      }
    })

    if (!res.ok) return false

    const data = await res.json()
    return data.stream !== null
  } catch {
    return false
  }
}

onMounted(async () => {
  loading.value = true
  error.value = null

  try {
    const [local, visitante] = await Promise.all([
      checkStream(props.canal_local),
      checkStream(props.canal_visitante)
    ])

    localOnline.value = local
    visitanteOnline.value = visitante
  } catch (e) {
    error.value = 'No se pudo conectar con Twitch.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="space-y-6">
    <div v-if="loading" class="flex items-center justify-center h-[200px] text-gray-400 bg-gray-900 rounded-lg border border-gray-700">
      <span>Cargando transmisiones...</span>
    </div>

    <div v-else-if="error" class="flex flex-col items-center justify-center h-[200px] text-red-500 bg-gray-900 rounded-lg border border-red-600 p-6">
      <p>Error:</p>
      <p class="mt-2 font-bold">{{ error }}</p>
    </div>

    <!-- LOCAL -->
    <div class="rounded-lg overflow-hidden border-2 border-red-600 bg-gray-900">
      <div class="text-sm text-gray-400 px-4 py-2 bg-gray-800 border-b border-gray-700 font-semibold uppercase tracking-widest">
        Transmisión equipo local: <span class="text-white">{{ canal_local }}</span>
      </div>
      <div v-if="localOnline" class="w-full h-[400px]">
        <iframe
          :src="`https://player.twitch.tv/?channel=${canal_local}&parent=${window.location.hostname}`"
          frameborder="0"
          allowfullscreen
          class="w-full h-full"
        ></iframe>
      </div>
      <div v-else class="flex items-center justify-center h-[400px] text-gray-400">
        <p>El canal <strong>{{ canal_local }}</strong> está offline.</p>
      </div>
    </div>

    <!-- VISITANTE -->
    <div class="rounded-lg overflow-hidden border-2 border-red-600 bg-gray-900">
      <div class="text-sm text-gray-400 px-4 py-2 bg-gray-800 border-b border-gray-700 font-semibold uppercase tracking-widest">
        Transmisión equipo visitante: <span class="text-white">{{ canal_visitante }}</span>
      </div>
      <div v-if="visitanteOnline" class="w-full h-[400px]">
        <iframe
          :src="`https://player.twitch.tv/?channel=${canal_visitante}&parent=${window.location.hostname}`"
          frameborder="0"
          allowfullscreen
          class="w-full h-full"
        ></iframe>
      </div>
      <div v-else class="flex items-center justify-center h-[400px] text-gray-400">
        <p>El canal <strong>{{ canal_visitante }}</strong> está offline.</p>
      </div>
    </div>
  </div>
</template>
