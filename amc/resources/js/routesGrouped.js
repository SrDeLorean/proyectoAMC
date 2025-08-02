export const groupedRoutes = [
  {
    groupName: 'Usuario',
    routes: [
      { name: 'Usuarios', routeName: 'admin.users.index' },
      { name: 'Equipos', routeName: 'equipos.index' },
      { name: 'Plantillas', routeName: 'admin.plantillas.index' },
    ],
  },
  {
    groupName: 'Torneo',
    routes: [
      { name: 'Competencias', routeName: 'competencias.index' },
      { name: 'Temporadas', routeName: 'temporadas.index' },
      { name: 'Temporada Competencias', routeName: 'temporada-competencias.index' },
      { name: 'Temporada Equipos', routeName: 'temporada-equipos.index' },
    ],
  },
]
