<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  field: { type: Object, required: true },
  modelValue: { type: [String, File, null], default: null },
  error: { type: String, default: '' },
  initialValue: { type: [String, File, null], default: null },
})

const emit = defineEmits(['update:modelValue'])

const DEFAULT_IMAGE = '/images/users/default-user.png'

function getUserImageUrl(foto) {
  return foto && typeof foto === 'string' && foto.trim() !== ''
    ? `/${foto.replace(/^\//, '')}`
    : DEFAULT_IMAGE
}

const preview = ref(DEFAULT_IMAGE)

function updatePreview() {
  if (props.modelValue) {
    if (props.modelValue instanceof File) {
      const reader = new FileReader()
      reader.onload = e => preview.value = e.target.result
      reader.readAsDataURL(props.modelValue)
    } else if (typeof props.modelValue === 'string') {
      preview.value = getUserImageUrl(props.modelValue)
    } else {
      preview.value = DEFAULT_IMAGE
    }
  } else if (props.initialValue) {
    preview.value = getUserImageUrl(props.initialValue)
  } else {
    preview.value = DEFAULT_IMAGE
  }
}

onMounted(() => {
  updatePreview()
})

watch(() => props.modelValue, () => {
  updatePreview()
})

watch(() => props.initialValue, (newVal) => {
  if (!props.modelValue) {
    preview.value = getUserImageUrl(newVal)
  }
})

function onFileChange(event) {
  const file = event.target.files[0] || null
  emit('update:modelValue', file)
}

function onImageError(event) {
  event.target.src = DEFAULT_IMAGE
}
</script>

<template>
  <div class="flex flex-col items-center md:items-start md:w-1/4 text-center md:text-left">
    <!-- Avatar -->
    <div
      class="relative w-36 h-36 rounded-full overflow-hidden shadow-lg border-4 border-gray-700 mb-4"
      aria-label="Foto de perfil"
      role="img"
    >
      <img
        :src="preview"
        alt="Foto de perfil"
        class="w-full h-full object-cover rounded-full transition-transform duration-500 ease-in-out hover:scale-110"
        @error="onImageError"
        loading="lazy"
      />
      <div
        class="absolute inset-0 rounded-full bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity duration-300"
        aria-hidden="true"
      ></div>
    </div>

    <!-- Botón cambiar imagen: icono y texto alineados horizontalmente -->
    <label
      :for="field.name"
      class="inline-flex items-center gap-3 cursor-pointer bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 focus:ring-4 focus:ring-red-400 focus:ring-opacity-50 text-white text-sm font-semibold rounded-lg px-6 py-2 select-none shadow-md transition transform hover:scale-105"
      tabindex="0"
      title="Subir nueva imagen de perfil"
      role="button"
      aria-label="Cambiar imagen de perfil"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0 0l-4-4m4 4l4-4M12 4v8" />
      </svg>
      Cambiar imagen
    </label>

    <input
      :id="field.name"
      type="file"
      accept="image/*"
      @change="onFileChange"
      class="sr-only"
    />

    <p v-if="error" class="mt-2 flex items-center text-red-500 text-sm font-medium space-x-2 select-none" role="alert">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ error }}</span>
    </p>
  </div>
</template>
