<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  jugadores: Array,
})

const getEquipoNombre = (jugador) => {
  return jugador.plantilla?.equipo?.nombre || 'Libre'
}
</script>

<template>
  <GuestLayout>
    <div class="max-w-6xl mx-auto p-6 text-white">
      <h1 class="text-3xl font-bold text-red-600 drop-shadow mb-6">Jugadores Registrados</h1>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="jugador in jugadores"
          :key="jugador.id"
          class="bg-gray-800 rounded-2xl p-4 shadow hover:shadow-red-600 hover:scale-[1.03] transition duration-300"
        >
          <div class="flex items-center space-x-4">
            <img
              :src="jugador.profile_photo_url"
              alt="Foto jugador"
              class="h-16 w-16 rounded-full object-cover border-2 border-red-600"
            />
            <div>
              <h2 class="text-xl font-semibold">{{ jugador.name }}</h2>
              <p class="text-gray-400 text-sm">
                Equipo:
                <span v-if="jugador.plantilla?.equipo">
                  <Link
                    :href="route('equipos.show', jugador.plantilla.equipo.id)"
                    class="text-white hover:underline"
                  >
                    {{ jugador.plantilla.equipo.nombre }}
                  </Link>
                </span>
                <span v-else class="text-white">Libre</span>
              </p>
              <p class="text-gray-400 text-sm">Posición: {{ jugador.posicion || 'No definida' }}</p>
            </div>
          </div>

          <div class="mt-4 text-right">
            <Link
              :href="route('jugadores.show', jugador.id)"
              class="text-sm text-red-500 hover:underline"
            >
              Ver perfil →
            </Link>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>
