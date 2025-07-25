<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NavLink from '@/Components/NavLink.vue'
</script>

<template>
  <AppLayout>
    <!-- Slot para navegación simple -->
    <template #nav>

        <nav class="flex space-x-4 bg-gray-900 p-3 rounded-md">
        <NavLink
            :href="route('entrenador.dashboard')"
            :active="route().current('entrenador.dashboard')"
            class="text-white hover:text-red-500"
            active-class="text-red-500 underline"
        >
            Dashboard
        </NavLink>

        <NavLink
            :href="route('entrenador.equipos.index')"
            :active="route().current('entrenador.equipos.index') || route().current('entrenador.equipos.*')"
            class="text-white hover:text-red-500"
            active-class="text-red-500 underline"
        >
            Mis Equipos
        </NavLink>

        <NavLink
            :href="route('entrenador.traspasos.index')"
            :active="route().current('entrenador.traspasos.index') || route().current('entrenador.traspasos.*')"
            class="text-white hover:text-red-500"
            active-class="text-red-500 underline"
        >
            Traspasos
        </NavLink>

        <NavLink
            :href="route('entrenador.plantillas.index')"
            :active="route().current('entrenador.plantillas.index') || route().current('entrenador.plantillas.*')"
            class="text-white hover:text-red-500"
            active-class="text-red-500 underline"
        >
            Plantillas
        </NavLink>
        </nav>

    </template>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Panel de Entrenador
      </h2>
    </template>

    <div>
      <slot />
    </div>
  </AppLayout>
</template>
