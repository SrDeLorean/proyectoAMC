<template>
  <div class="max-w-2xl mx-auto bg-white shadow-md font-sans rounded-none overflow-hidden">
    <!-- Encabezado -->
    <div
      class="relative flex flex-col text-white pb-[100px]"
      :style="{
        backgroundColor,
        backgroundRepeat: 'no-repeat',
        backgroundPosition: 'right bottom',
        backgroundSize: '160px',
      }"
    >
      <div class="absolute top-2 left-4 z-20">
        <h2 class="text-xl font-extrabold uppercase tracking-tight m-0">{{ title }}</h2>
      </div>

      <div class="absolute bottom-0 left-6 w-16 h-16 z-30 flex items-end p-1">
        <img :src="players[0].clubLogo" alt="Club" class="max-w-full max-h-full object-contain" />
      </div>

      <div
        class="absolute right-4 w-[120px] h-[120px] z-10 overflow-hidden"
        :style="{ backgroundColor, bottom: '-40px' }"
      >
        <img
          :src="players[0].playerImage"
          :alt="players[0].playerName"
          class="w-full h-full object-cover object-top drop-shadow-[0_8px_12px_rgba(0,0,0,0.8)]"
          @error="onImageError"
        />
      </div>
    </div>

    <!-- Tabla de jugadores -->
    <div class="bg-white text-gray-900 rounded-none border-t border-gray-300">
      <div
        v-for="player in players"
        :key="player.rank"
        class="flex items-center font-semibold text-lg px-4 py-2 border-b border-gray-300 last:border-none"
      >
        <div class="w-6 text-right font-mono mr-3">{{ player.rank }}</div>
        <img
          :src="player.playerImage"
          alt="Jugador"
          class="w-10 h-10 object-cover object-top mr-3"
          @error="onImageError"
        />
        <div class="flex-1 truncate">
          <span class="font-normal">{{ getFirstName(player.firstName) }}</span>
          <span class="font-normal">{{ getSecondName(player.firstName) }}</span>
          <strong>{{ player.lastName }}</strong>
        </div>
        <img :src="player.clubLogo" alt="Club" class="w-6 h-6 object-contain mx-3" />
        <div class="font-bold">{{ player.statValue }}</div>
      </div>
    </div>

    <!-- Enlace inferior -->
    <div class="border-t border-gray-200 text-center py-2">
      <a
        :href="moreLink"
        class="uppercase font-extrabold text-xs tracking-tight text-black no-underline"
      >
        MOSTRAR LISTA COMPLETA
      </a>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: 'Goles' },
  players: { type: Array, required: true }, // No limitado a 3
  backgroundColor: { type: String, default: '#000' },
  moreLink: { type: String, default: '/estadisticas/jugadores' },
})

const defaultPlayerImage = 'https://assets.bundesliga.com/placeholder/resp_live_placeholder_player.png'

function onImageError(event) {
  event.target.src = defaultPlayerImage
}

function getFirstName(fullName) {
  if (!fullName) return ''
  return fullName.trim().split(' ')[0]
}

function getSecondName(fullName) {
  if (!fullName) return ''
  const parts = fullName.trim().split(' ')
  return parts.length > 1 ? parts.slice(1).join(' ') : ''
}
</script>
