<script setup>
const props = defineProps({
  meta: Object,   // { current_page, last_page, ... }
  links: Array    // [{ url, label, active }]
})

const emit = defineEmits(['navigate'])
</script>

<template>
  <nav class="flex justify-center items-center flex-wrap gap-2 mt-6 select-none" role="navigation" aria-label="Paginación">
    <button
      v-for="(link, index) in links"
      :key="index"
      :disabled="!link.url"
      @click="emit('navigate', link.url ? link.url.replace('http://', 'https://') : null)"
      v-html="link.label"
      class="min-w-[36px] px-3 py-1.5 rounded-lg border text-sm font-medium transition-all duration-200"
      :class="[
        link.active
          ? 'bg-red-600 text-white border-red-500 shadow-inner ring-1 ring-red-400'
          : 'bg-gray-800 text-gray-300 hover:bg-red-700 hover:text-white border-gray-700',
        !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.03]'
      ]"
      :aria-label="'Ir a página ' + link.label.replace(/<[^>]*>/g, '')"
      :title="'Página ' + link.label.replace(/<[^>]*>/g, '')"
      type="button"
    />
  </nav>
</template>
