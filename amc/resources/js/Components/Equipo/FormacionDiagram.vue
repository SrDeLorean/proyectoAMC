<template>
  <div class="flex flex-col items-center mt-6 space-y-4 px-4">
    <!-- Cancha responsiva con proporción 2:3 -->
    <div
      class="relative w-full max-w-[600px] aspect-[2/3] rounded-lg shadow-lg overflow-visible mx-auto"
      :style="{
        backgroundImage: `url(${fondo})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        boxShadow: '0 0 15px rgba(0,0,0,0.3)',
        borderRadius: '12px',
      }"
    >
      <div
        v-for="(pos, index) in posiciones"
        :key="index"
        :style="{
          position: 'absolute',
          top: posicionY(pos),
          left: posicionX(pos),
          transform: 'translate(-50%, -50%)',
          cursor: 'default',
          width: 'clamp(60px, 12vw, 80px)',
          height: 'clamp(60px, 12vw, 80px)',
          textAlign: 'center',
        }"
      >
        <div
          class="bg-green-900 bg-opacity-90 text-white text-xs flex items-center justify-center flex-col rounded-full shadow-xl border-2 border-white px-2 py-2 transition-transform duration-200 ease-in-out hover:scale-110"
          style="backdrop-filter: blur(6px);"
          :title="`Posición: ${pos} - ${etiquetas[pos]}`"
        >
          <span class="font-extrabold text-sm sm:text-base select-none">{{ pos }}</span>
          <span class="text-[11px] sm:text-sm leading-tight select-none">{{ etiquetas[pos] }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import fondo from '@/assets/cancha_vertical.png'

const props = defineProps({
  formacion: String,
})

const formacionSeleccionada = ref(props.formacion || '4-4-2')

const formacionesConPosiciones = {
  "3-1-4-2": ["PO", "DFI", "DFC", "DFD", "MCD", "MCI", "MCR", "MI", "MD", "DI", "DD"],
  "3-4-1-2": ["PO", "DFI", "DFC", "DFD", "MCI", "MCR", "MI", "MD", "MCO", "DI", "DD"],
  "3-5-2": ["PO", "DFI", "DFC", "DFD", "MDI", "MDD", "MI", "MD", "MCO", "DI", "DD"],
  "3-4-3": ["PO", "DFI", "DFC", "DFD", "MCI", "MCR", "MI", "MD", "EI", "ED", "DC"],
  "3-4-2-1": ["PO", "DFI", "DFC", "DFD", "MCI", "MCR", "MI", "MD", "MOI", "MOD", "DC"],
  "4-1-4-1": ["PO", "LD", "DFD", "DFI", "LI", "MCD", "MCI", "MCR", "MI", "MD", "DC"],
  "4-2-3-1": ["PO", "LD", "DFD", "DFI", "LI", "MDI", "MDD", "MOI", "MOD", "MCO", "DC"],
  "4-2-3-1(2)": ["PO", "LD", "DFD", "DFI", "LI", "MDD", "MDI", "MI", "MD", "MCO", "DC"],
  "4-5-1(2)": ["PO", "LD", "DFD", "DFI", "LI", "MC", "MI", "MD", "MOI", "MOD", "DC"],
  "4-5-1": ["PO", "LD", "DFD", "DFI", "LI", "MC", "MCI", "MCR", "MI", "MD", "DC"],
  "4-3-2-1": ["PO", "LD", "DFD", "DFI", "LI", "MC", "MCI", "MCR", "MOI", "MOD", "DC"],
  "4-4-1-1": ["PO", "LD", "DFD", "DFI", "LI", "MCI", "MCR", "MI", "MD", "MCO", "DC"],
  "4-4-2(2)": ["PO", "LD", "DFD", "DFI", "LI", "MDI", "MDD", "MI", "MD", "DI", "DD"],
  "4-4-2": ["PO", "LD", "DFD", "DFI", "LI", "MCI", "MCR", "MI", "MD", "DI", "DD"],
  "4-1-2-1-2(2)": ["PO", "LD", "DFD", "DFI", "LI", "MCD", "MCI", "MCR", "MCO", "DI", "DD"],
  "4-1-2-1-2": ["PO", "LD", "DFD", "DFI", "LI", "MCD", "MI", "MD", "MCO", "DI", "DD"],
  "4-2-2-2": ["PO", "LD", "DFD", "DFI", "LI", "MDI", "MDD", "MOI", "MOD", "DI", "DD"],
  "4-3-1-2": ["PO", "LD", "DFD", "DFI", "LI", "MC", "MCI", "MCR", "MCO", "DI", "DD"],
  "4-1-3-2": ["PO", "LD", "DFD", "DFI", "LI", "MCD", "MC", "MI", "MD", "DI", "DD"],
  "4-3-3(4)": ["PO", "LD", "DFD", "DFI", "LI", "MCI", "MCR", "MCO", "EI", "ED", "DC"],
  "4-3-3(3)": ["PO", "LD", "DFD", "DFI", "LI", "MDI", "MDD", "MC", "EI", "ED", "DC"],
  "4-3-3(2)": ["PO", "LD", "DFD", "DFI", "LI", "MCD", "MCI", "MCR", "EI", "ED", "DC"],
  "4-3-3": ["PO", "LD", "DFD", "DFI", "LI", "MCI", "MC", "MCR", "EI", "ED", "DC"],
  "4-2-1-3": ["PO", "LD", "DFD", "DFI", "LI", "MDI", "MDD", "MCO", "EI", "ED", "DC"],
  "4-2-4": ["PO", "LD", "DFD", "DFI", "LI", "MCI", "MCR", "EI", "ED", "DI", "DD"],
  "5-4-1(2)": ["PO", "DFI", "DFC", "DFD", "LD", "LI", "MCI", "MCR", "MI", "MD", "DC"],
  "5-2-1-2": ["PO", "DFI", "DFC", "DFD", "LD", "LI", "MCI", "MCR", "MCO", "DI", "DD"],
  "5-3-2": ["PO", "DFI", "DFC", "DFD", "LD", "LI", "MCD", "MCI", "MCR", "DI", "DD"],
  "5-2-2-1": ["PO", "DFI", "DFC", "DFD", "LD", "LI", "MCI", "MCR", "EI", "ED", "DC"]
}

const etiquetas = {
  PO: 'Portero',
  DFC: 'Defensa Central',
  DFD: 'Defensa Derecho',
  DFI: 'Defensa Izquierdo',
  LD: 'Lateral Derecho',
  LI: 'Lateral Izquierdo',
  MCD: 'Medio Centro Defensivo',
  MDD: 'Mediocampista Defensivo Derecho',
  MDI: 'Mediocampista Defensivo Izquierdo',
  MC: 'Mediocampista Central',
  MCI: 'Mediocampista Central Izquierdo',
  MCR: 'Mediocampista Central Derecho',
  MD: 'Carrilero Derecho',
  MI: 'Carrilero Izquierdo',
  MCO: 'Medio Ofensivo Central',
  MOI: 'Medio Ofensivo Izquierdo',
  MOD: 'Medio Ofensivo Derecho',
  EI: 'Extremo Izquierdo',
  ED: 'Extremo Derecho',
  DI: 'Delantero Izquierdo',
  DD: 'Delantero Derecho',
  DC: 'Delantero Centro',
  SDD: 'Segundo Delantero Derecho',
  SDI: 'Segundo Delantero Izquierdo',
  MP: 'Falso 9',
}

const posiciones = ref([])

watch(formacionSeleccionada, (nueva) => {
  posiciones.value = formacionesConPosiciones[nueva] || []
}, { immediate: true })

const coordenadas = {
  PO: [92, 50],
  DFI: [76, 35],
  DFC: [80, 50],
  DFD: [76, 65],
  LI: [70, 20],
  LD: [70, 80],
  MCD: [55, 50],
  MDD: [55, 65],
  MDI: [55, 35],
  MCI: [45, 32],
  MC: [45, 50],
  MCR: [45, 67],
  MI: [42, 15],
  MD: [42, 85],
  MCO: [30, 50],
  MOI: [30, 25],
  MOD: [30, 75],
  EI: [18, 20],
  ED: [18, 80],
  DI: [15, 35],
  DD: [15, 65],
  DC: [15, 50],
  SDI: [25, 48],
  SDD: [25, 52],
}

const posicionY = (pos) => `${coordenadas[pos]?.[0] || 0}%`
const posicionX = (pos) => `${coordenadas[pos]?.[1] || 0}%`
</script>
