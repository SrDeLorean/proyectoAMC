<script setup>
const props = defineProps({
  filterText: String,
  perPage: Number
})

const emit = defineEmits(['update:filterText', 'update:perPage'])
</script>

<template>
  <div class="flex flex-wrap gap-4 mb-6 items-center justify-between">
    <label for="search" class="relative w-full max-w-xs">
      <svg
        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
        xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
      </svg>
      <input
        id="search"
        :value="filterText"
        @input="e => emit('update:filterText', e.target.value)"
        type="search"
        placeholder="Buscar..."
        class="w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800 text-white placeholder-gray-500
               focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-0 transition"
        autocomplete="off"
        spellcheck="false"
      />
    </label>

    <label for="perPage" class="flex items-center gap-2 text-white select-none whitespace-nowrap relative">
      <span class="font-medium text-sm">Items por página:</span>
      <select
        id="perPage"
        :value="perPage"
        @change="e => emit('update:perPage', Number(e.target.value))"
        class="select-no-arrow rounded-md bg-gray-800 text-white px-4 py-2
               focus:outline-none focus:ring-2 focus:ring-red-600 transition
               cursor-pointer"
      >
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
      </select>
      <svg
        class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
        xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </label>
  </div>
</template>

<style scoped>
.select-no-arrow {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: none !important;
  padding-right: 2rem; /* espacio para la flecha SVG */
}
</style>
