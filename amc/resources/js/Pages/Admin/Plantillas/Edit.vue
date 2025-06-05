<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  plantilla: Object,
  equipos: Array,
  jugadores: Array,
})

const form = ref({
  id_equipo: '',
  id_jugador: '',
  posicion: '',
  numero: '',
})

watch(() => props.plantilla, (p) => {
  form.value.id_equipo = p.id_equipo
  form.value.id_jugador = p.id_jugador
  form.value.posicion = p.posicion
  form.value.numero = p.numero
}, { immediate: true })

function submit() {
  router.put(`/admin/plantillas/${props.plantilla.id}`, form.value)
}
</script>

<template>
  <AdminLayout>
    <template #title>Editar Plantilla</template>
    <div class="p-6 max-w-lg mx-auto bg-gray-800 rounded shadow text-white">

      <h1 class="text-2xl mb-6 font-bold">Editar Plantilla</h1>

      <form @submit.prevent="submit" class="space-y-4">

        <div>
          <label class="block mb-1" for="id_equipo">Equipo</label>
          <select v-model="form.id_equipo" id="id_equipo" class="w-full p-2 rounded bg-gray-700 border border-gray-600">
            <option value="" disabled>Seleccione un equipo</option>
            <option v-for="equipo in equipos" :key="equipo.id" :value="equipo.id">{{ equipo.nombre }}</option>
          </select>
        </div>

        <div>
          <label class="block mb-1" for="id_jugador">Jugador</label>
          <select v-model="form.id_jugador" id="id_jugador" class="w-full p-2 rounded bg-gray-700 border border-gray-600">
            <option value="" disabled>Seleccione un jugador</option>
            <option v-for="jugador in jugadores" :key="jugador.id" :value="jugador.id">{{ jugador.name }}</option>
          </select>
        </div>

        <div>
          <label class="block mb-1" for="posicion">Posición</label>
          <input
            v-model="form.posicion"
            type="text"
            id="posicion"
            class="w-full p-2 rounded bg-gray-700 border border-gray-600"
            placeholder="Ej: Defensa"
          />
        </div>

        <div>
          <label class="block mb-1" for="numero">Número</label>
          <input
            v-model="form.numero"
            type="number"
            id="numero"
            min="1"
            class="w-full p-2 rounded bg-gray-700 border border-gray-600"
            placeholder="Ej: 10"
          />
        </div>

        <button
          type="submit"
          class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition w-full"
        >
          Guardar cambios
        </button>

      </form>
    </div>
  </AdminLayout>
</template>
