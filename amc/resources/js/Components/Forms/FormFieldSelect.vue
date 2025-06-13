<script setup>
const props = defineProps({
  field: { type: Object, required: true },
  modelValue: [String, Number, null],
  error: String,
  selectOptions: Object,
})
const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  emit('update:modelValue', event.target.value)
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
    <select
      :id="field.name"
      :value="modelValue"
      @change="updateValue"
      :required="field.required || false"
      class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white
             focus:outline-none focus:ring-2 focus:ring-red-600 transition"
      :aria-describedby="error ? `${field.name}-error` : null"
    >
      <option value="" disabled>Selecciona una opción</option>
      <option
        v-for="option in (field.options || selectOptions[field.optionsKey] || [])"
        :key="option[field.optionValue || 'value']"
        :value="option[field.optionValue || 'value']"
      >
        {{ option[field.optionLabel || 'label'] }}
      </option>
    </select>
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
