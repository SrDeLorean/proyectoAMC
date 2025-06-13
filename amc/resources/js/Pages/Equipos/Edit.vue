<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EquipoForm from '@/Components/Forms/CrudForm.vue'

const props = defineProps({
  equipo: Object,        // Objeto con los datos del equipo a editar
  usuarios: Array,       // [{ id: 1, name: 'Juan' }, ...]
  formaciones: Array     // [{ id: 1, nombre: '4-4-2' }, ...]
})

// Inicializamos el formulario con los datos que vienen del backend (equipo)
const form = useForm({
  nombre: props.equipo.nombre || '',
  descripcion: props.equipo.descripcion || '',
  color_primario: props.equipo.color_primario || '#000000',
  color_secundario: props.equipo.color_secundario || '#ffffff',
  logo: null,  // Siempre nulo al inicio, el logo solo cambia si se sube uno nuevo
  id_formacion: props.equipo.id_formacion || '',
  instagram: props.equipo.instagram || '',
  twitch: props.equipo.twitch || '',
  youtube: props.equipo.youtube || '',
  id_usuario: props.equipo.id_usuario || '',
  id_usuario2: props.equipo.id_usuario2 || ''
})

const submit = () => {
  form.post(`/equipos/${props.equipo.id}`, {
    method: 'put',
    forceFormData: true
  })
}

const fields = [
  { name: 'nombre', label: 'Nombre', type: 'text', required: true },
  { name: 'descripcion', label: 'Descripción', type: 'textarea' },
  { name: 'color_primario', label: 'Color Primario', type: 'color' },
  { name: 'color_secundario', label: 'Color Secundario', type: 'color' },
  { name: 'logo', label: 'Logo', type: 'file' },
  {
    name: 'id_formacion',
    label: 'Formación',
    type: 'select',
    optionsKey: 'formaciones',
    optionValue: 'id',
    optionLabel: 'nombre',
    required: false
  },
  { name: 'instagram', label: 'Instagram', type: 'text' },
  { name: 'twitch', label: 'Twitch', type: 'text' },
  { name: 'youtube', label: 'YouTube', type: 'text' },
  {
    name: 'id_usuario',
    label: 'Propietario Responsable',
    type: 'select',
    optionsKey: 'usuarios',
    optionValue: 'id',
    optionLabel: 'name',
    required: false
  },
  {
    name: 'id_usuario2',
    label: 'Entrenador Responsable',
    type: 'select',
    optionsKey: 'usuarios',
    optionValue: 'id',
    optionLabel: 'name',
    required: false
  }
]
</script>

<template>
  <AdminLayout>
    <template #title>Editar Equipo</template>
    <div class="p-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-[1200px]">
      <h1 class="text-2xl font-bold mb-4 text-white">Editar Equipo</h1>

      <EquipoForm
        :fields="fields"
        :form="form"
        :submit="submit"
        :selectOptions="{ usuarios: props.usuarios, formaciones: props.formaciones }"
        submit-label="Actualizar Equipo"
      />
    </div>
  </AdminLayout>
</template>
