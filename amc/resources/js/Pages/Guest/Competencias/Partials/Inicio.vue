// Inicio.vue
<script setup>
const props = defineProps({ equipos: Array })
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <div
      v-for="item in equipos"
      :key="item.id"
      class="bg-gray-900 rounded-md p-4 flex items-center gap-4 hover:bg-gray-800 transition cursor-pointer shadow-md border border-red-600"
    >
      <img
        :src="`/${item.equipo?.logo || 'images/equipos/default-equipo.png'}`"
        alt="Logo equipo"
        class="w-14 h-14 object-contain rounded-md border border-gray-600 bg-gray-900 p-1"
      />
      <div class="flex flex-col">
        <p class="text-lg font-semibold text-white leading-tight">
          {{ item.equipo?.nombre || 'Nombre no disponible' }}
        </p>
        <p class="text-gray-400 text-sm leading-snug max-w-xs truncate">
          {{ item.equipo?.descripcion || 'Sin descripción' }}
        </p>
      </div>
    </div>
  </div>
</template>
