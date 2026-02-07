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
