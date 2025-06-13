<script setup>
const props = defineProps({
  field: { type: Object, required: true },
  modelValue: String,
  error: String,
})
const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  const val = event.target.value || ''
  emit('update:modelValue', val)
}
</script>

<template>
  <div>
    <label
      :for="field.name"
      class="block text-sm text-gray-400 mb-1 font-semibold"
    >
      {{ field.label }}
    </label>
    <input
      :id="field.name"
      type="color"
      :value="modelValue"
      @input="updateValue"
      :required="field.required || false"
      class="w-20 h-10 rounded cursor-pointer border-none p-0"
      title="Seleccionar color"
      :aria-describedby="error ? `${field.name}-error` : null"
    />
    <p
      v-if="error"
      :id="`${field.name}-error`"
      class="text-red-500 text-sm mt-1"
      role="alert"
    >
      {{ error }}
    </p>
  </div>
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
