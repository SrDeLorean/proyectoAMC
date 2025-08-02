<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'

const props = defineProps({
  calendarioId: [Number, String],
  success: String,
})

const form = useForm({
  foto_rendimiento_partido: null,
  foto_listado_id_partido: null,
  foto_rendimientos_individuales: null,
})

const mensaje = ref(props.success || '')  // Mostrar éxito inicial que venga del backend

function submit() {
  mensaje.value = ''  // Limpiar mensajes previos

  form.post(`/entrenador/reportes/store/${props.calendarioId}`, {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      mensaje.value = 'Reporte guardado correctamente.'
    },
    onError: () => {
      mensaje.value = 'Error al subir el reporte. Por favor revisa los campos.'
    },
  })
}
</script>

<template>
  <EntrenadorLayout>
    <div class="max-w-lg mx-auto p-6 bg-white dark:bg-gray-800 rounded shadow">
      <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
        Reportar partido #{{ calendarioId }}
      </h1>

      <div
        v-if="mensaje"
        class="mb-4 p-3 rounded"
        :class="{
          'bg-green-600 text-white': mensaje === 'Reporte guardado correctamente.',
          'bg-red-600 text-white': mensaje !== 'Reporte guardado correctamente.'
        }"
      >
        {{ mensaje }}
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
            Foto rendimiento del partido
          </label>
          <input
            type="file"
            @change="e => form.foto_rendimiento_partido = e.target.files[0]"
            accept="image/*"
            required
            class="w-full"
          />
          <span v-if="form.errors.foto_rendimiento_partido" class="text-red-500 text-sm">
            {{ form.errors.foto_rendimiento_partido }}
          </span>
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
            Foto listado de ID del partido
          </label>
          <input
            type="file"
            @change="e => form.foto_listado_id_partido = e.target.files[0]"
            accept="image/*"
            required
            class="w-full"
          />
          <span v-if="form.errors.foto_listado_id_partido" class="text-red-500 text-sm">
            {{ form.errors.foto_listado_id_partido }}
          </span>
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
            Foto rendimientos individuales
          </label>
          <input
            type="file"
            @change="e => form.foto_rendimientos_individuales = e.target.files[0]"
            accept="image/*"
            required
            class="w-full"
          />
          <span v-if="form.errors.foto_rendimientos_individuales" class="text-red-500 text-sm">
            {{ form.errors.foto_rendimientos_individuales }}
          </span>
        </div>

        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold"
          :disabled="form.processing"
        >
          Guardar reporte
        </button>
      </form>
    </div>
  </EntrenadorLayout>
</template>
