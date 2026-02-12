<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-sm md:max-w-md lg:max-w-lg bg-white rounded-2xl shadow-lg p-6 space-y-4">
      <!-- Header Logo & Title -->
      <div class="flex items-center gap-2 border-b pb-3">
        <img src="/logo.png" alt="Logo" class="h-14 w-14 object-contain" />
        <div class="flex flex-col">
          <h1 class="font-bold text-gray-800 text-base leading-tight">E-Lapor</h1>
          <p class="text-gray-500 text-xs leading-tight">Pemkab Pemalang</p>
        </div>
      </div>

      <!-- Title -->
      <div class="text-center -mt-1">
        <h2 class="font-semibold text-gray-800 text-lg">LOGIN</h2>
      </div>

      <!-- Success Message -->
      <div v-if="$page.props.flash?.success" class="p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ $page.props.flash.success }}
      </div>
        <!-- Or sign in with Google -->
        <!-- <div class="pt-4">
          <div class="flex items-center gap-3">
            <div class="flex-1 h-px bg-gray-200"></div>
            <div class="text-xs text-gray-400 uppercase">atau</div>
            <div class="flex-1 h-px bg-gray-200"></div>
          </div>
          <a href="/auth/google/redirect"
             class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-3 border rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-5 h-5" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <path fill="#EA4335" d="M24 9.5c3.54 0 6.38 1.52 8.28 2.78l6.06-6.06C35.84 3 30.34 1 24 1 14.73 1 6.86 6.92 3.47 14.96l7.6 5.89C12.95 16.2 18.98 9.5 24 9.5z"/>
              <path fill="#34A853" d="M46.5 24c0-1.62-.14-3.19-.4-4.71H24v9.06h12.8c-.55 2.98-2.23 5.49-4.76 7.2l7.28 5.62C43.97 36.3 46.5 30.43 46.5 24z"/>
              <path fill="#4A90E2" d="M10.75 29.85A14.93 14.93 0 0112 24c0-1.9-.34-3.72-.95-5.35L3.47 14.96C5.68 19.86 8.81 24.03 12.9 27.2l-2.15 2.65z"/>
              <path fill="#FBBC05" d="M24 46c6.44 0 11.94-2.1 15.84-5.7l-7.28-5.62C29.9 34.54 27.78 35.5 24 35.5c-5 0-10.03-6.7-12.93-10.39l-7.6 5.89C6.86 41.08 14.73 46 24 46z"/>
            </svg>
            Masuk dengan Google
          </a>
        </div> -->

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-4">
        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700 text-sm font-medium mb-1.5">
            Email
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
            :class="{ 'border-red-500': form.errors.email }"
            placeholder="nama@email.com"
            autocomplete="email"
          />
          <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">
            {{ form.errors.email }}
          </p>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-gray-700 text-sm font-medium mb-1.5">
            Password
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
            :class="{ 'border-red-500': form.errors.password }"
            placeholder="••••••••"
            autocomplete="current-password"
          />
          <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">
            {{ form.errors.password }}
          </p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-full py-3 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed mt-6"
        >
          <span v-if="form.processing">Memproses...</span>
          <span v-else>Masuk</span>
        </button>
      </form>

      <!-- Register Link -->
      <div class="text-center pt-2">
        <p class="text-gray-600 text-sm">
          Belum punya akun?
          <Link href="/register" class="text-yellow-600 hover:text-yellow-700 font-semibold">
            Daftar sekarang
          </Link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const submit = () => {
  form.post('/login', {
    onFinish: () => {
      // Reset password field on error
      if (form.errors.email || form.errors.password) {
        form.password = '';
      }
    },
  });
};
</script>
