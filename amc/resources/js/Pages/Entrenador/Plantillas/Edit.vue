<template>
  <EntrenadorLayout>
    <template #header>
      <h1 class="text-2xl font-bold">Editar Jugador en Plantilla</h1>
    </template>

    <form @submit.prevent="submit">
      <div class="space-y-4 mt-6 text-white">
        <div>
          <label class="block">Rol</label>
          <input v-model="form.rol" type="text" class="w-full text-black" />
        </div>

        <div>
          <label class="block">Posición</label>
          <input v-model="form.posicion" type="text" class="w-full text-black" />
        </div>

        <div>
          <label class="block">Número</label>
          <input v-model="form.numero" type="number" class="w-full text-black" />
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          Guardar Cambios
        </button>
      </div>
    </form>
  </EntrenadorLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
const props = defineProps({
  plantilla: Object,
})

const form = useForm({
  rol: props.plantilla.rol,
  posicion: props.plantilla.posicion,
  numero: props.plantilla.numero,
})

function submit() {
  form.put(route('entrenador.plantillas.update', props.plantilla.id))
}
</script>
