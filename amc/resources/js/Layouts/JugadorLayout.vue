<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NavLink from '@/Components/NavLink.vue'
</script>

<template>
  <AppLayout>
    <!-- Navegación simple para jugador -->
    <template #nav>
      <nav class="flex space-x-4 bg-gray-900 p-3 rounded-md">
        <NavLink
          :href="route('dashboard')"
          :active="route().current('dashboard')"
          class="text-white hover:text-blue-400"
          active-class="text-blue-400 underline"
        >
          Dashboard
        </NavLink>

        <NavLink
          :href="route('jugador.equipos.index')"
          :active="route().current('jugador.equipos.index')"
          class="text-white hover:text-blue-400"
          active-class="text-blue-400 underline"
        >
          Mi Equipo
        </NavLink>

        <NavLink
          :href="route('jugador.traspasos.index')"
          :active="route().current('jugador.traspasos.index')"
          class="text-white hover:text-blue-400"
          active-class="text-blue-400 underline"
        >
          Traspasos
        </NavLink>
      </nav>
    </template>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Panel de Jugador
      </h2>
    </template>

    <div>
      <slot />
    </div>
  </AppLayout>
</template>
