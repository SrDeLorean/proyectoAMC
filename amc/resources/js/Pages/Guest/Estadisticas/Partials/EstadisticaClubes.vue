<script setup>
import { ref } from 'vue'

const clubes = ref([
  {
    posicion: 1,
    nombre: 'FC Bayern München',
    logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/FC_Bayern_München_logo_(2017).svg/50px-FC_Bayern_München_logo_(2017).svg.png',
    PJ: 34,
    PG: 24,
    PE: 6,
    PP: 4,
    GF: 89,
    GC: 32,
    Dif: 57,
    Pts: 78,
  },
  {
    posicion: 2,
    nombre: 'Borussia Dortmund',
    logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Borussia_Dortmund_logo.svg/50px-Borussia_Dortmund_logo.svg.png',
    PJ: 34,
    PG: 22,
    PE: 7,
    PP: 5,
    GF: 75,
    GC: 36,
    Dif: 39,
    Pts: 73,
  },
  {
    posicion: 3,
    nombre: 'RB Leipzig',
    logo: 'https://upload.wikimedia.org/wikipedia/en/thumb/0/04/RB_Leipzig_2014_logo.svg/50px-RB_Leipzig_2014_logo.svg.png',
    PJ: 34,
    PG: 20,
    PE: 6,
    PP: 8,
    GF: 68,
    GC: 40,
    Dif: 28,
    Pts: 66,
  },
  {
    posicion: 4,
    nombre: 'VfL Wolfsburg',
    logo: 'https://upload.wikimedia.org/wikipedia/en/thumb/0/0a/VfL_Wolfsburg_logo.svg/50px-VfL_Wolfsburg_logo.svg.png',
    PJ: 34,
    PG: 18,
    PE: 8,
    PP: 8,
    GF: 62,
    GC: 45,
    Dif: 17,
    Pts: 62,
  },
  {
    posicion: 5,
    nombre: 'Eintracht Frankfurt',
    logo: 'https://upload.wikimedia.org/wikipedia/en/thumb/0/02/Eintracht_Frankfurt_Logo.svg/50px-Eintracht_Frankfurt_Logo.svg.png',
    PJ: 34,
    PG: 17,
    PE: 9,
    PP: 8,
    GF: 55,
    GC: 41,
    Dif: 14,
    Pts: 60,
  },
])
</script>

<template>
  <div class="max-w-7xl mx-auto p-6 bg-gray-900 rounded-lg shadow-lg text-white">
    <h1 class="text-3xl font-bold mb-6 text-red-600 drop-shadow-[0_0_4px_rgba(220,38,38,0.9)]">
      Estadísticas de Clubes - Bundesliga
    </h1>
    <div class="overflow-x-auto">
      <table class="min-w-full border-collapse border border-gray-700">
        <thead>
          <tr class="bg-red-700 text-white">
            <th class="p-3 border border-gray-600 w-[50px] text-center">#</th>
            <th class="p-3 border border-gray-600 min-w-[180px]">Equipo</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">PJ</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">PG</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">PE</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">PP</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">GF</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">GC</th>
            <th class="p-3 border border-gray-600 w-[50px] text-center">Dif</th>
            <th class="p-3 border border-gray-600 w-[60px] text-center">Pts</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="club in clubes"
            :key="club.posicion"
            class="even:bg-gray-800 hover:bg-red-900 cursor-pointer transition"
          >
            <td class="p-2 border border-gray-600 text-center font-semibold">{{ club.posicion }}</td>
            <td class="p-2 border border-gray-600 flex items-center space-x-3">
              <img :src="club.logo" alt="Logo" class="w-8 h-8 object-contain" />
              <span>{{ club.nombre }}</span>
            </td>
            <td class="p-2 border border-gray-600 text-center">{{ club.PJ }}</td>
            <td class="p-2 border border-gray-600 text-center">{{ club.PG }}</td>
            <td class="p-2 border border-gray-600 text-center">{{ club.PE }}</td>
            <td class="p-2 border border-gray-600 text-center">{{ club.PP }}</td>
            <td class="p-2 border border-gray-600 text-center">{{ club.GF }}</td>
            <td class="p-2 border border-gray-600 text-center">{{ club.GC }}</td>
            <td class="p-2 border border-gray-600 text-center">{{ club.Dif }}</td>
            <td class="p-2 border border-gray-600 text-center font-bold">{{ club.Pts }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
