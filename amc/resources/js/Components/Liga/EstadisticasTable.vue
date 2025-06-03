<template>
  <div
    class="max-w-sm mx-auto bg-white shadow-md overflow-hidden font-sans rounded-none"
    :style="{ backgroundColor: backgroundColor || 'transparent' }"
  >
    <!-- Título general -->
    <div class="px-4 py-2 border-b border-gray-300">
      <h2
        class="text-xl font-extrabold uppercase tracking-tight m-0 p-0"
        :style="{ color: backgroundColor ? 'white' : 'black' }"
      >
        {{ title }}
      </h2>
    </div>

    <!-- Jugador destacado -->
    <div
      class="relative flex flex-col text-white pb-[100px] rounded-none cursor-pointer select-none"
      :style="{
        backgroundColor,
        backgroundRepeat: 'no-repeat',
        backgroundPosition: 'right bottom',
        backgroundSize: '160px',
      }"
      @click="$emit('playerClick', players[0])"
    >
      <div class="absolute top-2 left-4 z-20">
        <h3 class="text-xl font-extrabold uppercase leading-tight tracking-tight m-0 p-0">
          {{ players[0].firstName }} {{ players[0].lastName }}
        </h3>
      </div>

      <div class="absolute bottom-0 left-6 w-16 h-16 z-30 flex items-end p-1">
        <img
          :src="players[0].clubLogo"
          alt="Logo equipo"
          class="max-w-full max-h-full object-contain"
        />
      </div>

      <div
        class="absolute right-4 w-[120px] h-[120px] z-10 rounded-none overflow-hidden"
        :style="{ backgroundColor, bottom: '-40px' }"
      >
        <img
          :src="players[0].playerImage"
          :alt="players[0].playerName"
          class="block w-full h-full min-w-[120px] min-h-[120px] object-cover object-top drop-shadow-[0_8px_12px_rgba(0,0,0,0.8)] rounded-none"
          @error="onImageError"
        />
      </div>

      <div
        class="flex justify-between items-center px-4 py-1 font-semibold text-lg bg-opacity-90 select-none rounded-none border-b border-gray-300 mt-[100px]"
        :style="{ backgroundColor, color: 'white' }"
      >
        <div>
          <span class="font-mono mr-2 font-normal align-middle">{{ players[0].rank }}</span>
          <span>
            <span class="font-normal">{{ getFirstName(players[0].firstName) }}</span>
            <span class="font-normal">
              {{ getSecondName(players[0].firstName) ? ' ' + getSecondName(players[0].firstName) : '' }}
            </span>
            <strong> {{ players[0].lastName }}</strong>
          </span>
        </div>
        <div class="font-bold text-2xl">{{ players[0].statValue }}</div>
      </div>
    </div>

    <!-- Lista jugadores restantes -->
    <div class="px-4 py-1 bg-white text-gray-900 rounded-none border-t border-gray-300">
      <div
        v-for="player in players.slice(1)"
        :key="player.playerName"
        class="flex items-center font-semibold text-lg border-b border-gray-300 last:border-none pb-1 cursor-pointer hover:bg-gray-100"
        @click="$emit('playerClick', player)"
      >
        <div class="mr-3 font-mono w-5 text-right font-normal align-middle">{{ player.rank }}</div>
        <img
          :src="player.playerImage"
          :alt="player.playerName"
          class="w-10 h-10 object-cover object-top rounded-none mr-3 flex-shrink-0"
          @error="onImageError"
        />
        <div class="flex-1 truncate">
          <span class="font-normal">{{ getFirstName(player.firstName) }}</span
          ><span class="font-normal">{{ getSecondName(player.firstName) ? ' ' + getSecondName(player.firstName) : '' }}</span
          ><strong>{{ player.lastName }}</strong>
        </div>
        <img
          :src="player.clubLogo"
          alt="Logo equipo"
          class="w-6 h-6 object-contain mx-3 flex-shrink-0"
        />
        <div class="font-normal">{{ player.statValue }}</div>
      </div>
    </div>

    <!-- Link mostrar lista completa -->
    <div class="border-t border-gray-200 text-center py-1">
      <a
        :href="moreLink"
        class="uppercase font-extrabold tracking-tight text-xs no-underline font-sans"
        :style="{ color: backgroundColor ? 'white' : 'black', letterSpacing: '-0.02em' }"
      >
        MOSTRAR LISTA COMPLETA
      </a>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: 'Estadísticas' },
  players: { type: Array, required: true, validator: arr => arr.length >= 1 },
  backgroundColor: { type: String, default: 'transparent' },
  moreLink: { type: String, default: '/estadisticas/jugadores' },
})

const defaultPlayerImage = 'https://assets.bundesliga.com/placeholder/resp_live_placeholder_player.png'

function onImageError(event) {
  event.target.src = defaultPlayerImage
}

function getFirstName(fullName) {
  if (!fullName) return ''
  const parts = fullName.trim().split(' ')
  return parts[0]
}

function getSecondName(fullName) {
  if (!fullName) return ''
  const parts = fullName.trim().split(' ')
  return parts.length > 1 ? parts.slice(1).join(' ') : ''
}
</script>
