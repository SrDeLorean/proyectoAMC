<script setup>
import { computed } from 'vue'
import EstadisticasTable from '@/Components/Liga/EstadisticasTable.vue'
import WelcomeLayout from '@/Layouts/GuestLayout.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const jugadores = page.props.jugadores || []
const titulo = page.props.titulo || 'Estadísticas'
const backgroundColor = page.props.backgroundColor || '#333'
</script>

<template>
  <WelcomeLayout>
    <template #default>
      <div class="max-w-7xl mx-auto px-4 py-10 text-white">
        <EstadisticasTable
          :title="titulo"
          :players="jugadores"
          :backgroundColor="backgroundColor"
        />
      </div>
    </template>
  </WelcomeLayout>
</template>
