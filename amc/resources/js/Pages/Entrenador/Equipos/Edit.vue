<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { watchEffect } from 'vue'
import EntrenadorLayout from '@/Layouts/EntrenadorLayout.vue'
import EquipoForm from '@/Components/Forms/CrudForm.vue'
import Swal from 'sweetalert2'

const page = usePage()

const props = defineProps({
  equipo: Object,
  usuarios: Array,
  formaciones: Array,
  success: String,
  info: String,
})

const form = useForm({
  nombre: props.equipo.nombre || '',
  descripcion: props.equipo.descripcion || '',
  color_primario: props.equipo.color_primario || '#000000',
  color_secundario: props.equipo.color_secundario || '#ffffff',
  logo: null,
  id_formacion: props.equipo.id_formacion || '',
  instagram: props.equipo.instagram || '',
  twitch: props.equipo.twitch || '',
  youtube: props.equipo.youtube || '',
  id_usuario: props.equipo.id_usuario || '',
  id_usuario2: props.equipo.id_usuario2 || ''
})

function mostrarAlerta(tipo, titulo, texto) {
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: texto,
    timer: 3000,
    showConfirmButton: false,
  })
}

function submit() {
  form.post(`/entrenador/equipos/${props.equipo.id}`, {
    method: 'put',
    forceFormData: true,
    onError: () => {
      mostrarAlerta('error', 'Error', 'Hubo un error al actualizar el equipo.')
    }
  })
}

watchEffect(() => {
  if (props.success) {
    mostrarAlerta('success', '¡Éxito!', props.success)
  } else if (props.info) {
    mostrarAlerta('info', 'Información', props.info)
  }
})

const fields = [
  { name: 'nombre', label: 'Nombre', type: 'text', required: true, readonly: true },
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
  <EntrenadorLayout>
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
  </EntrenadorLayout>
</template>
