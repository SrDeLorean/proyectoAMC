<script setup>
import { ref, computed, watch } from 'vue'
import TableHeader from './TableHeader.vue'
import TableBody from './TableBody.vue'
import TablePagination from './TablePagination.vue'

const props = defineProps({
  columns: Array,
  rows: Array,
  actions: Array
})
const emit = defineEmits(['action'])

const filterText = ref('')
const perPage = ref(10)
const currentPage = ref(1)
const sortKey = ref('')
const sortOrder = ref('asc')

watch(perPage, () => currentPage.value = 1)

function getNestedValue(obj, path) {
  return path?.split('.').reduce((o, key) => (o && key in o) ? o[key] : null, obj)
}

const filteredRows = computed(() => {
  const search = filterText.value.toLowerCase()
  return !search
    ? props.rows
    : props.rows.filter(row =>
        props.columns.some(col =>
          String(getNestedValue(row, col.key) ?? '').toLowerCase().includes(search)
        )
      )
})

const sortedRows = computed(() => {
  if (!sortKey.value) return filteredRows.value
  return [...filteredRows.value].sort((a, b) => {
    const valA = getNestedValue(a, sortKey.value)
    const valB = getNestedValue(b, sortKey.value)
    if (typeof valA === 'string') return valA.localeCompare(valB) * (sortOrder.value === 'asc' ? 1 : -1)
    return (valA < valB ? -1 : valA > valB ? 1 : 0) * (sortOrder.value === 'asc' ? 1 : -1)
  })
})

const totalPages = computed(() => Math.ceil(sortedRows.value.length / perPage.value))

const paginatedRows = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return sortedRows.value.slice(start, start + perPage.value)
})

const pagesToShow = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  const delta = 2
  const pages = []
  if (total <= 7) for (let i = 1; i <= total; i++) pages.push(i)
  else {
    const left = Math.max(2, current - delta)
    const right = Math.min(total - 1, current + delta)
    pages.push(1)
    if (left > 2) pages.push('...')
    for (let i = left; i <= right; i++) pages.push(i)
    if (right < total - 1) pages.push('...')
    pages.push(total)
  }
  return pages
})

function sortBy(key) {
  sortKey.value === key ? (sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc') : (sortKey.value = key, sortOrder.value = 'asc')
}

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page
}

function handleAction(actionName, row) {
  emit('action', { actionName, row })
}
</script>

<template>
  <div
    class="w-full max-w-full mx-auto px-4 sm:px-6 md:px-8 py-8
      bg-gray-800 rounded-xl shadow space-y-6 text-white
      border border-red-600"
  >
    <TableHeader v-model:filterText="filterText" v-model:perPage="perPage" />
    <div class="overflow-x-auto bg-gray-900 shadow rounded-lg">
      <TableBody
        :rows="paginatedRows"
        :columns="columns"
        :actions="actions"
        :sortKey="sortKey"
        :sortOrder="sortOrder"
        :getNestedValue="getNestedValue"
        @sort="sortBy"
        @action="handleAction"
      />
    </div>
    <TablePagination
      :currentPage="currentPage"
      :totalPages="totalPages"
      :pagesToShow="pagesToShow"
      @go="goToPage"
    />
  </div>
</template>
