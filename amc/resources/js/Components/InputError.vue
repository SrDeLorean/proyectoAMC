<script setup>
const props = defineProps({
  message: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div v-if="props.message" role="alert" class="mt-1">
    <p class="text-sm text-red-600 font-medium">
      {{ props.message }}
    </p>
  </div>
</template>
