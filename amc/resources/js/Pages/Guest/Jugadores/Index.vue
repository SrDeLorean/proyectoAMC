<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import PlantillaEquipo from '@/Components/Equipo/PlantillaEquipo.vue'
import { ref, computed } from 'vue'
import { ChevronDown, ChevronUp } from 'lucide-vue-next'

const props = defineProps({
  plantillasPorEquipo: Object,
})

const desplegados = ref({})
const hoverEquipo = ref(null)

function toggleEquipo(nombre) {
  if (desplegados.value[nombre]) {
    desplegados.value = {}
  } else {
    desplegados.value = { [nombre]: true }
  }
}

function setHover(nombre) {
  hoverEquipo.value = nombre
}

function clearHover() {
  hoverEquipo.value = null
}

function getEquipoInfo(plantillas) {
  const equipo = plantillas[0]?.equipo || {}
  return {
    logo: equipo.logo || null,
    color: equipo.color_primario || '#ef4444',
  }
}

const algunDesplegado = computed(() => Object.keys(desplegados.value).length > 0)
</script>

<template>
  <GuestLayout>
    <div class="max-w-7xl mx-auto p-4 text-white flex flex-col">
      <h1
        class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight transition-all"
        :class="algunDesplegado ? 'mb-24' : 'mb-8'"
      >
        Jugadores por Equipo
      </h1>

      <div
        v-if="Object.keys(plantillasPorEquipo).length > 0"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
      >
        <div
          v-for="(plantillas, nombreEquipo) in plantillasPorEquipo"
          :key="nombreEquipo"
          v-show="!algunDesplegado || desplegados[nombreEquipo]"
          :class="[
            'rounded-xl shadow-lg border-2 overflow-hidden transition-all duration-300 transform bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 cursor-pointer',
            desplegados[nombreEquipo] ? 'col-span-full scale-105 shadow-2xl' : ''
          ]"
          :style="{
            borderColor: (hoverEquipo === nombreEquipo || desplegados[nombreEquipo])
              ? getEquipoInfo(plantillas).color
              : '#4b5563',
          }"
          @mouseenter="setHover(nombreEquipo)"
          @mouseleave="clearHover"
          @click="toggleEquipo(nombreEquipo)"
        >
          <!-- Encabezado -->
          <div
            class="flex items-center justify-between p-5 backdrop-blur-sm rounded-t-xl select-none"
            :style="{
              backgroundColor: (hoverEquipo === nombreEquipo || desplegados[nombreEquipo])
                ? getEquipoInfo(plantillas).color + '33'
                : 'rgba(0,0,0,0.4)',
            }"
          >
            <div class="flex items-center space-x-5">
              <img
                v-if="getEquipoInfo(plantillas).logo"
                :src="getEquipoInfo(plantillas).logo"
                alt="Logo equipo"
                class="w-14 h-14 rounded-full object-cover shadow-lg"
                loading="lazy"
              />
              <h2
                class="text-2xl font-semibold text-white truncate max-w-xs"
                :title="nombreEquipo"
              >
                {{ nombreEquipo }}
              </h2>
            </div>

            <div
              class="text-white flex items-center justify-center w-8 h-8 rounded-full bg-black/40 hover:bg-black/60 transition"
              :style="{ border: '2px solid ' + (hoverEquipo === nombreEquipo || desplegados[nombreEquipo] ? getEquipoInfo(plantillas).color : 'transparent') }"
            >
              <component
                :is="desplegados[nombreEquipo] ? ChevronUp : ChevronDown"
                class="w-5 h-5"
                aria-hidden="true"
              />
            </div>
          </div>

          <!-- Contenido desplegable -->
          <transition name="fade" mode="out-in">
            <div v-if="desplegados[nombreEquipo]" class="p-6 bg-gray-900/70 rounded-b-xl">
              <PlantillaEquipo
                :jugadores="plantillas"
                :colorEquipo="getEquipoInfo(plantillas).color"
              />
            </div>
          </transition>
        </div>
      </div>

      <p
        v-else
        class="text-gray-400 italic text-center mt-12 select-none"
      >
        No hay jugadores registrados.
      </p>
    </div>
  </GuestLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
