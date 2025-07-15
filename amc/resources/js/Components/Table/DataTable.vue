<script setup>
import { computed, watch, ref } from 'vue'
import TableHeader from './TableHeader.vue'
import TableBody from './TableBody.vue'
import TablePagination from './TablePagination.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  columns: Array,
  rows: Array,
  actions: Array,
  meta: Object,
  links: Array,
  filterText: String,
  perPage: [Number, String], // Permitimos también String para evitar error, pero lo convertimos
})

const emit = defineEmits(['action', 'update:filterText', 'update:perPage'])

function getNestedValue(obj, path) {
  return path?.split('.').reduce((o, key) => (o && key in o) ? o[key] : null, obj)
}

// Convertir perPage a número para evitar warning
const perPageNumber = computed(() => {
  const num = Number(props.perPage)
  return isNaN(num) ? 10 : num
})

// No hacemos filtrado local para búsqueda, backend hace la paginación y filtro.
// Simplemente mostramos todas las filas recibidas
const filteredRows = computed(() => props.rows)

// Propagar cambios de filterText y perPage hacia el padre
watch(() => props.filterText, (val) => emit('update:filterText', val))
watch(() => perPageNumber.value, (val) => emit('update:perPage', val))

function handleAction(actionName, row) {
  emit('action', { actionName, row })
}

function goToPage(url) {
  if (url) {
    router.visit(url, { preserveScroll: true, preserveState: true })
  }
}
</script>

<template>
  <div
    class="w-full max-w-full mx-auto px-4 sm:px-6 md:px-8 py-8
    bg-gray-800 rounded-xl shadow space-y-6 text-white
    border border-red-600"
  >
    <TableHeader
      :filterText="props.filterText"
      :perPage="perPageNumber"
      @update:filterText="val => emit('update:filterText', val)"
      @update:perPage="val => emit('update:perPage', val)"
    />

    <div class="overflow-x-auto bg-gray-900 shadow rounded-lg">
      <TableBody
        :rows="filteredRows"
        :columns="columns"
        :actions="actions"
        :getNestedValue="getNestedValue"
        @action="handleAction"
      />
    </div>

    <TablePagination
      :meta="meta"
      :links="links"
      @navigate="goToPage"
    />
  </div>
</template>
