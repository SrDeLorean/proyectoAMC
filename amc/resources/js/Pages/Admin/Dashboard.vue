<script setup>
import AppLayout from '@/Layouts/AdminLayout.vue'
import { computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

// Definir aquí las rutas y nombres para mostrar en el dashboard
const adminLinks = [
  { name: 'Usuarios', route: 'admin.users.index' },
  { name: 'Equipos', route: 'admin.equipos.index' },
  { name: 'Plantillas', route: 'admin.plantillas.index' },
  { name: 'Temporadas', route: 'admin.temporadas.index' },
  { name: 'Competencias', route: 'admin.competencias.index' },
  { name: 'Temporada Competencia', route: 'admin.temporada-competencias.index' },
  { name: 'Calendarios', route: 'admin.calendarios.index' },
]

// Función para navegar usando Inertia
function goTo(routeName) {
  router.get(routeName)
}
</script>

<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard administrador
      </h2>
    </template>

    <div class="p-4">
      <p class="mb-4 text-gray-700">Bienvenido administrador, aquí puedes acceder rápidamente a todas las secciones del sistema:</p>

      <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <li v-for="link in adminLinks" :key="link.route">
          <button
            @click="goTo(link.route)"
            class="w-full text-left bg-gray-800 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition"
          >
            {{ link.name }}
          </button>
        </li>
      </ul>
    </div>
  </AppLayout>
</template>
