<script setup>
const props = defineProps({
  value: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <label class="block text-sm font-medium text-gray-300">
    <span>
      {{ props.value || '' }}
      <slot v-if="!props.value" />
    </span>
  </label>
</template>
