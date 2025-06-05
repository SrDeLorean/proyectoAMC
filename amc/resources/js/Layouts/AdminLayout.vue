<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);
const DEFAULT_IMAGE = '/storage/images/users/default-user.png';

const getUserImageUrl = (foto) => {
  return foto && foto.trim() !== ''
    ? `/storage/${foto}`
    : DEFAULT_IMAGE;
};
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-900 text-white">
      <nav class="border-b border-gray-700 bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 justify-between">
            <div class="flex">
              <!-- Logo -->
              <div class="flex shrink-0 items-center">
                <Link :href="route('dashboard')">
                  <ApplicationLogo class="block h-9 w-auto fill-current text-white" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                  class="text-white hover:text-red-500"
                  active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
                >
                  Dashboard
                </NavLink>

                <NavLink
                  v-if="$page.props.auth.user?.role === 'administrador'"
                  :href="route('admin.users.index')"
                  :active="route().current('admin.users.index')"
                  class="text-white hover:text-red-500"
                  active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
                >
                  Usuarios
                </NavLink>

                <NavLink
                  v-if="$page.props.auth.user?.role === 'administrador'"
                  :href="route('equipos.index')"
                  :active="route().current('equipos.index')"
                  class="text-white hover:text-red-500"
                  active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
                >
                  Equipos
                </NavLink>
              </div>
            </div>

            <!-- Desktop Dropdown -->
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
              <div class="relative ms-3">
                <Dropdown align="right" width="48">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                      >
                        {{ $page.props.auth.user.name }}
                        <svg
                          class="-me-0.5 ms-2 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <!-- 👇 Aquí estaba el fondo blanco -->
                    <div class="bg-gray-900 rounded-md shadow-md px-4 py-4 space-y-4 w-64 text-white">
                      <div class="flex items-center gap-3">
                        <img
                          :src="getUserImageUrl($page.props.auth.user.foto)"
                          alt="Foto de {{ $page.props.auth.user.name }}"
                          class="w-12 h-12 rounded-full object-cover border-2 border-red-600"
                          @error="(e) => { e.target.src = DEFAULT_IMAGE }"
                        />
                        <div>
                          <div class="text-sm font-semibold">{{ $page.props.auth.user.name }}</div>
                          <div class="text-xs text-gray-400">ID: {{ $page.props.auth.user.id_ea }}</div>
                          <div class="text-xs text-gray-400 capitalize">Rol: {{ $page.props.auth.user.role }}</div>
                        </div>
                      </div>

                      <div class="space-y-2">
                        <DropdownLink
                          :href="route('profile.edit')"
                          class="flex items-center gap-2 rounded-md px-3 py-2 text-white hover:bg-red-600 hover:text-white transition"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                          </svg>
                          Profile
                        </DropdownLink>

                        <DropdownLink
                          :href="route('logout')"
                          method="post"
                          as="button"
                          class="flex items-center gap-2 rounded-md px-3 py-2 text-white hover:bg-red-600 hover:text-white transition"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                          </svg>
                          Log Out
                        </DropdownLink>
                      </div>
                    </div>
                  </template>
                </Dropdown>
              </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-700 hover:text-red-500 focus:bg-gray-700 focus:text-red-500 focus:outline-none"
              >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path
                    :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
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

        <!-- Responsive Navigation -->
        <div
          :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
          class="sm:hidden bg-gray-800 border-t border-gray-700"
        >
          <div class="space-y-1 pb-3 pt-2">
            <ResponsiveNavLink
              :href="route('dashboard')"
              :active="route().current('dashboard')"
              class="text-white hover:text-red-500"
              active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
            >
              Dashboard
            </ResponsiveNavLink>

            <ResponsiveNavLink
              v-if="$page.props.auth.user?.role === 'administrador'"
              :href="route('admin.users.index')"
              :active="route().current('admin.users.index')"
              class="text-white hover:text-red-500"
              active-class="bg-gray-900 text-red-500 rounded-md px-3 py-2"
            >
              Usuarios
            </ResponsiveNavLink>
          </div>

          <!-- Responsive User Info -->
          <div class="border-t border-gray-700 bg-gray-900 rounded-b-md p-4 shadow-lg">
            <div class="flex items-center gap-3 mb-3">
              <img
                :src="getUserImageUrl($page.props.auth.user.foto)"
                alt="Foto de {{ $page.props.auth.user.name }}"
                class="w-12 h-12 rounded-full object-cover border-2 border-red-600"
                @error="(e) => { e.target.src = DEFAULT_IMAGE }"
              />
              <div>
                <p class="text-base font-semibold text-white truncate" :title="$page.props.auth.user.name">
                  {{ $page.props.auth.user.name }}
                </p>
                <p class="text-sm font-medium text-gray-400 truncate" :title="$page.props.auth.user.email">
                  {{ $page.props.auth.user.email }}
                </p>
              </div>
            </div>

            <div class="space-y-2">
              <ResponsiveNavLink
                :href="route('profile.edit')"
                class="flex items-center gap-2 rounded-md px-3 py-2 text-white hover:bg-red-600 hover:text-white transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Profile
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :href="route('logout')"
                method="post"
                as="button"
                class="flex items-center gap-2 rounded-md px-3 py-2 text-white hover:bg-red-600 hover:text-white transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                </svg>
                Log Out
              </ResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header class="bg-gray-800 shadow" v-if="$slots.header">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main class="bg-gray-900">
        <slot />
      </main>
    </div>
  </div>
</template>
