<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
  <AppLayout>
    <!-- Aquí definimos el slot "navigationLinks" que usaremos dentro de AppLayout para poner las NavLinks -->
    <template #navigationLinks>
      <NavLink
        :href="route('dashboard')"
        :active="route().current('dashboard')"
      >
        Dashboard
      </NavLink>
      <!-- Agrega más enlaces exclusivos para entrenador -->
      <NavLink
        :href="route('entrenador.equipos.index')"
        :active="route().current('entrenador.equipos.index')"
      >
        Mis Equipos
      </NavLink>
      <NavLink
        :href="route('entrenador.jugadores.index')"
        :active="route().current('entrenador.jugadores.index')"
      >
        Mis Jugadores
      </NavLink>
    </template>

    <!-- Slot header y contenido van normales -->
    <template #header>
      <slot name="header" />
    </template>

    <slot />
  </AppLayout>
</template>
