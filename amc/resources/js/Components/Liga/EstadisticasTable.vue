<template>
  <div class="max-w-full mx-auto bg-white shadow-md overflow-hidden font-sans rounded-none p-4">
    <div class="w-full select-none">

      <!-- Fondo con imagen y título del top jugador -->
      <div
        class="relative flex flex-col text-white pb-[100px] rounded-none"
        :style="{
          backgroundColor,
          backgroundRepeat: 'no-repeat',
          backgroundPosition: 'right bottom',
          backgroundSize: '160px',
        }"
      >
        <div class="absolute top-2 left-4 z-20" v-if="topPlayer">
          <h2 class="text-2xl font-extrabold uppercase leading-tight tracking-tight m-0 p-0">{{ title }}</h2>
        </div>

        <div class="absolute bottom-0 left-6 w-20 h-20 z-30 flex items-end p-1" v-if="topPlayer">
          <img :src="topPlayer.clubLogo" alt="Logo equipo" class="max-w-full max-h-full object-contain" />
        </div>


        <div
          class="absolute right-4 w-[140px] h-[140px] z-10 rounded-none overflow-hidden"
          :style="{ backgroundColor, bottom: '-40px' }"
          v-if="topPlayer"
        >

          <img
            :src="topPlayer.playerImage"
            :alt="topPlayer.playerName"
            class="block w-full h-full min-w-[140px] min-h-[140px] object-cover object-top drop-shadow-[0_8px_12px_rgba(0,0,0,0.8)] rounded-none"
            @error="onImageError"
          />

        </div>



      </div>

<!-- Destacado Top 1 -->
<div
  v-if="topPlayer"
  class="relative flex items-center px-4 py-2 font-semibold text-xl bg-opacity-90 select-none rounded-none border-b border-gray-300"
  :style="{ backgroundColor, color: 'white' }"
>
  <!-- Ranking y nombre alineados a la izquierda -->
  <div class="flex items-center space-x-4">
    <span class="font-mono mr-3 font-normal align-middle">{{ topPlayer.rank }}</span>
    <span>
      <span class="font-normal">
        {{ getFirstName(topPlayer.firstName) }}
        <template v-if="getSecondName(topPlayer.firstName)"> {{ getSecondName(topPlayer.firstName) }}</template>
      </span>
      <strong> {{ topPlayer.lastName }}</strong>
    </span>
  </div>

  <div
    class="absolute z-40 text-center font-bold text-3xl tabular-nums select-none"
    :style="{ right: '180px' }"
    >
    <span class="font-bold text-3xl tabular-nums select-none">
        {{ topPlayer.statValue }}
    </span>
    </div>
    </div>

      <!-- Filtro global -->
      <div class="px-4 py-2 bg-white border-t border-gray-300">
        <input
          v-model="globalFilter"
          type="search"
          aria-label="Filtrar jugadores"
          placeholder="Filtrar por posición, nombre, club o cantidad..."
          class="w-full text-base border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primaryGreen"
        />
      </div>

      <!-- Tabla de jugadores -->
      <div
        class="px-4 py-1 bg-white text-gray-900 rounded-none border-t border-gray-300 overflow-x-auto"
        v-if="filteredPlayers.length > 0"
      >
        <table class="w-full min-w-[700px] text-left border-collapse" role="table" aria-label="Estadísticas de jugadores">
          <thead>
            <tr class="border-b border-gray-300">
              <th
                scope="col"
                class="py-3 pr-3 font-semibold text-base w-16 text-center cursor-pointer select-none"
                @click="sortBy('rank')"
                :aria-sort="sortKey === 'rank' ? (sortOrder === 'asc' ? 'ascending' : 'descending') : 'none'"
                tabindex="0"
                @keydown.enter.prevent="sortBy('rank')"
              >
                Pos
                <span v-if="sortKey === 'rank'">
                  <span v-if="sortOrder === 'asc'">▲</span>
                  <span v-else>▼</span>
                </span>
              </th>
              <th class="py-3 pr-3 font-semibold text-base w-16"></th>
              <th
                scope="col"
                class="py-3 pr-3 font-semibold text-base cursor-pointer"
                @click="sortBy('name')"
                :aria-sort="sortKey === 'name' ? (sortOrder === 'asc' ? 'ascending' : 'descending') : 'none'"
                tabindex="0"
                @keydown.enter.prevent="sortBy('name')"
              >
                Nombre
                <span v-if="sortKey === 'name'">
                  <span v-if="sortOrder === 'asc'">▲</span>
                  <span v-else>▼</span>
                </span>
              </th>
              <th
                scope="col"
                class="py-3 pr-3 font-semibold text-base w-20 cursor-pointer"
                tabindex="0"
                @keydown.enter.prevent="sortBy('club')"
              >
                <!-- Logo columna vacía para club -->
              </th>
              <th
                scope="col"
                class="py-3 pr-3 font-semibold text-base w-32 cursor-pointer"
                @click="sortBy('clubName')"
                :aria-sort="sortKey === 'clubName' ? (sortOrder === 'asc' ? 'ascending' : 'descending') : 'none'"
                tabindex="0"
                @keydown.enter.prevent="sortBy('clubName')"
              >
                Club
                <span v-if="sortKey === 'clubName'">
                  <span v-if="sortOrder === 'asc'">▲</span>
                  <span v-else>▼</span>
                </span>
              </th>
              <th
                scope="col"
                class="py-3 font-semibold text-base w-24 text-right cursor-pointer"
                @click="sortBy('statValue')"
                :aria-sort="sortKey === 'statValue' ? (sortOrder === 'asc' ? 'ascending' : 'descending') : 'none'"
                tabindex="0"
                @keydown.enter.prevent="sortBy('statValue')"
              >
                Cantidad
                <span v-if="sortKey === 'statValue'">
                  <span v-if="sortOrder === 'asc'">▼</span>
                  <span v-else>▲</span>
                </span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="player in filteredPlayers"
              :key="player.id"
              class="border-b border-gray-200 hover:bg-gray-50"
            >
              <td class="py-3 pr-3 font-mono text-center font-normal align-middle tabular-nums">{{ player.rank }}</td>
              <td class="py-1 pr-3">
                <img
                  :src="player.playerImage"
                  :alt="player.playerName"
                  class="w-12 h-12 object-cover object-top rounded-none"
                  @error="onImageError"
                />
              </td>
              <td class="py-3 pr-3 truncate" :title="player.firstName + ' ' + player.lastName">
                <span class="font-normal">
                    {{ getFirstName(topPlayer.firstName) }}
                    <template v-if="getSecondName(topPlayer.firstName)"> {{ getSecondName(topPlayer.firstName) }}</template>
                </span>
                <strong>{{ player.lastName }}</strong>
              </td>
              <td class="py-3 pr-3 text-left flex items-center space-x-2">
                <img :src="player.clubLogo" alt="Logo equipo" class="w-6 h-6 object-contain" />
              </td>
              <td class="py-3 pr-3 truncate max-w-[180px]" :title="player.clubName">{{ player.clubName }}</td>
              <td class="py-3 font-normal text-right tabular-nums">{{ player.statValue }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="p-4 text-center text-gray-600">No hay jugadores que coincidan con el filtro.</div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  title: { type: String, default: 'Goles' },
  players: {
    type: Array,
    default: () => [
    ],
  },
  backgroundColor: { type: String, default: 'transparent' },
})

const defaultPlayerImage = 'https://assets.bundesliga.com/placeholder/resp_live_placeholder_player.png'

function onImageError(event) {
  event.target.src = defaultPlayerImage
}

// Retorna el primer nombre (antes del primer espacio)
function getFirstName(fullName) {
  if (!fullName) return ''
  const parts = fullName.trim().split(' ')
  return parts[0]
}

// Retorna el resto de nombres (después del primer espacio), si existe
function getSecondName(fullName) {
  if (!fullName) return ''
  const parts = fullName.trim().split(' ')
  return parts.length > 1 ? ' ' + parts.slice(1).join(' ') : ''
}

const topPlayer = computed(() => (props.players && props.players.length > 0 ? props.players[0] : null))

const globalFilter = ref('')
const sortKey = ref('rank')
const sortOrder = ref('asc')

// Cambia la columna de orden y alterna asc/desc
function sortBy(field) {
  if (sortKey.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = field
    sortOrder.value = 'asc'
  }
}

// Filtra y ordena la lista de jugadores según filtro global y orden
const filteredPlayers = computed(() => {
  let players = props.players

  if (globalFilter.value.trim() !== '') {
    const filter = globalFilter.value.trim().toLowerCase()
    players = players.filter((player) => {
      const rankStr = player.rank.toString()
      const fullName = (player.firstName + ' ' + player.lastName).toLowerCase()
      const clubName = player.clubName ? player.clubName.toLowerCase() : ''
      const statValStr = player.statValue.toString()
      const position = player.position ? player.position.toLowerCase() : ''

      return (
        rankStr.includes(filter) ||
        fullName.includes(filter) ||
        clubName.includes(filter) ||
        statValStr.includes(filter) ||
        position.includes(filter)
      )
    })
  }

  return players.slice().sort((a, b) => {
    let valA, valB

    switch (sortKey.value) {
      case 'rank':
        valA = a.rank
        valB = b.rank
        break
      case 'name':
        valA = (a.firstName + ' ' + a.lastName).toLowerCase()
        valB = (b.firstName + ' ' + b.lastName).toLowerCase()
        break
      case 'club':
        valA = (a.clubName || '').toLowerCase()
        valB = (b.clubName || '').toLowerCase()
        break
      case 'clubName':
        valA = (a.clubName || '').toLowerCase()
        valB = (b.clubName || '').toLowerCase()
        break
      case 'statValue':
        valA = a.statValue
        valB = b.statValue
        break
      default:
        valA = a.rank
        valB = b.rank
    }

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })
})
</script>
