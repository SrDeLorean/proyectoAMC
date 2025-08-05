<template>
  <div
    class="overflow-hidden font-sans rounded-xl relative h-full flex flex-col transition-all duration-300 transform hover:scale-[1.01]"
    :style="{
      background: 'linear-gradient(to bottom right, #1a1a1a, #111)',
    }"
  >
    <!-- Parte superior con color del equipo -->
    <div class="w-full cursor-pointer select-none flex-grow" @click="onClick">
      <div
        class="relative flex flex-col pb-[100px] overflow-hidden"
        :style="{
          backgroundColor: backgroundColor,
          backgroundRepeat: 'no-repeat',
          backgroundPosition: 'right bottom',
          backgroundSize: '160px',
        }"
      >
        <div class="absolute top-2 left-4 z-20">
          <h2 class="text-xl font-extrabold uppercase leading-tight tracking-tight text-white">
            {{ title }}
          </h2>
        </div>

        <div class="absolute bottom-0 left-6 w-16 h-16 z-30 flex items-end p-1">
          <img
            :src="players[0].clubLogo"
            alt="Logo equipo"
            class="max-w-full max-h-full object-contain"
            @error="onClubLogoError"
          />
        </div>

        <div
          class="absolute right-4 z-10 overflow-hidden w-[140px] h-[140px] rounded-full"
          style="bottom: -50px;"
        >
          <img
            :src="players[0].playerImage"
            :alt="players[0].nombre"
            class="w-full h-full object-cover object-top min-w-[140px] min-h-[140px]"
            @error="onPlayerImageError"
          />
        </div>
      </div>

      <!-- Top 1 -->
      <div
        class="flex justify-between items-center gap-2 px-4 py-1 font-semibold text-lg border-b border-gray-800 text-white"
        :style="{
          backgroundColor: darkenColor(backgroundColor, 0.2),
        }"
      >
        <div class="flex-1 truncate">
          <span class="font-mono mr-2 font-normal align-middle">{{ players[0].rank }}</span>
          <strong>{{ players[0].nombre }}</strong>
        </div>
        <div class="font-bold text-2xl whitespace-nowrap">{{ players[0].statValue }}</div>
      </div>

      <!-- Jugadores 2 y 3 -->
      <div class="px-4 py-1 text-gray-300 divide-y divide-gray-700 bg-black bg-opacity-50">
        <div
          v-for="player in players.slice(1)"
          :key="player.id_jugador"
          class="flex items-center font-semibold text-lg py-1"
        >
          <div class="mr-3 font-mono w-5 text-right font-normal align-middle">{{ player.rank }}</div>
          <img
            :src="player.playerImage"
            :alt="player.nombre"
            class="w-10 h-10 object-cover object-top rounded-none mr-3 flex-shrink-0"
            @error="onPlayerImageError"
          />
          <div class="flex-1 truncate">
            <strong>{{ player.nombre }}</strong>
          </div>
          <img
            :src="player.clubLogo"
            alt="Logo equipo"
            class="w-6 h-6 object-contain mx-3 flex-shrink-0"
            @error="onClubLogoError"
          />
          <div class="font-normal">{{ player.statValue }}</div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-700 text-center py-1 mt-auto bg-black bg-opacity-60">
      <a
        :href="moreLink"
        class="uppercase font-extrabold tracking-tight text-xs no-underline text-gray-400 hover:text-white"
        style="letter-spacing: -0.02em;"
      >
        MOSTRAR LISTA COMPLETA
      </a>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: 'Goles' },
  players: { type: Array, required: true, validator: arr => arr.length === 3 },
  backgroundColor: { type: String, default: 'transparent' },
  moreLink: { type: String, default: '/estadisticas/jugadores' },
})

const defaultPlayerImage = '/images/default-player.png'
const defaultClubLogo = '/images/default-club.png'

function onPlayerImageError(event) {
  event.target.src = defaultPlayerImage
}

function onClubLogoError(event) {
  event.target.src = defaultClubLogo
}

// Devuelve una versión más oscura del color en hex
function darkenColor(hex, factor = 0.2) {
  if (!hex.startsWith('#') || hex.length !== 7) return hex

  const r = Math.floor(parseInt(hex.slice(1, 3), 16) * (1 - factor))
  const g = Math.floor(parseInt(hex.slice(3, 5), 16) * (1 - factor))
  const b = Math.floor(parseInt(hex.slice(5, 7), 16) * (1 - factor))

  return `rgb(${r}, ${g}, ${b})`
}

function onClick() {
  console.log(`Clicked on top player ${props.players[0].nombre}`)
}
</script>
