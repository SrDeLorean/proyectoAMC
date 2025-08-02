<script setup>
const props = defineProps({
  field: { type: Object, required: true },
  modelValue: [String, Number, Boolean],
  error: String,
})
const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  emit('update:modelValue', event.target.value)
}

function updateCheckbox(event) {
  emit('update:modelValue', event.target.checked)
}
</script>

<template>
  <div>
    <label :for="field.name" class="block text-sm text-gray-300 mb-1 font-semibold">
      {{ field.label }}
    </label>

    <template v-if="field.type === 'boolean' || field.type === 'checkbox'">
      <input
        type="checkbox"
        :id="field.name"
        :checked="!!modelValue"
        @change="updateCheckbox"
        class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-600 border-gray-600 bg-gray-800"
      />
    </template>

    <template v-else>
      <input
        :id="field.name"
        :value="modelValue"
        @input="updateValue"
        :type="field.type || 'text'"
        :required="field.required || false"
        :placeholder="field.placeholder || ''"
        class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-red-600"
      />
    </template>

    <p v-if="error" class="text-red-400 text-sm mt-1">{{ error }}</p>
  </div>
</template>
