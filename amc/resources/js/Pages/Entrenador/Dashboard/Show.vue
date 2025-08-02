<script setup>
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'

const props = defineProps({
  user: Object,
  plantilla: Object,
  equipo: Object,
  estadisticas: Array,
})

const colorPrimario = props.equipo?.color_primario || '#0A3D62'
</script>

<template>
  <EntrenadorLayout :title="`Perfil de ${user.name}`">

    <!-- Franja superior con fondo del color del equipo -->
    <div :style="{ backgroundColor: colorPrimario }" class="text-white py-6 px-4 sm:px-8 relative shadow-md">

      <!-- Contenedor de franja -->
      <div class="flex flex-col md:flex-row justify-between items-center relative">

        <!-- Izquierda: Nombre e ID EA -->
        <div class="text-center md:text-left space-y-2 max-w-sm">
          <h1 class="text-3xl md:text-4xl font-extrabold truncate">{{ user.name }}</h1>
          <p class="text-lg font-semibold">ID EA: {{ user.id_ea || '--' }}</p>

          <!-- Logo del club más pequeño -->
          <div class="w-full max-w-[140px] md:max-w-[180px] aspect-square mx-auto md:mx-0 mt-2">
            <img
              v-if="equipo?.logo"
              :src="equipo.logo"
              alt="Logo club"
              class="object-contain w-full h-full"
            />
            <div v-else class="text-gray-300 text-sm italic text-center py-16">
              Sin club
            </div>
          </div>
        </div>

        <!-- Centro: Rol - Posición entre logos -->
        <div
          class="absolute left-1/2 bottom-4 transform -translate-x-1/2 text-center text-base sm:text-lg font-medium tracking-wide bg-black bg-opacity-40 rounded px-3 py-1"
        >
          <div v-if="plantilla?.rol || plantilla?.posicion">
            {{ plantilla?.rol || '' }}<span v-if="plantilla?.rol && plantilla?.posicion"> - </span>{{ plantilla?.posicion || '' }}
          </div>
        </div>

        <!-- Derecha: Foto jugador muy grande cubriendo toda la altura de la franja SIN BORDE -->
        <div class="w-full max-w-[300px] h-[140px] sm:h-[180px] md:h-[200px] lg:h-[240px] xl:h-[280px] rounded-full overflow-hidden mt-4 md:mt-0 shadow-lg">
          <img
            :src="user.profile_photo_url"
            alt="Foto jugador"
            class="object-cover w-full h-full"
          />
        </div>
      </div>
    </div>

    <!-- Datos físicos centrados -->
    <div class="text-center text-gray-400 mt-6 text-sm sm:text-base flex flex-wrap justify-center gap-4">
      <div>
        <strong>Nacionalidad:</strong>
        <img
          v-if="user.nacionalidad"
          :src="`https://flagcdn.com/w20/${user.nacionalidad.slice(0,2).toLowerCase()}.png`"
          alt="bandera"
          class="inline-block w-5 h-3 mx-1 object-cover"
        />
        {{ user.nacionalidad || '--' }}
      </div>
      <div>
        <strong>Fecha nacimiento:</strong>
        {{ user.fecha_nacimiento ? new Date(user.fecha_nacimiento).toLocaleDateString('es-ES') : '--' }}
      </div>
      <div>
        <strong>Altura:</strong> {{ user.altura || '--' }} cm
      </div>
      <div>
        <strong>Peso:</strong> {{ user.peso || '--' }} kg
      </div>
    </div>

    <!-- Sección de estadísticas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 text-white">
      <section>
        <h2
          class="text-2xl sm:text-3xl font-bold text-red-600 mb-6 border-b-4 border-red-600 pb-2 max-w-max"
        >
          Estadísticas detalladas por partido
        </h2>
        <div v-if="estadisticas.length" class="overflow-auto max-h-[480px] pr-2 sm:pr-4 rounded-lg border-2 border-red-600 bg-gray-900 shadow-inner">
          <table class="min-w-full table-auto border-collapse border border-gray-700 text-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-900 sticky top-0">
              <tr>
                <th class="border border-gray-700 p-2 text-left">Fecha</th>
                <th class="border border-gray-700 p-2 text-left">Competencia</th>
                <th class="border border-gray-700 p-2 text-center">Calif.</th>
                <th class="border border-gray-700 p-2 text-center">Goles</th>
                <th class="border border-gray-700 p-2 text-center">Asistencias</th>
                <th class="border border-gray-700 p-2 text-center">Minutos</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="stat in estadisticas" :key="stat.id_calendario" class="hover:bg-gray-800">
                <td class="border border-gray-700 p-2">
                  {{ new Date(stat.created_at).toLocaleDateString('es-ES') }}
                </td>
                <td class="border border-gray-700 p-2">
                  {{ stat.temporadaCompetencia?.competencia?.nombre || '--' }}
                </td>
                <td class="border border-gray-700 p-2 text-center">
                  {{ stat.calificacion }}
                </td>
                <td class="border border-gray-700 p-2 text-center">
                  {{ stat.goles }}
                </td>
                <td class="border border-gray-700 p-2 text-center">
                  {{ stat.asistencias }}
                </td>
                <td class="border border-gray-700 p-2 text-center">
                  {{ stat.minutos_jugados_vs_equipo }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-gray-400 italic mt-4">
          No hay estadísticas disponibles.
        </div>
      </section>
    </div>

  </EntrenadorLayout>
</template>
