<script setup>
const props = defineProps({
  local: Object,
  visitante: Object,
  plantillaLocal: Array,
  plantillaVisitante: Array,
})

function agruparPorPosicion(plantilla) {
  const grupos = { POR: [], DEF: [], MED: [], DEL: [] }
  plantilla.forEach(j => {
    const pos = j.posicion.toUpperCase()
    if (pos.includes('POR')) grupos.POR.push(j)
    else if (pos.includes('DF')) grupos.DEF.push(j)
    else if (pos.includes('MC') || pos.includes('MD') || pos.includes('MI')) grupos.MED.push(j)
    else if (pos.includes('DC') || pos.includes('DEL')) grupos.DEL.push(j)
  })
  return grupos
}

function normalizeFoto(foto) {
  if (!foto) return '/images/users/default-user.png'
  if (foto.startsWith('http') || foto.startsWith('/')) return foto
  return '/' + foto
}

const localGrupos = agruparPorPosicion(props.plantillaLocal)
const visitanteGrupos = agruparPorPosicion(props.plantillaVisitante)
</script>

<template>
  <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white p-6 rounded-3xl shadow-2xl border border-gray-700 max-w-7xl mx-auto select-none">
    <!-- Logos y nombres -->
    <div class="flex items-center justify-center mb-6 gap-12">
      <div class="text-center group cursor-default">
        <img
          :src="normalizeFoto(local.logo)"
          alt="Logo local"
          class="w-16 h-16 object-contain mx-auto mb-1 rounded-lg shadow-md bg-gray-800 transition-transform group-hover:scale-110"
        />
        <h2 class="text-lg font-extrabold text-red-500 tracking-wide truncate max-w-[120px]">{{ local.nombre }}</h2>
      </div>

      <span class="text-gray-400 font-semibold text-xl select-none">vs</span>

      <div class="text-center group cursor-default">
        <img
          :src="normalizeFoto(visitante.logo)"
          alt="Logo visitante"
          class="w-16 h-16 object-contain mx-auto mb-1 rounded-lg shadow-md bg-gray-800 transition-transform group-hover:scale-110"
        />
        <h2 class="text-lg font-extrabold text-red-500 tracking-wide truncate max-w-[120px]">{{ visitante.nombre }}</h2>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <template
        v-for="(equipo, index) in [
          { nombre: 'Local', grupos: localGrupos, propietario: local.propietario, entrenador: local.entrenador },
          { nombre: 'Visitante', grupos: visitanteGrupos, propietario: visitante.propietario, entrenador: visitante.entrenador }
        ]"
        :key="index"
      >
        <section
          class="bg-gray-800 bg-opacity-90 backdrop-blur-md rounded-xl p-5 shadow-lg border border-red-600 flex flex-col"
        >
          <h3 class="text-center text-red-500 font-extrabold text-2xl mb-5 tracking-wide select-text">
            Alineación {{ equipo.nombre }}
          </h3>

          <!-- Grupos sin fondo ni borde -->
          <div class="grid grid-cols-1 gap-6">
            <div
              v-for="(jugadores, grupo) in equipo.grupos"
              :key="grupo"
            >
              <h4
                class="text-xs uppercase font-semibold text-red-400 tracking-widest mb-3 border-b border-red-600 pb-1 text-center select-text"
              >
                {{ grupo }}
              </h4>
              <ul class="flex flex-col gap-2 pr-1">
                <li
                  v-for="jugador in jugadores"
                  :key="jugador.jugador.id"
                  class="flex items-center gap-4 bg-gray-900 bg-opacity-70 p-3 rounded-lg shadow-inner border border-red-600 transition hover:bg-red-900 cursor-pointer select-text"
                >
                  <img
                    :src="normalizeFoto(jugador.jugador.foto)"
                    alt="Foto jugador"
                    class="w-14 h-14 rounded-full object-cover border-2 border-gray-600 shadow-md flex-shrink-0"
                  />
                  <span class="w-10 text-red-500 font-bold text-lg select-text text-center flex-shrink-0">
                    {{ jugador.numero }}
                  </span>
                  <p class="flex-1 text-white font-semibold truncate select-text">
                    {{ jugador.jugador.id_ea }}
                  </p>
                  <span
                    class="text-xs font-semibold uppercase bg-red-600 bg-opacity-80 text-white rounded px-3 py-0.5 whitespace-nowrap select-none flex-shrink-0"
                    style="min-width: 50px; text-align: center;"
                  >
                    {{ jugador.posicion }}
                  </span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Cuerpo técnico con estilo igual a jugadores, nombre sin truncar -->
          <div
            v-if="equipo.propietario || equipo.entrenador"
            class="mt-auto pt-6 border-t border-red-600 flex flex-col gap-4"
          >
            <h4 class="text-sm text-red-400 mb-3 font-semibold tracking-wide select-text">
              Cuerpo técnico
            </h4>

            <div
              v-if="equipo.propietario"
              class="flex items-center gap-4 bg-gray-900 bg-opacity-70 p-3 rounded-lg shadow-inner border border-red-600 cursor-default select-text"
            >
              <img
                :src="normalizeFoto(equipo.propietario.foto)"
                alt="Propietario"
                class="w-14 h-14 rounded-full object-cover border-2 border-gray-600 shadow-md flex-shrink-0"
              />
              <p class="flex-1 text-white font-semibold select-text whitespace-normal">
                {{ equipo.propietario.id_ea }}
              </p>
              <span
                class="text-xs font-semibold uppercase bg-red-600 bg-opacity-80 text-white rounded px-3 py-0.5 whitespace-nowrap select-none flex-shrink-0"
                style="min-width: 50px; text-align: center;"
              >
                Propietario
              </span>
            </div>

            <div
              v-if="equipo.entrenador"
              class="flex items-center gap-4 bg-gray-900 bg-opacity-70 p-3 rounded-lg shadow-inner border border-red-600 cursor-default select-text"
            >
              <img
                :src="normalizeFoto(equipo.entrenador.foto)"
                alt="Entrenador"
                class="w-14 h-14 rounded-full object-cover border-2 border-gray-600 shadow-md flex-shrink-0"
              />
              <p class="flex-1 text-white font-semibold select-text whitespace-normal">
                {{ equipo.entrenador.id_ea }}
              </p>
              <span
                class="text-xs font-semibold uppercase bg-red-600 bg-opacity-80 text-white rounded px-3 py-0.5 whitespace-nowrap select-none flex-shrink-0"
                style="min-width: 50px; text-align: center;"
              >
                Entrenador
              </span>
            </div>
          </div>
        </section>
      </template>
    </div>
  </div>
</template>
