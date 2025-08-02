<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'

const props = defineProps({
  equipos: Array,
  jugadores: Array,
})

const equipoEntrenador = props.equipos.length > 0 ? props.equipos[0] : null

const form = useForm({
  id_jugador: '',
  id_origen: '',
  id_destino: equipoEntrenador ? equipoEntrenador.id : '',
  motivo: '',
})

const estadoJugador = ref('')
const jugadorSeleccionado = ref(null)

watch(
  () => form.id_jugador,
  (nuevoId) => {
    if (!nuevoId) {
      estadoJugador.value = ''
      form.id_origen = ''
      jugadorSeleccionado.value = null
      return
    }

    const jugador = props.jugadores.find((j) => j.id === nuevoId)
    jugadorSeleccionado.value = jugador || null

    if (jugador?.equipo_actual) {
      estadoJugador.value = jugador.equipo_actual.nombre
      form.id_origen = jugador.equipo_actual.id
    } else {
      estadoJugador.value = 'Libre'
      form.id_origen = ''
    }
  }
)

function submit() {
  form.post('/entrenador/traspasos', {
    onSuccess: () => form.reset('id_jugador', 'id_origen', 'id_destino', 'motivo'),
  })
}
</script>

<template>
  <EntrenadorLayout title="Crear solicitud de fichaje">
    <template #header>
      <h1 class="text-2xl font-bold mb-6">Crear solicitud de fichaje</h1>
    </template>

    <form @submit.prevent="submit" class="max-w-lg mx-auto bg-gray-800 p-6 rounded space-y-6 text-white">
      <!-- Jugador -->
      <div>
        <label for="id_jugador" class="block mb-1 font-semibold">Jugador</label>
        <select
          id="id_jugador"
          v-model="form.id_jugador"
          required
          class="w-full rounded border border-gray-600 bg-gray-700 p-2"
        >
          <option disabled value="">Seleccione jugador</option>
          <option v-for="jugador in jugadores" :key="jugador.id" :value="jugador.id">
            {{ jugador.name }}
          </option>
        </select>
        <p v-if="form.errors.id_jugador" class="text-red-500 mt-1 text-sm">{{ form.errors.id_jugador }}</p>
      </div>

      <!-- Detalles del jugador -->
      <div v-if="form.id_jugador" class="mt-4 p-4 bg-gray-700 rounded">
        <div class="flex items-center space-x-4">
          <img
            v-if="jugadorSeleccionado?.foto"
            :src="jugadorSeleccionado.foto"
            alt="Foto del jugador"
            class="w-16 h-16 rounded-full object-cover border border-gray-500"
          />
          <div>
            <p class="text-lg font-semibold">{{ jugadorSeleccionado?.name }}</p>
            <p v-if="estadoJugador" class="text-sm">
              Estado:
              <span v-if="estadoJugador === 'Libre'" class="text-green-400 font-semibold">Libre</span>
              <span v-else class="text-yellow-400 font-semibold">{{ estadoJugador }}</span>
            </p>
          </div>
        </div>

        <div class="mt-3 text-sm space-y-1">
          <p><strong>Posición:</strong> {{ jugadorSeleccionado?.posicion || 'No definida' }}</p>
          <p><strong>Rol:</strong> {{ jugadorSeleccionado?.rol || 'No definido' }}</p>
          <p><strong>Número:</strong> {{ jugadorSeleccionado?.numero || 'N/A' }}</p>
        </div>
      </div>

      <!-- Equipo Origen (oculto) -->
      <input type="hidden" v-model="form.id_origen" />

      <!-- Equipo Destino (solo mostrar nombre, no editable) -->
      <div>
        <label class="block mb-1 font-semibold">Equipo Destino</label>
        <input
          type="text"
          :value="equipoEntrenador ? equipoEntrenador.nombre : 'Sin equipo'"
          disabled
          class="w-full rounded border border-gray-600 bg-gray-700 p-2 cursor-not-allowed"
        />
      </div>

      <!-- Motivo -->
      <div>
        <label for="motivo" class="block mb-1 font-semibold">Motivo</label>
        <textarea
          id="motivo"
          v-model="form.motivo"
          placeholder="Motivo del fichaje"
          rows="4"
          class="w-full rounded border border-gray-600 bg-gray-700 p-2 resize-y"
        ></textarea>
        <p v-if="form.errors.motivo" class="text-red-500 mt-1 text-sm">{{ form.errors.motivo }}</p>
      </div>

      <button
        type="submit"
        :disabled="form.processing"
        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-semibold disabled:opacity-50"
      >
        <span v-if="form.processing">Enviando...</span>
        <span v-else>Enviar solicitud</span>
      </button>
    </form>
  </EntrenadorLayout>
</template>
