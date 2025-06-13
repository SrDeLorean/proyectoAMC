<script setup>
const props = defineProps({
  filterText: String,
  perPage: Number
})
const emit = defineEmits(['update:filterText', 'update:perPage'])
</script>

<template>
  <div class="flex flex-wrap justify-between items-center mb-3 gap-2">
    <input
        :value="filterText"
        @input="e => emit('update:filterText', e.target.value)"
        type="text"
        placeholder="Buscar..."
        class="px-3 py-2 rounded bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600"
    />
    <div>
        <label class="text-white mr-2">Items por página:</label>
        <select
        :value="perPage"
        @change="e => emit('update:perPage', Number(e.target.value))"
        class="rounded bg-gray-700 text-white px-2 py-1 focus:outline-none focus:ring-2 focus:ring-red-600"
        >
        <option :value="5">5</option>
        <option :value="10">10</option>
        <option :value="25">25</option>
        <option :value="50">50</option>
        </select>
    </div>
    </div>

</template>
