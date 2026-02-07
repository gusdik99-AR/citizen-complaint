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
        <h2 class="font-semibold text-gray-800 text-lg">REGISTRASI</h2>
      </div>

      <!-- Error Message -->
      <div v-if="form.errors.error" class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        {{ form.errors.error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-4">
        <!-- NIK -->
        <div>
          <label for="nik" class="block text-gray-700 text-sm font-medium mb-1.5">
            NIK <span class="text-red-500">*</span>
          </label>
          <input
            id="nik"
            v-model="form.nik"
            type="text"
            maxlength="16"
            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
            :class="{ 'border-red-500': form.errors.nik }"
            placeholder="16 digit NIK"
          />
          <p v-if="form.errors.nik" class="text-red-500 text-xs mt-1">
            {{ form.errors.nik }}
          </p>
        </div>

        <!-- Nama Lengkap -->
        <div>
          <label for="nama_lengkap" class="block text-gray-700 text-sm font-medium mb-1.5">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input
            id="nama_lengkap"
            v-model="form.nama_lengkap"
            type="text"
            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
            :class="{ 'border-red-500': form.errors.nama_lengkap }"
            placeholder="Nama lengkap Anda"
          />
          <p v-if="form.errors.nama_lengkap" class="text-red-500 text-xs mt-1">
            {{ form.errors.nama_lengkap }}
          </p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700 text-sm font-medium mb-1.5">
            Email <span class="text-red-500">*</span>
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
            Password <span class="text-red-500">*</span>
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
            :class="{ 'border-red-500': form.errors.password }"
            placeholder="Minimal 6 karakter"
            autocomplete="new-password"
          />
          <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">
            {{ form.errors.password }}
          </p>
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-1.5">
            Konfirmasi Password <span class="text-red-500">*</span>
          </label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="w-full rounded-lg border border-gray-300 py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400"
            placeholder="Ulangi password"
            autocomplete="new-password"
          />
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-full py-3 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed mt-6"
        >
          <span v-if="form.processing">Memproses...</span>
          <span v-else>Daftar</span>
        </button>
      </form>

      <!-- Login Link -->
      <div class="text-center pt-2">
        <p class="text-gray-600 text-sm">
          Sudah punya akun?
          <Link href="/login" class="text-yellow-600 hover:text-yellow-700 font-semibold">
            Login sekarang
          </Link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const form = useForm({
  nik: '',
  nama_lengkap: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/register', {
    preserveScroll: true,
    onSuccess: (response) => {
      // Tampilkan SweetAlert sukses
      Swal.fire({
        icon: 'success',
        title: 'Registrasi Berhasil!',
        text: 'Akun Anda telah berhasil dibuat. Silakan login untuk melanjutkan.',
        confirmButtonText: 'Login Sekarang',
        confirmButtonColor: '#FBBF24',
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          // Inertia akan auto-redirect dari backend
          window.location.href = '/login';
        }
      });
    },
    onError: () => {
      // Reset password fields on error
      form.password = '';
      form.password_confirmation = '';
    },
  });
};
</script>
