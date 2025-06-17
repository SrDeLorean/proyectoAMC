<script setup>
import { computed } from 'vue'
import {
  PencilSquareIcon as EditIcon,
  TrashIcon,
  ArrowPathIcon as RestoreIcon,
  UsersIcon
} from '@heroicons/vue/24/solid'

const props = defineProps({
  rows: { type: Array, default: () => [] },
  columns: { type: Array, default: () => [] },
  actions: { type: Array, default: () => [] },
  sortKey: String,
  sortOrder: String,
  getNestedValue: Function
})

const emit = defineEmits(['sort', 'action'])

const isImageUrl = (url) =>
  typeof url === 'string' && /\.(jpeg|jpg|gif|png|webp|svg|bmp|tiff|avif)(\?.*)?$/i.test(url)

const getFotoUrl = (foto) => {
  if (!foto) return null
  if (foto.startsWith('http://') || foto.startsWith('https://')) return foto
  if (foto.startsWith('/')) return foto
  return '/' + foto
}
</script>

<template>
  <table class="min-w-full text-sm text-left text-gray-200">
    <thead>
      <tr class="bg-gradient-to-r from-red-700 to-red-600 text-white uppercase text-xs tracking-wide select-none">
        <th
          v-for="column in columns"
          :key="column.key"
          class="px-6 py-3 whitespace-nowrap"
          :class="column.key !== 'equipos' ? 'cursor-pointer' : ''"
          @click="column.key !== 'equipos' ? $emit('sort', column.key) : null"
          :aria-sort="sortKey === column.key ? (sortOrder === 'asc' ? 'ascending' : 'descending') : 'none'"
          role="columnheader"
          tabindex="0"
        >
          <div class="flex items-center gap-1">
            <span>{{ column.label }}</span>
            <span v-if="sortKey === column.key && column.key !== 'equipos'" class="ml-1">
              <svg v-if="sortOrder === 'asc'" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none">
                <path stroke="currentColor" stroke-width="2" d="M5 15l7-7 7 7" />
              </svg>
              <svg v-else class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none">
                <path stroke="currentColor" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </span>
          </div>
        </th>
        <th v-if="actions.length" class="px-6 py-3 text-right select-none">Acciones</th>
      </tr>
    </thead>

    <tbody>
      <tr
        v-for="row in rows"
        :key="row.id || row._id"
        class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition-colors"
      >
        <td
          v-for="column in columns"
          :key="column.key"
          class="px-6 py-4 align-middle whitespace-nowrap"
        >
          <slot
            :name="`cell_${column.key}`"
            :row="row"
          >
            <template v-if="column.key.includes('color_')">
              <div
                class="w-6 h-6 rounded border border-gray-500"
                :style="{ backgroundColor: getNestedValue(row, column.key) }"
                :title="getNestedValue(row, column.key)"
              ></div>
            </template>

            <template v-else-if="isImageUrl(getNestedValue(row, column.key))">
              <div class="w-10 h-10 rounded-full ring-2 ring-red-600 overflow-hidden flex items-center justify-center bg-gray-900">
                <img
                  :src="getFotoUrl(getNestedValue(row, column.key))"
                  alt="Imagen"
                  class="max-w-full max-h-full object-contain"
                />
              </div>
            </template>

            <template v-else>
              <span class="block text-sm text-gray-300">
                {{ getNestedValue(row, column.key) }}
              </span>
            </template>
          </slot>
        </td>

        <td v-if="actions.length" class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
          <template v-for="action in actions" :key="action.actionName">
            <button
              type="button"
              @click="$emit('action', action.actionName, row)"
              class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded bg-gray-700 hover:bg-red-700/70 text-red-400 hover:text-white transition"
              :title="action.label"
              :aria-label="`${action.label} registro`"
            >
              <component
                v-if="action.actionName === 'edit'"
                :is="EditIcon"
                class="w-4 h-4"
              />
              <component
                v-else-if="action.actionName === 'delete'"
                :is="TrashIcon"
                class="w-4 h-4"
              />
              <component
                v-else-if="action.actionName === 'restore'"
                :is="RestoreIcon"
                class="w-4 h-4"
              />
              <span>{{ action.label }}</span>
            </button>
          </template>
        </td>
      </tr>

      <tr v-if="rows.length === 0">
        <td
          :colspan="columns.length + (actions.length ? 1 : 0)"
          class="text-center text-gray-400 py-6 bg-gray-800"
        >
          No hay datos para mostrar.
        </td>
      </tr>
    </tbody>
  </table>
</template>
