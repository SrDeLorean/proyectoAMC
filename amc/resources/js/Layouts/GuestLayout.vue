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
  <div class="min-h-screen bg-gray-100 dark:bg-gray-950 text-gray-800 dark:text-white">
    <!-- Navbar -->
    <nav class="border-b border-gray-700 bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center space-x-4">
            <!-- Logo -->
            <Link href="/">
              <img src="/images/amc-banner.png" alt="AMC Logo" class="h-20 w-auto" />
            </Link>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex space-x-4 text-sm font-medium">
              <Link href="/" class="hover:text-gray-300">Inicio</Link>
              <Link href="/competenciasguest" class="hover:text-gray-300">Competencias AMC</Link>
              <Link href="/noticias" class="hover:text-gray-300">Noticias</Link>
            </div>
          </div>

          <!-- Social + Auth -->
          <div class="hidden sm:flex items-center space-x-4">
            <div class="flex items-center space-x-3 text-xl">
              <a href="https://facebook.com" target="_blank" class="hover:text-blue-400"><i class="fab fa-facebook-f"></i></a>
              <a href="https://www.instagram.com/amc_comunidad" target="_blank" class="hover:text-pink-400"><i class="fab fa-instagram"></i></a>
              <a href="https://www.twitch.tv/comunidadamc" target="_blank" class="hover:text-purple-400"><i class="fab fa-twitch"></i></a>
              <a href="https://www.youtube.com/@AMC_Comunidad" target="_blank" class="hover:text-red-500"><i class="fab fa-youtube"></i></a>
            </div>

            <template v-if="user">
              <Link href="/dashboard" class="text-sm font-medium hover:text-gray-300">Dashboard</Link>
            </template>
            <template v-else>
              <template v-if="canLogin">
                <Link href="/login" class="text-sm font-medium hover:text-gray-300">Login</Link>
              </template>
              <template v-if="canRegister">
                <Link href="/register" class="text-sm font-medium hover:text-gray-300">Register</Link>
              </template>
            </template>
          </div>

          <!-- Mobile Menu -->
          <div class="sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-800 hover:text-white"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                  v-show="!showingNavigationDropdown"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
                <path
                  v-show="showingNavigationDropdown"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation Links -->
      <div v-show="showingNavigationDropdown" class="sm:hidden px-4 pb-4 pt-2 text-sm space-y-2">
        <Link href="/" class="block hover:text-gray-300">Inicio</Link>
        <Link href="/competencias" class="block hover:text-gray-300">Competencias AMC</Link>
        <Link href="/noticias" class="block hover:text-gray-300">Noticias</Link>

        <div class="flex justify-start space-x-4 text-lg pt-2 border-t border-gray-700">
          <a href="https://facebook.com" target="_blank" class="hover:text-blue-400"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/amc_comunidad" target="_blank" class="hover:text-pink-400"><i class="fab fa-instagram"></i></a>
          <a href="https://www.twitch.tv/comunidadamc" target="_blank" class="hover:text-purple-400"><i class="fab fa-twitch"></i></a>
          <a href="https://www.youtube.com/@AMC_Comunidad" target="_blank" class="hover:text-red-500"><i class="fab fa-youtube"></i></a>
        </div>

        <div class="pt-2 border-t border-gray-700">
          <template v-if="user">
            <Link href="/dashboard" class="block hover:text-gray-300">Dashboard</Link>
          </template>
          <template v-else>
            <template v-if="canLogin">
              <Link href="/login" class="block hover:text-gray-300">Login</Link>
            </template>
            <template v-if="canRegister">
              <Link href="/register" class="block hover:text-gray-300">Register</Link>
            </template>
          </template>
        </div>
      </div>
    </nav>

    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-white shadow dark:bg-gray-800 dark:text-white">
      <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Page Content -->
    <main class="py-6">
      <div class="w-full px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>
