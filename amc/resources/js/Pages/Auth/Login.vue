<script setup>
import { ref } from 'vue'
import { useForm, Head, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const passwordType = ref('password')
const togglePassword = () => {
  passwordType.value = passwordType.value === 'password' ? 'text' : 'password'
}

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Iniciar sesión" />

    <div class="min-h-screen flex bg-gray-900 text-gray-100">
      <!-- Lado izquierdo -->
      <div class="hidden lg:flex w-1/2 bg-gray-800 text-red-500 flex-col justify-center px-16">
        <div class="text-center">
            <div class="mb-10">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mx-auto h-12 w-12 text-red-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
                >
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="white" />
                <path
                    stroke="currentColor"
                    stroke-width="1.5"
                    d="M12 3v18M3 12h18M7.5 7.5l9 9M7.5 16.5l9-9"
                />
                <path
                    stroke="currentColor"
                    stroke-width="1"
                    d="M12 6l-1 3 3 1-3 1 1 3 1-3 3-1-3-1 1-3z"
                    fill="none"
                />
            </svg>
            </div>
            <h1 class="text-4xl font-bold mb-2">Comunidad AMC</h1>
            <h2 class="text-xl font-semibold mb-6 text-gray-300">Torneos de EA FC</h2>
            <p class="text-lg max-w-md mx-auto text-gray-300">
            Deja atrás las frustraciones de jugar solo. Únete a torneos competitivos, mejora tus habilidades y conecta con otros fanáticos de EA FC para vivir la mejor experiencia de juego.
            </p>
            <p class="mt-10 text-sm text-gray-500">&copy; 2025 SaleSkip. Todos los derechos reservados.</p>
        </div>
        </div>

      <!-- Lado derecho -->
      <div class="flex flex-col justify-center w-full lg:w-1/2 bg-gray-900 px-12 py-20">
        <h2 class="text-2xl font-bold mb-2 text-white">¡Bienvenido de nuevo!</h2>
        <p class="mb-8 text-sm text-gray-400">
          ¿No tienes una cuenta?
          <Link href="/register" class="underline text-red-500 hover:text-red-700">
            Crea una ahora,
          </Link> ¡es GRATIS! Toma menos de un minuto.
        </p>

        <form @submit.prevent="submit" class="space-y-6 max-w-md">
          <div>
            <InputLabel for="email" value="Correo electrónico" class="text-gray-300" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full bg-gray-800 border border-gray-700 rounded-md px-3 py-2 text-gray-100 placeholder-gray-500"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
            />
            <InputError :message="form.errors.email" class="mt-1 text-red-500" />
          </div>

          <div>
            <InputLabel for="password" value="Contraseña" class="text-gray-300" />
            <div class="relative">
              <TextInput
                :type="passwordType"
                id="password"
                class="mt-1 block w-full bg-gray-800 border border-gray-700 rounded-md px-3 py-2 pr-10 text-gray-100 placeholder-gray-500"
                v-model="form.password"
                required
                autocomplete="current-password"
              />
              <button
                type="button"
                @click="togglePassword"
                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none"
                tabindex="-1"
                aria-label="Alternar visibilidad de contraseña"
              >
                <svg v-if="passwordType === 'password'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.973 9.973 0 012.119-3.35m1.618-1.618A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.985 9.985 0 01-4.152 5.17M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>
              </button>
            </div>
            <InputError :message="form.errors.password" class="mt-1 text-red-500" />
          </div>

          <div class="flex items-center justify-between">
            <label class="inline-flex items-center text-gray-300">
              <input
                type="checkbox"
                class="form-checkbox text-red-500"
                v-model="form.remember"
              />
              <span class="ml-2 text-sm">Recordarme</span>
            </label>

            <Link href="/password/reset" class="text-sm text-red-500 hover:underline">
              ¿Olvidaste tu contraseña?
            </Link>
          </div>

          <PrimaryButton
            type="submit"
            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded flex justify-center"
            :disabled="form.processing"
            >
            Iniciar sesión
            </PrimaryButton>

          <Link
            href="/register"
            class="w-full mt-3 border border-gray-700 rounded text-gray-300 py-2 flex items-center justify-center space-x-2 hover:bg-gray-800 text-center"
          >
            <span>Registrarse</span>
          </Link>
        </form>
      </div>
    </div>
  </GuestLayout>
</template>
