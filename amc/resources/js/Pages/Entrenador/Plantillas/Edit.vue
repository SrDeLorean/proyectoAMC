<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { computed, watchEffect, ref } from 'vue'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import CrudForm from '@/Components/Forms/CrudForm.vue'
import FormacionDiagram from '@/Components/Equipo/FormacionDiagram.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  plantilla: Object,
})

const page = usePage()

// Diccionario de formaciones y posiciones
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
  "5-2-2-1": ["PO", "DFI", "DFC", "DFD", "LD", "LI", "MCI", "MCR", "EI", "ED", "DC"],
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

// Función para obtener rol padre por código posición
function obtenerRolPorPosicion(pos) {
  if (pos === 'PO') return 'Portero'
  if (['DFC','DFD','DFI','LD','LI'].includes(pos)) return 'Defensores'
  if (['MCD','MDD','MDI','MC','MCI','MCR','MD','MI','MCO','MOI','MOD'].includes(pos)) return 'Mediocampistas'
  if (['SDD','SDI','MP','ED','EI','DD','DI','DC'].includes(pos)) return 'Delanteros'
  return null
}

// Computed para obtener la formación raw como string
const formacionRaw = computed(() => {
  const f = props.plantilla.equipo?.formacion
  if (!f) return ''
  if (typeof f === 'string') return f
  if (typeof f.nombre === 'string') return f.nombre.includes('-')
    ? f.nombre
    : f.nombre.trim().split(/\s+/).join('-')
  return ''
})

// Posiciones permitidas según la formación actual
const posicionesPermitidas = computed(() => {
  return formacionesConPosiciones[formacionRaw.value] || []
})

// Roles permitidos: solo aquellos que tienen al menos una posición en la formación actual
const rolesFiltrados = computed(() => {
  const pos = posicionesPermitidas.value
  const rolesSet = new Set()
  pos.forEach(p => {
    const rol = obtenerRolPorPosicion(p)
    if (rol) rolesSet.add(rol)
  })
  return Array.from(rolesSet).map(r => ({
    value: r,
    label: r === 'Delanteros' ? 'Delantero' : r,
  }))
})

// Posiciones filtradas según rol seleccionado y formación
const posicionesFiltradas = computed(() => {
  const rol = form.rol
  if (!rol) return []
  return posicionesPermitidas.value
    .filter(pos => obtenerRolPorPosicion(pos) === rol)
    .map(pos => ({
      value: pos,
      label: `${pos} - ${etiquetas[pos] || pos}`
    }))
})

// Función para normalizar rol de datos cargados
function normalizarRol(rol) {
  if (!rol) return ''
  if (rol.toLowerCase() === 'delantero') return 'Delanteros'
  return rol
}

const form = useForm({
  jugadorName: props.plantilla.jugador?.name || 'Nombre no disponible',
  rol: normalizarRol(props.plantilla.rol) || '',
  posicion: props.plantilla.posicion || '',
  numero: props.plantilla.numero || '',
  titular: props.plantilla.titular || false,
})

function submit() {
  form.put(route('entrenador.plantillas.update', props.plantilla.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Jugador actualizado correctamente',
        timer: 2500,
        showConfirmButton: false,
      })
    }
  })
}

// Mostrar alerta de éxito en flash
const alertShown = ref(false)
watchEffect(() => {
  const msg = page.props.flash?.success
  if (msg && !alertShown.value) {
    alertShown.value = true
    Swal.fire({
      icon: 'success',
      title: 'Éxito',
      text: msg,
      timer: 2500,
      showConfirmButton: false,
    })
  }
})
</script>

<template>
  <EntrenadorLayout>
    <template #title>Editar Jugador</template>

    <div class="p-6 text-white max-w-xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Editar Jugador en Plantilla</h1>

      <CrudForm
        :fields="[
          { name: 'jugadorName', label: 'Jugador', type: 'text', readonly: true },
          { name: 'rol', label: 'Rol', type: 'select', optionsKey: 'rol', required: true },
          { name: 'posicion', label: 'Posición', type: 'select', optionsKey: 'posicion', required: true },
          { name: 'numero', label: 'Número', type: 'number', required: true },
          { name: 'titular', label: 'Titular', type: 'checkbox' }
        ]"
        :form="form"
        :submit="submit"
        submit-label="Guardar Cambios"
        :selectOptions="{ rol: rolesFiltrados, posicion: posicionesFiltradas }"
      />

      <h2 class="text-xl font-semibold mt-10 mb-4 text-center">
        Diagrama de Formación: {{ formacionRaw }}
      </h2>

      <FormacionDiagram :formacion="formacionRaw" :jugadores="[props.plantilla]" />
    </div>
  </EntrenadorLayout>
</template>
