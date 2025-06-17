<script setup>
import { ref, watch, onMounted } from 'vue';

// Props para v-model
const props = defineProps({
  modelValue: {
    type: String,
    required: true,
  },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);
const internalValue = ref(props.modelValue);

// Sincronizar cambios externos al prop
watch(() => props.modelValue, (val) => {
  internalValue.value = val;
});

// Emitir cambios internos al padre
watch(internalValue, (val) => {
  emit('update:modelValue', val);
});

onMounted(() => {
  if (input.value?.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({
  focus: () => input.value?.focus(),
});
</script>

<template>
  <input
    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    v-model="internalValue"
    ref="input"
    autofocus
  />
</template>
