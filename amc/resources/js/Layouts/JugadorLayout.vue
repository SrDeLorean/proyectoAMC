<!-- resources/js/Pages/Jugador/Panel.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NavLink from '@/Components/NavLink.vue'
</script>

<template>
  <AppLayout>
    <!-- Navegación principal (escritorio) -->
    <template #nav>
      <NavLink
        :href="route('dashboard')"
        :active="route().current('dashboard')"
        class="text-white hover:text-red-500 transition"
        active-class="text-red-500 underline"
      >
        Dashboard
      </NavLink>

      <NavLink
        :href="route('jugador.equipos.index')"
        :active="route().current('jugador.equipos.index')"
        class="text-white hover:text-red-500 transition"
        active-class="text-red-500 underline"
      >
        Mi Equipo
      </NavLink>

      <NavLink
        :href="route('jugador.traspasos.index')"
        :active="route().current('jugador.traspasos.index')"
        class="text-white hover:text-red-500 transition"
        active-class="text-red-500 underline"
      >
        Traspasos
      </NavLink>
    </template>

    <!-- Navegación móvil -->
    <template #nav-mobile>
      <NavLink
        :href="route('dashboard')"
        :active="route().current('dashboard')"
        class="block text-white hover:text-red-500 transition"
        active-class="text-red-500 underline"
      >
        Dashboard
      </NavLink>

      <NavLink
        :href="route('jugador.equipos.index')"
        :active="route().current('jugador.equipos.index')"
        class="block text-white hover:text-red-500 transition"
        active-class="text-red-500 underline"
      >
        Mi Equipo
      </NavLink>

      <NavLink
        :href="route('jugador.traspasos.index')"
        :active="route().current('jugador.traspasos.index')"
        class="block text-white hover:text-red-500 transition"
        active-class="text-red-500 underline"
      >
        Traspasos
      </NavLink>
    </template>

    <!-- Encabezado -->
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">Panel de Jugador</h2>
    </template>

    <!-- Contenido principal -->
    <div class="mt-6">
      <slot />
    </div>
  </AppLayout>
</template>
