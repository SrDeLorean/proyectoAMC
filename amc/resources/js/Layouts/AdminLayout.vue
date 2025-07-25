<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NavLink from '@/Components/NavLink.vue'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  title: String,
})

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

const showingMenu = ref(false)

function onMouseEnter() {
  showingMenu.value = true
}

function onMouseLeave() {
  showingMenu.value = false
}

function toggleMenu() {
  showingMenu.value = !showingMenu.value
}
</script>

<template>
  <AppLayout :title="props.title">
    <template #nav>
      <div
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
        class="relative inline-block"
      >
        <button
          @click="toggleMenu"
          class="text-white bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-md transition flex items-center"
          aria-haspopup="true"
          :aria-expanded="showingMenu.toString()"
        >
          Menú
          <svg
            class="h-4 w-4 ml-1 text-red-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <transition name="fade">
          <div
            v-show="showingMenu"
            class="absolute mt-2 bg-gray-900 border border-red-600 rounded-md shadow-lg p-4 min-w-[240px] z-50"
          >
            <!-- Grupo Administración -->
            <div class="mb-4">
              <h3 class="text-red-500 uppercase font-semibold mb-2 border-b border-red-600 pb-1">
                Administración
              </h3>

              <NavLink
                :href="route('admin.users.index')"
                :active="route().current('admin.users.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500 mb-1"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Usuarios
              </NavLink>

              <NavLink
                :href="route('admin.equipos.index')"
                :active="route().current('admin.equipos.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500 mb-1"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Equipos
              </NavLink>

              <NavLink
                :href="route('admin.plantillas.index')"
                :active="route().current('admin.plantillas.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500 mb-1"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Plantillas
              </NavLink>

              <NavLink
                :href="route('admin.traspasos.index')"
                :active="route().current('admin.traspasos.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Traspasos
              </NavLink>
            </div>

            <!-- Grupo Torneo -->
            <div>
              <h3 class="text-red-500 uppercase font-semibold mb-2 border-b border-red-600 pb-1">
                Torneo
              </h3>

              <NavLink
                :href="route('admin.temporadas.index')"
                :active="route().current('admin.temporadas.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500 mb-1"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Temporadas
              </NavLink>

              <NavLink
                :href="route('admin.competencias.index')"
                :active="route().current('admin.competencias.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500 mb-1"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Competencias
              </NavLink>

              <NavLink
                :href="route('admin.temporada-competencias.index')"
                :active="route().current('admin.temporada-competencias.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500 mb-1"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Temporada Competencia
              </NavLink>

              <NavLink
                :href="route('admin.calendarios.index')"
                :active="route().current('admin.calendarios.index')"
                v-if="user?.role === 'administrador'"
                class="block text-white hover:text-red-500"
                active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
              >
                Calendario
              </NavLink>
            </div>
          </div>
        </transition>
      </div>
    </template>

    <template #default>
      <slot />
    </template>
  </AppLayout>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
