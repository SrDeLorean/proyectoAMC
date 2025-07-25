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
  <div class="min-h-screen bg-gray-900 text-gray-100">
    <!-- Navbar -->
    <nav class="border-b border-gray-800 bg-gray-900 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center space-x-6">
            <!-- Logo -->
            <Link href="/" aria-label="AMC Home">
              <img
                src="/images/competencias/AMC-COMUNIDAD.png"
                alt="AMC Logo"
                class="h-16 w-auto transition-transform duration-300 hover:scale-105"
                draggable="false"
              />
            </Link>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex space-x-8 text-base font-semibold tracking-wide">
              <Link href="/" class="hover:text-red-600 transition-colors duration-300">Inicio</Link>
              <Link :href="route('competencias.index')" class="hover:text-red-600 transition-colors duration-300">Competencias AMC</Link>
              <Link href="/noticias" class="hover:text-red-600 transition-colors duration-300">Noticias</Link>
            </div>
          </div>

          <!-- Social + Auth -->
          <div class="hidden sm:flex items-center space-x-6">
            <div class="flex items-center space-x-5 text-2xl">
              <a
                href="https://facebook.com"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Facebook"
                class="rounded-full p-2 hover:bg-blue-600 hover:text-white transition"
              >
                <i class="fab fa-facebook-f"></i>
              </a>
              <a
                href="https://www.instagram.com/amc_comunidad"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Instagram"
                class="rounded-full p-2 hover:bg-pink-500 hover:text-white transition"
              >
                <i class="fab fa-instagram"></i>
              </a>
              <a
                href="https://www.twitch.tv/comunidadamc"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Twitch"
                class="rounded-full p-2 hover:bg-purple-700 hover:text-white transition"
              >
                <i class="fab fa-twitch"></i>
              </a>
              <a
                href="https://www.youtube.com/@AMC_Comunidad"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="YouTube"
                class="rounded-full p-2 hover:bg-red-600 hover:text-white transition"
              >
                <i class="fab fa-youtube"></i>
              </a>
            </div>

            <template v-if="user">
              <Link
                href="/dashboard"
                class="text-base font-semibold hover:text-red-600 transition-colors duration-300"
              >
                Dashboard
              </Link>
            </template>
            <template v-else>
              <template v-if="canLogin">
                <Link
                  href="/login"
                  class="text-base font-semibold hover:text-red-600 transition-colors duration-300"
                >
                  Login
                </Link>
              </template>
              <template v-if="canRegister">
                <Link
                  href="/register"
                  class="text-base font-semibold hover:text-red-600 transition-colors duration-300"
                >
                  Register
                </Link>
              </template>
            </template>
          </div>

          <!-- Mobile Menu -->
          <div class="sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition"
              aria-label="Toggle menu"
              :aria-expanded="showingNavigationDropdown.toString()"
            >
              <svg
                v-if="!showingNavigationDropdown"
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg
                v-else
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation Links -->
      <transition name="fade">
        <div
          v-show="showingNavigationDropdown"
          class="sm:hidden px-4 pb-6 pt-4 text-base space-y-3 bg-gray-900 border-t border-gray-800"
        >
          <Link href="/" class="block hover:text-red-600 transition">Inicio</Link>
          <Link :href="route('competencias.index')" class="block hover:text-red-600 transition">Competencias AMC</Link>
          <Link href="/noticias" class="block hover:text-red-600 transition">Noticias</Link>

          <div class="flex justify-start space-x-6 text-2xl pt-4 border-t border-gray-800">
            <a
              href="https://facebook.com"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Facebook"
              class="rounded-full p-2 hover:bg-blue-600 hover:text-white transition"
            >
              <i class="fab fa-facebook-f"></i>
            </a>
            <a
              href="https://www.instagram.com/amc_comunidad"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Instagram"
              class="rounded-full p-2 hover:bg-pink-500 hover:text-white transition"
            >
              <i class="fab fa-instagram"></i>
            </a>
            <a
              href="https://www.twitch.tv/comunidadamc"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Twitch"
              class="rounded-full p-2 hover:bg-purple-700 hover:text-white transition"
            >
              <i class="fab fa-twitch"></i>
            </a>
            <a
              href="https://www.youtube.com/@AMC_Comunidad"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="YouTube"
              class="rounded-full p-2 hover:bg-red-600 hover:text-white transition"
            >
              <i class="fab fa-youtube"></i>
            </a>
          </div>

          <div class="pt-4 border-t border-gray-800">
            <template v-if="user">
              <Link href="/dashboard" class="block hover:text-red-600 transition">Dashboard</Link>
            </template>
            <template v-else>
              <template v-if="canLogin">
                <Link href="/login" class="block hover:text-red-600 transition">Login</Link>
              </template>
              <template v-if="canRegister">
                <Link href="/register" class="block hover:text-red-600 transition">Register</Link>
              </template>
            </template>
          </div>
        </div>
      </transition>
    </nav>

    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-gray-900 border-b border-gray-800">
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
