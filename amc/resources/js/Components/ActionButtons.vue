<script setup>
const props = defineProps({
  buttons: {
    type: Array,
    default: () => []
    /* Cada botón es un objeto con:
      - label: texto del botón
      - href: enlace (string) (opcional, si es enlace)
      - onClick: función a ejecutar (opcional, si es botón)
      - colorClass: clases de color para fondo y hover (string)
      - icon: emoji o ícono opcional (string)
      - disabled: booleano (opcional)
    */
  }
})
</script>

<template>
  <div class="flex flex-wrap gap-3 w-full sm:w-auto">
    <template v-for="(btn, i) in buttons" :key="i">
      <a
        v-if="btn.href && !btn.disabled"
        :href="btn.href"
        class="w-full sm:w-auto text-center px-4 py-2 rounded shadow-md text-white transition
               focus:outline-none focus:ring-2"
        :class="btn.colorClass"
        :title="btn.label"
      >
        <span v-if="btn.icon" class="mr-1">{{ btn.icon }}</span>{{ btn.label }}
      </a>

      <button
        v-else-if="btn.onClick && !btn.disabled"
        @click="btn.onClick"
        type="button"
        class="w-full sm:w-auto text-center px-4 py-2 rounded shadow-md text-white transition
               focus:outline-none focus:ring-2 cursor-pointer"
        :class="btn.colorClass"
        :title="btn.label"
      >
        <span v-if="btn.icon" class="mr-1">{{ btn.icon }}</span>{{ btn.label }}
      </button>

      <button
        v-else
        disabled
        type="button"
        class="w-full sm:w-auto text-center px-4 py-2 rounded shadow-md text-gray-400 bg-gray-500 cursor-not-allowed"
        :title="btn.label"
      >
        <span v-if="btn.icon" class="mr-1">{{ btn.icon }}</span>{{ btn.label }}
      </button>
    </template>
  </div>
</template>
