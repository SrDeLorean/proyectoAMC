<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  columns: {
    type: Array,
    required: true
  },
  rows: {
    type: Array,
    required: true
  },
  actions: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['action']);

const filterText = ref('');
const sortKey = ref('');
const sortOrder = ref('asc');
const currentPage = ref(1);
const perPage = ref(10);

watch(perPage, () => {
  currentPage.value = 1;
});

const filteredRows = computed(() => {
  if (!filterText.value) return props.rows;

  const search = filterText.value.toLowerCase();

  return props.rows.filter(row =>
    props.columns.some(col => {
      const val = row[col.key];
      return val != null && String(val).toLowerCase().includes(search);
    })
  );
});

const sortedRows = computed(() => {
  if (!sortKey.value) return filteredRows.value;

  return [...filteredRows.value].sort((a, b) => {
    let valA = a[sortKey.value];
    let valB = b[sortKey.value];

    if (typeof valA === 'string') valA = valA.toLowerCase();
    if (typeof valB === 'string') valB = valB.toLowerCase();

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1;
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1;
    return 0;
  });
});

const totalPages = computed(() => Math.ceil(sortedRows.value.length / perPage.value));

const paginatedRows = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  return sortedRows.value.slice(start, start + perPage.value);
});

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
}

function sortBy(columnKey) {
  if (sortKey.value === columnKey) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = columnKey;
    sortOrder.value = 'asc';
  }
}

function handleAction(actionName, row) {
  emit('action', { actionName, row });
}

const pagesToShow = computed(() => {
  const total = totalPages.value;
  const current = currentPage.value;
  const delta = 2;
  const pages = [];

  if (total <= 7) {
    for (let i = 1; i <= total; i++) pages.push(i);
  } else {
    const left = Math.max(2, current - delta);
    const right = Math.min(total - 1, current + delta);

    pages.push(1);
    if (left > 2) pages.push('...');
    for (let i = left; i <= right; i++) pages.push(i);
    if (right < total - 1) pages.push('...');
    pages.push(total);
  }
  return pages;
});

// Función para asegurar URL absoluta con '/' al inicio
function getFotoUrl(foto) {
  if (!foto) return null;

  if (foto.startsWith('http://') || foto.startsWith('https://')) {
    return foto;
  }

  if (foto.startsWith('/')) {
    return foto;
  }

  if (foto.startsWith('')) {
    return '/' + foto;
  }

  if (foto.startsWith('/')) {
    return foto;
  }

  return '/' + foto;
}
</script>

<template>
  <div>
    <!-- Filtro y perPage -->
    <div class="flex flex-wrap justify-between items-center mb-3 gap-2">
      <input
        v-model="filterText"
        type="text"
        placeholder="Buscar..."
        class="px-3 py-2 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-600"
      />
      <div>
        <label class="text-white mr-2">Items por página:</label>
        <select v-model.number="perPage" class="rounded bg-gray-800 text-white px-2 py-1 focus:outline-none focus:ring-2 focus:ring-red-600">
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="25">25</option>
          <option :value="50">50</option>
        </select>
      </div>
    </div>

    <div class="overflow-x-auto bg-gray-900 shadow rounded-lg">
      <table class="min-w-full text-sm text-left text-white">
        <thead class="bg-gray-800 uppercase text-xs text-gray-400">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-3 cursor-pointer select-none"
              @click="sortBy(column.key)"
            >
              <div class="flex items-center gap-1">
                <span>{{ column.label }}</span>
                <span v-if="sortKey === column.key">
                  <svg
                    v-if="sortOrder === 'asc'"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                  </svg>
                  <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span>
              </div>
            </th>
            <th v-if="actions.length" class="px-6 py-3 text-right">Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="row in paginatedRows"
            :key="row.id || row._id || row.key || row.name"
            class="border-b border-gray-800 hover:bg-gray-800"
          >
            <td v-for="column in columns" :key="column.key" class="px-6 py-4">
              <template v-if="column.key === 'logo'">
                <img
                  v-if="row.logo"
                  :src="getFotoUrl(row.logo)"
                  alt="Logo"
                  class="w-12 h-12 object-contain rounded"
                />
                <span v-else class="text-gray-500 italic">Sin logo</span>
              </template>

              <template v-else-if="column.key === 'color_primario' || column.key === 'color_secundario'">
                <div
                  class="w-8 h-8 rounded border border-gray-600"
                  :style="{ backgroundColor: row[column.key] }"
                  :title="row[column.key]"
                ></div>
              </template>

              <template v-else-if="column.key === 'foto'">
                <img
                  v-if="row.foto"
                  :src="getFotoUrl(row.foto)"
                  alt="Foto"
                  class="w-12 h-12 object-cover rounded-full"
                />
                <span v-else class="text-gray-500 italic">Sin foto</span>
              </template>

              <template v-else>
                {{ row[column.key] }}
              </template>
            </td>

            <td
              v-if="actions.length"
              class="px-6 py-4 text-right space-x-2 whitespace-nowrap"
            >
              <button
                v-for="action in actions"
                :key="action.actionName"
                :class="action.class"
                type="button"
                @click="handleAction(action.actionName, row)"
              >
                {{ action.label }}
              </button>
            </td>
          </tr>

          <tr v-if="paginatedRows.length === 0">
            <td
              :colspan="columns.length + (actions.length ? 1 : 0)"
              class="text-center text-gray-400 py-6"
            >
              No hay datos para mostrar.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <nav class="flex justify-center items-center gap-1 mt-3 select-none">
      <button
        class="px-3 py-1 rounded bg-gray-700 text-white hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
      >
        &lt;
      </button>

      <template v-for="page in pagesToShow" :key="page">
        <button
          v-if="page !== '...'"
          class="px-3 py-1 rounded hover:bg-red-600"
          :class="page === currentPage ? 'bg-red-600 text-white font-bold' : 'text-white'"
          @click="goToPage(page)"
        >
          {{ page }}
        </button>
        <span
          v-else
          class="px-3 py-1 text-white select-none cursor-default"
        >
          ...
        </span>
      </template>

      <button
        class="px-3 py-1 rounded bg-gray-700 text-white hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="currentPage === totalPages || totalPages === 0"
        @click="goToPage(currentPage + 1)"
      >
        &gt;
      </button>
    </nav>
  </div>
</template>
