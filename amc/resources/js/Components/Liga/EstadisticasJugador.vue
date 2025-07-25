<template>
  <div class="max-w-sm mx-auto bg-white shadow-md overflow-hidden font-sans rounded-none relative">
    <div class="w-full cursor-pointer select-none" @click="onClick">
      <div
        class="relative flex flex-col text-white pb-[100px] rounded-none overflow-hidden"
        :style="{
          backgroundColor,
          backgroundRepeat: 'no-repeat',
          backgroundPosition: 'right bottom',
          backgroundSize: '160px',
        }"
      >
        <div class="absolute top-2 left-4 z-20">
          <h2 class="text-xl font-extrabold uppercase leading-tight tracking-tight m-0 p-0">{{ title }}</h2>
        </div>

        <div class="absolute bottom-0 left-6 w-16 h-16 z-30 flex items-end p-1">
          <img
            :src="players[0].clubLogo"
            alt="Logo equipo"
            class="max-w-full max-h-full object-contain"
          />
        </div>

        <!-- Imagen superior posicionada flotante, sin afectar el layout -->
        <div
            class="absolute right-4 z-10 rounded-none overflow-hidden w-[140px] h-[140px] drop-shadow-xl"
            style="bottom: -50px;"
            >
            <img
                :src="players[0].playerImage"
                :alt="players[0].playerName"
                class="w-full h-full object-cover object-top min-w-[140px] min-h-[140px] rounded-none"
                @error="onImageError"
            />
        </div>
      </div>

      <!-- Línea principal con nombre y valor juntos -->
      <div
        class="flex justify-between items-center gap-2 px-4 py-1 font-semibold text-lg bg-opacity-90 select-none rounded-none border-b border-gray-300"
        :style="{ backgroundColor, color: 'white' }"
      >
        <div class="flex-1 truncate">
          <span class="font-mono mr-2 font-normal align-middle">{{ players[0].rank }}</span>
          <span>
            <span class="font-normal">{{ getFirstName(players[0].firstName) }}</span>
            <span class="font-normal">
              {{ getSecondName(players[0].firstName) ? ' ' + getSecondName(players[0].firstName) : '' }}
            </span>
            <strong> {{ players[0].lastName }}</strong>
          </span>
        </div>
        <div class="font-bold text-2xl whitespace-nowrap">{{ players[0].statValue }}</div>
      </div>

      <!-- Lista de los otros jugadores -->
      <div class="px-4 py-1 bg-white text-gray-900 rounded-none border-t border-gray-300">
        <div
          v-for="player in players.slice(1)"
          :key="player.playerName"
          class="flex items-center font-semibold text-lg border-b border-gray-300 last:border-none pb-1"
        >
          <div class="mr-3 font-mono w-5 text-right font-normal align-middle">{{ player.rank }}</div>
          <img
            :src="player.playerImage"
            :alt="player.playerName"
            class="w-10 h-10 object-cover object-top rounded-none mr-3 flex-shrink-0"
            @error="onImageError"
          />
          <div class="flex-1 truncate">
            <span class="font-normal">{{ getFirstName(player.firstName) }}</span>
            <span class="font-normal">
              {{ getSecondName(player.firstName) ? ' ' + getSecondName(player.firstName) : '' }}
            </span>
            <strong>{{ player.lastName }}</strong>
          </div>
          <img
            :src="player.clubLogo"
            alt="Logo equipo"
            class="w-6 h-6 object-contain mx-3 flex-shrink-0"
          />
          <div class="font-normal">{{ player.statValue }}</div>
        </div>
      </div>
    </div>

    <div class="border-t border-gray-200 text-center py-1">
      <a
        :href="moreLink"
        class="uppercase font-extrabold tracking-tight text-xs no-underline font-sans"
        style="color: black; letter-spacing: -0.02em;"
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

const defaultPlayerImage = 'https://assets.bundesliga.com/placeholder/resp_live_placeholder_player.png'

function onImageError(event) {
  event.target.src = defaultPlayerImage
}

function onClick() {
  console.log(`Clicked on top player ${props.players[0].firstName} ${props.players[0].lastName}`)
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
