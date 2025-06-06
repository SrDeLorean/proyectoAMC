<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  initialData: {
    type: Object,
    default: () => ({})
  },
  fields: {
    type: Array,
    required: true
  },
  form: {
    type: Object,
    required: true
  },
  submit: {
    type: Function,
    required: true
  },
  submitLabel: {
    type: String,
    default: 'Guardar cambios'
  },
  selectOptions: {
    type: Object,
    default: () => ({})
  }
})

const imagePreviews = ref({})

const safeFields = computed(() => Array.isArray(props.fields) ? props.fields : [])

// Inicializar imagePreviews con datos iniciales para campos tipo file
function initImagePreviews(data) {
  safeFields.value.forEach(field => {
    if (field.type === 'file') {
      imagePreviews.value[field.name] = data[field.name] || null
    }
  })
}

// Inicializar al cargar componente
initImagePreviews(props.initialData)

// Observar cambios en initialData para actualizar previews
watch(
  () => props.initialData,
  (newData) => {
    initImagePreviews(newData)
  },
  { immediate: true }
)

function onFileChange(event, fieldName) {
  const file = event.target.files[0] || null
  props.form[fieldName] = file

  if (!file) {
    // Si se elimina archivo, volver al preview inicial o null
    imagePreviews.value[fieldName] = props.initialData[fieldName] || null
    return
  }

  const reader = new FileReader()
  reader.onload = e => {
    imagePreviews.value[fieldName] = e.target.result
  }
  reader.readAsDataURL(file)
}

const successMessage = ref('')
const errorMessage = ref('')

function onSubmit() {
  props.form.clearErrors()
  successMessage.value = ''
  errorMessage.value = ''

  props.submit()
}
</script>

<template>
  <form
    @submit.prevent="onSubmit"
    enctype="multipart/form-data"
    class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-8
           bg-gray-800 rounded-xl shadow space-y-6 text-white
           border-2 border-gray-600"
  >
    <div v-if="successMessage" class="p-4 bg-green-600 rounded-lg text-sm font-medium">
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="p-4 bg-red-600 rounded-lg text-sm font-medium">
      {{ errorMessage }}
    </div>

    <template v-for="field in safeFields" :key="field.name">
      <!-- FILE -->
      <div v-if="field.type === 'file'" class="flex items-center gap-6">
        <img
          v-if="imagePreviews[field.name]"
          :src="imagePreviews[field.name]"
          alt="Preview"
          class="w-24 h-24 rounded-full object-cover border border-gray-500"
        />
        <div class="flex flex-col flex-1">
          <label :for="field.name" class="text-sm text-gray-300 mb-1 font-semibold">
            {{ field.label }}
          </label>
          <input
            :id="field.name"
            type="file"
            accept="image/*"
            @change="e => onFileChange(e, field.name)"
            class="file:bg-red-700 file:border-none file:text-white file:px-4 file:py-1 file:rounded file:cursor-pointer text-white"
          />
          <p v-if="props.form.errors[field.name]" class="text-red-400 text-sm mt-1">
            {{ props.form.errors[field.name] }}
          </p>
        </div>
      </div>

      <!-- SELECT -->
      <div v-else-if="field.type === 'select'">
        <label :for="field.name" class="block text-sm text-gray-300 mb-1 font-semibold">
          {{ field.label }}
        </label>
        <select
          :id="field.name"
          v-model="props.form[field.name]"
          :required="field.required || false"
          class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-red-600"
        >
          <option value="" disabled>
            Selecciona una opción
          </option>
          <option
            v-for="option in (field.options || props.selectOptions[field.optionsKey] || [])"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </select>
        <p v-if="props.form.errors[field.name]" class="text-red-400 text-sm mt-1">
          {{ props.form.errors[field.name] }}
        </p>
      </div>

      <!-- TEXTAREA -->
      <div v-else-if="field.type === 'textarea'">
        <label :for="field.name" class="block text-sm text-gray-300 mb-1 font-semibold">
          {{ field.label }}
        </label>
        <textarea
          :id="field.name"
          v-model="props.form[field.name]"
          :required="field.required || false"
          :placeholder="field.placeholder || ''"
          rows="4"
          class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-red-600 resize-vertical"
        />
        <p v-if="props.form.errors[field.name]" class="text-red-400 text-sm mt-1">
          {{ props.form.errors[field.name] }}
        </p>
      </div>

      <!-- COLOR -->
      <div v-else-if="field.type === 'color'">
        <label :for="field.name" class="block text-sm text-gray-300 mb-1 font-semibold">
          {{ field.label }}
        </label>
        <input
          :id="field.name"
          v-model="props.form[field.name]"
          type="color"
          :required="field.required || false"
          class="w-20 h-10 rounded cursor-pointer border-none p-0"
          title="Seleccionar color"
        />
        <p v-if="props.form.errors[field.name]" class="text-red-400 text-sm mt-1">
          {{ props.form.errors[field.name] }}
        </p>
      </div>

      <!-- DEFAULT INPUT -->
      <div v-else>
        <label :for="field.name" class="block text-sm text-gray-300 mb-1 font-semibold">
          {{ field.label }}
        </label>
        <input
          :id="field.name"
          v-model="props.form[field.name]"
          :type="field.type || 'text'"
          :required="field.required || false"
          :placeholder="field.placeholder || ''"
          class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-red-600"
        />
        <p v-if="props.form.errors[field.name]" class="text-red-400 text-sm mt-1">
          {{ props.form.errors[field.name] }}
        </p>
      </div>
    </template>

    <!-- SUBMIT -->
    <div class="text-right">
      <slot name="submit-button">
        <button
          type="submit"
          class="bg-red-600 hover:bg-red-700 disabled:opacity-50 px-6 py-2 rounded-xl text-white font-semibold transition"
          :disabled="props.form.processing"
        >
          <span v-if="props.form.processing" class="animate-pulse">Guardando...</span>
          <span v-else>{{ submitLabel }}</span>
        </button>
      </slot>
    </div>
  </form>
</template>

<style scoped>
input[type="color"] {
  border: none !important;
  padding: 0;
  -webkit-appearance: none;
  appearance: none;
  background: none;
}
input[type="color"]::-webkit-color-swatch-wrapper {
  padding: 0;
}
input[type="color"]::-webkit-color-swatch {
  border: none;
  border-radius: 0.375rem;
}
input[type="color"]::-moz-color-swatch {
  border: none;
  border-radius: 0.375rem;
}
</style>
