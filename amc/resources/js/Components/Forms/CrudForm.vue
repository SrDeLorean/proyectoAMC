<script setup>
import { ref, computed, watch } from 'vue'
import FormFieldFile from './FormFieldFile.vue'
import FormFieldSelect from './FormFieldSelect.vue'
import FormFieldTextarea from './FormFieldTextarea.vue'
import FormFieldColor from './FormFieldColor.vue'
import FormFieldInput from './FormFieldInput.vue'

const props = defineProps({
  initialData: { type: Object, default: () => ({}) },
  fields: { type: Array, required: true },
  form: { type: Object, required: true },
  submit: { type: Function, required: true },
  submitLabel: { type: String, default: 'Guardar cambios' },
  selectOptions: { type: Object, default: () => ({}) },
})

const imagePreviews = ref({})

const safeFields = computed(() => Array.isArray(props.fields) ? props.fields : [])

function getNestedValue(obj, path) {
  return path.split('.').reduce((acc, part) => acc && acc[part], obj)
}
function setNestedValue(obj, path, value) {
  const parts = path.split('.')
  const last = parts.pop()
  const target = parts.reduce((acc, part) => {
    if (!acc[part]) acc[part] = {}
    return acc[part]
  }, obj)
  target[last] = value
}

// Inicializa imagePreviews con URL o null
function initImagePreviews(data) {
  safeFields.value.forEach(field => {
    if (field.type === 'file') {
      const val = getNestedValue(data, field.name)
      if (val && typeof val === 'string' && val.trim() !== '') {
        imagePreviews.value[field.name] = val.startsWith('/')
          ? val
          : '/' + val
      } else {
        imagePreviews.value[field.name] = null
      }
    }
  })
}
initImagePreviews(props.initialData)

watch(() => props.initialData, (newData) => {
  initImagePreviews(newData)
}, { immediate: true })

function modelGet(fieldName) {
  return getNestedValue(props.form, fieldName)
}
function modelSet(fieldName, value) {
  setNestedValue(props.form, fieldName, value)
}

function onFileChange(fieldName, file) {
  modelSet(fieldName, file)
  if (!file) {
    const val = getNestedValue(props.initialData, fieldName)
    if (val && typeof val === 'string' && val.trim() !== '') {
      imagePreviews.value[fieldName] = val.startsWith('/')
        ? val
        : '/' + val
    } else {
      imagePreviews.value[fieldName] = null
    }
  } else if (file instanceof File) {
    const reader = new FileReader()
    reader.onload = e => imagePreviews.value[fieldName] = e.target.result
    reader.readAsDataURL(file)
  }
}

function onSubmit() {
  props.form.clearErrors()
  props.submit()
}
</script>

<template>
  <form
    @submit.prevent="onSubmit"
    enctype="multipart/form-data"
    class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-8
           bg-gray-800 rounded-xl shadow space-y-6 text-white
           border-2 border-red-600"
  >
    <!-- Campos tipo file -->
    <template v-for="field in safeFields.filter(f => f.type === 'file')" :key="field.name">
      <div v-if="field.readonly || field.viewOnly" class="mb-4">
        <label class="block font-semibold mb-1">{{ field.label || field.name }}</label>
        <!-- Mostrar imagen si existe, sino texto "Sin archivo" -->
        <img
          v-if="imagePreviews[field.name]"
          :src="imagePreviews[field.name]"
          alt="Preview"
          class="max-w-xs rounded"
        />
        <div v-else class="italic text-gray-400">Sin archivo</div>
      </div>
      <FormFieldFile
        v-else
        :field="field"
        :initialValue="imagePreviews[field.name]"
        :modelValue="modelGet(field.name)"
        :error="props.form.errors[field.name]"
        :disabled="field.disabled || false"
        @update:modelValue="file => onFileChange(field.name, file)"
      />
    </template>

    <!-- Otros campos -->
    <template v-for="field in safeFields.filter(f => f.type !== 'file')" :key="field.name">
      <div v-if="field.readonly || field.viewOnly" class="mb-4">
        <label class="block font-semibold mb-1">{{ field.label || field.name }}</label>
        <div class="p-2 bg-gray-700 rounded text-white whitespace-pre-wrap">
          {{ modelGet(field.name) ?? '-' }}
        </div>
      </div>

      <FormFieldSelect
        v-else-if="field.type === 'select'"
        :field="field"
        :modelValue="modelGet(field.name)"
        :error="props.form.errors[field.name]"
        :selectOptions="props.selectOptions"
        :disabled="field.disabled || false"
        @update:modelValue="val => modelSet(field.name, val)"
      />
      <FormFieldTextarea
        v-else-if="field.type === 'textarea'"
        :field="field"
        :modelValue="modelGet(field.name)"
        :error="props.form.errors[field.name]"
        :disabled="field.disabled || false"
        @update:modelValue="val => modelSet(field.name, val)"
      />
      <FormFieldColor
        v-else-if="field.type === 'color'"
        :field="field"
        :modelValue="modelGet(field.name)"
        :error="props.form.errors[field.name]"
        :disabled="field.disabled || false"
        @update:modelValue="val => modelSet(field.name, val)"
      />
      <FormFieldInput
        v-else-if="field.type === 'boolean'"
        :field="field"
        type="checkbox"
        :modelValue="!!modelGet(field.name)"
        :error="props.form.errors[field.name]"
        :disabled="field.disabled || false"
        @update:modelValue="val => modelSet(field.name, val)"
        />
      <FormFieldInput
        v-else
        :field="field"
        :modelValue="modelGet(field.name)"
        :error="props.form.errors[field.name]"
        :disabled="field.disabled || false"
        @update:modelValue="val => modelSet(field.name, val)"
      />
    </template>

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
