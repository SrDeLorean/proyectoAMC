<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user || null
const canLogin = page.props.canLogin ?? true
const canRegister = page.props.canRegister ?? true

const showingNavigationDropdown = ref(false)
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-black via-gray-900 to-black text-gray-100">
    <!-- Navbar -->
    <nav class="bg-gray-900 border-b border-red-600 shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
          <!-- Left -->
          <div class="flex items-center space-x-6">
            <!-- Logo -->
            <Link href="/" aria-label="AMC Home">
              <img
                src="/images/competencias/AMC-COMUNIDAD.png"
                alt="AMC Logo"
                class="h-14 w-auto transition-transform duration-300 hover:scale-105 drop-shadow-[0_0_4px_#dc2626]"
                draggable="false"
              />
            </Link>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex space-x-6 text-base font-semibold tracking-wide">
              <Link href="/" class="hover:text-red-500 transition">Inicio</Link>
              <Link :href="route('competencias.index')" class="hover:text-red-500 transition">Competencias AMC</Link>
              <Link :href="route('equipos.index')" class="hover:text-red-500 transition">Equipos</Link>
              <Link :href="route('jugadores.index')" class="hover:text-red-500 transition">Jugadores</Link>
            </div>
          </div>

          <!-- Right -->
          <div class="hidden sm:flex items-center space-x-6">
            <!-- Social Icons -->
            <div class="flex space-x-4 text-xl">
              <a href="https://facebook.com" target="_blank" class="hover:text-blue-500 transition" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="https://www.instagram.com/amc_comunidad" target="_blank" class="hover:text-pink-500 transition" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="https://www.twitch.tv/comunidadamc" target="_blank" class="hover:text-purple-500 transition" aria-label="Twitch">
                <i class="fab fa-twitch"></i>
              </a>
              <a href="https://www.youtube.com/@AMC_Comunidad" target="_blank" class="hover:text-red-600 transition" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
              </a>
            </div>

            <!-- Auth -->
            <template v-if="user">
              <Link href="/dashboard" class="hover:text-red-500 font-semibold transition">Dashboard</Link>
            </template>
            <template v-else>
              <template v-if="canLogin">
                <Link href="/login" class="hover:text-red-500 font-semibold transition">Login</Link>
              </template>
              <template v-if="canRegister">
                <Link href="/register" class="hover:text-red-500 font-semibold transition">Register</Link>
              </template>
            </template>
          </div>

          <!-- Mobile Menu Toggle -->
          <div class="sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition"
              aria-label="Toggle menu"
              :aria-expanded="showingNavigationDropdown.toString()"
            >
              <svg v-if="!showingNavigationDropdown" class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <transition name="fade">
        <div v-show="showingNavigationDropdown" class="sm:hidden bg-gray-900 border-t border-red-600 px-4 pt-4 pb-6 space-y-4 text-base">
          <Link href="/" class="block hover:text-red-500 transition">Inicio</Link>
          <Link :href="route('competencias.index')" class="block hover:text-red-500 transition">Competencias AMC</Link>
          <Link :href="route('equipos.index')" class="block hover:text-red-500 transition">Equipos</Link>
          <Link :href="route('jugadores.index')" class="block hover:text-red-500 transition">Jugadores</Link>

          <!-- Social Icons -->
          <div class="flex space-x-5 pt-4 text-xl border-t border-gray-700">
            <a href="https://facebook.com" target="_blank" class="hover:text-blue-500" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/amc_comunidad" target="_blank" class="hover:text-pink-500" aria-label="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.twitch.tv/comunidadamc" target="_blank" class="hover:text-purple-500" aria-label="Twitch">
              <i class="fab fa-twitch"></i>
            </a>
            <a href="https://www.youtube.com/@AMC_Comunidad" target="_blank" class="hover:text-red-500" aria-label="YouTube">
              <i class="fab fa-youtube"></i>
            </a>
          </div>

          <!-- Auth -->
          <div class="pt-4 border-t border-gray-700">
            <template v-if="user">
              <Link href="/dashboard" class="block hover:text-red-500 transition">Dashboard</Link>
            </template>
            <template v-else>
              <template v-if="canLogin">
                <Link href="/login" class="block hover:text-red-500 transition">Login</Link>
              </template>
              <template v-if="canRegister">
                <Link href="/register" class="block hover:text-red-500 transition">Register</Link>
              </template>
            </template>
          </div>
        </div>
      </transition>
    </nav>

    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-gray-900 border-b border-red-600">
      <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Page Content -->
    <main class="py-6 bg-gray-900 text-gray-100 min-h-[calc(100vh-4rem)]">
      <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
