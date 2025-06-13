<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const showingNavigationDropdown = ref(false)
const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

const DEFAULT_IMAGE = '/images/users/default-user.png'

const getUserImageUrl = (foto) => {
  return foto && foto.trim() !== '' ? `/${foto}` : DEFAULT_IMAGE
}
</script>

<template>
  <div class="min-h-screen bg-gray-900 text-white font-sans">
    <nav class="border-b border-gray-700 bg-gray-900 shadow-lg">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
          <div class="flex items-center space-x-8">
            <Link
              :href="route('dashboard')"
              class="flex items-center hover:brightness-110 transition duration-300"
            >
              <ApplicationLogo class="h-10 w-auto text-white" />
            </Link>

            <!-- Slot para navegación personalizada -->
            <slot name="nav" />
          </div>

          <!-- Dropdown usuario con tamaño ajustado -->
          <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="user">
            <Dropdown align="right" width="auto">
              <template #trigger>
                <button
                  class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-md bg-gray-800 hover:bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-600 transition max-w-[200px]"
                  title="Abrir menú usuario"
                >
                  <span class="mr-2 truncate max-w-[130px]">{{ user.name }}</span>
                  <svg
                    class="h-5 w-5 text-red-500"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0
                      111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0
                      010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </template>

              <template #content>
                <div
                  class="bg-gray-800 text-white p-3 space-y-3 rounded-lg shadow-xl border border-red-600 w-[240px]"
                >
                  <div class="flex items-center gap-2.5">
                    <img
                      :src="getUserImageUrl(user.foto)"
                      alt="Avatar"
                      class="w-12 h-12 rounded-full object-cover border-2 border-red-600 shadow-md"
                      @error="e => (e.target.src = DEFAULT_IMAGE)"
                    />
                    <div class="truncate">
                      <div class="font-bold text-lg truncate">{{ user.name }}</div>
                      <div class="text-xs text-gray-400 truncate">ID: {{ user.id_ea }}</div>
                      <div class="text-xs text-gray-400 truncate">Email: {{ user.email }}</div>
                      <div class="text-xs text-gray-400 capitalize truncate">Rol: {{ user.role }}</div>
                    </div>
                  </div>

                  <DropdownLink
                    :href="route('profile.edit')"
                    class="block px-4 py-1.5 rounded-md hover:bg-red-600 hover:text-white transition"
                  >
                    Perfil
                  </DropdownLink>
                  <DropdownLink
                    method="post"
                    as="button"
                    :href="route('logout')"
                    class="block px-4 py-1.5 rounded-md hover:bg-red-600 hover:text-white transition"
                  >
                    Cerrar sesión
                  </DropdownLink>
                </div>
              </template>
            </Dropdown>
          </div>

          <!-- Mobile Hamburger -->
          <div class="sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="p-2 rounded-md text-gray-400 hover:bg-gray-700 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-600 transition"
              aria-label="Toggle navigation menu"
            >
              <svg
                class="h-6 w-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path
                  v-if="!showingNavigationDropdown"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <transition name="fade">
        <div
          v-if="showingNavigationDropdown"
          class="sm:hidden bg-gray-900 border-t border-gray-700"
        >
          <div class="px-4 py-4 space-y-3">
            <slot name="nav-mobile" />
          </div>

          <!-- Mobile User Info -->
          <div class="border-t border-gray-700 p-4 bg-gray-800" v-if="user">
            <div class="flex items-center gap-4 mb-4">
              <img
                :src="getUserImageUrl(user.foto)"
                alt="Foto"
                class="w-12 h-12 rounded-full border-2 border-red-600 object-cover shadow-md"
                @error="e => (e.target.src = DEFAULT_IMAGE)"
              />
              <div>
                <div class="text-sm font-semibold truncate">{{ user.name }}</div>
                <div class="text-xs text-gray-400 truncate">{{ user.email }}</div>
              </div>
            </div>
            <div class="space-y-2">
              <DropdownLink
                :href="route('profile.edit')"
                class="block rounded-md px-3 py-2 hover:bg-red-600 hover:text-white transition"
              >
                Perfil
              </DropdownLink>
              <DropdownLink
                method="post"
                as="button"
                :href="route('logout')"
                class="block rounded-md px-3 py-2 hover:bg-red-600 hover:text-white transition"
              >
                Cerrar sesión
              </DropdownLink>
            </div>
          </div>
        </div>
      </transition>
    </nav>

    <main class="p-6 sm:p-8 lg:p-12">
      <slot />
    </main>
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
