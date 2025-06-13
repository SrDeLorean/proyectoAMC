<script setup>
const props = defineProps({
  currentPage: Number,
  totalPages: Number,
  pagesToShow: Array
})

const emit = defineEmits(['go'])
</script>

<template>
  <nav class="flex justify-center items-center gap-1 mt-3 select-none">
    <button
      class="px-3 py-1 rounded bg-gray-700 text-white hover:bg-gray-600 disabled:opacity-50"
      :disabled="currentPage === 1"
      @click="emit('go', currentPage - 1)"
    >
      &lt;
    </button>

    <template v-for="page in pagesToShow" :key="page">
      <button
        v-if="page !== '...'"
        class="px-3 py-1 rounded hover:bg-red-600"
        :class="page === currentPage ? 'bg-red-600 text-white font-bold' : 'text-white'"
        @click="emit('go', page)"
      >
        {{ page }}
      </button>
      <span v-else class="px-3 py-1 text-white">...</span>
    </template>

    <button
      class="px-3 py-1 rounded bg-gray-700 text-white hover:bg-gray-600 disabled:opacity-50"
      :disabled="currentPage === totalPages || totalPages === 0"
      @click="emit('go', currentPage + 1)"
    >
      &gt;
    </button>
  </nav>
</template>
