<script setup>
const props = defineProps({
  field: { type: Object, required: true },
  modelValue: String,
  error: String,
})
const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  emit('update:modelValue', event.target.value)
}
</script>

<template>
  <div>
    <label :for="field.name" class="block text-sm text-gray-300 mb-1 font-semibold">
      {{ field.label }}
    </label>
    <textarea
      :id="field.name"
      :value="modelValue"
      @input="updateValue"
      :required="field.required || false"
      :placeholder="field.placeholder || ''"
      rows="4"
      class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-red-600 resize-vertical"
    />
    <p v-if="error" class="text-red-400 text-sm mt-1">{{ error }}</p>
  </div>
</template>
