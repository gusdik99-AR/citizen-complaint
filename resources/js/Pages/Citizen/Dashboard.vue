<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import CitizenLayout from '@/Layouts/CitizenLayout.vue'

defineProps({
  user: Object,
  myComplaints: {
    type: Array,
    default: () => []
  }
})

// Flash message
const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success)
const showFlash = ref(false)

if (flashSuccess.value) {
  showFlash.value = true
  setTimeout(() => {
    showFlash.value = false
  }, 5000)
}
</script>

<template>
  <Head title="Beranda" />

  <CitizenLayout :user="user">
    <!-- Flash Message -->
    <div v-if="showFlash && flashSuccess" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-medium">{{ flashSuccess }}</span>
      </div>
      <button @click="showFlash = false" class="text-green-700 hover:text-green-900">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Welcome Card -->
    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white mb-6">
      <h2 class="text-2xl font-bold mb-2">Selamat Datang!</h2>
      <p class="text-yellow-50 text-sm">
        Platform pengaduan masyarakat Kabupaten Pemalang. Sampaikan keluhan Anda dengan mudah dan cepat.
      </p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-4 mb-6">
      <Link href="/aduan/create" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-3">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-sm mb-1">Buat Aduan</h3>
        <p class="text-xs text-gray-500">Laporkan keluhan Anda</p>
      </Link>

      <Link href="#" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-3">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-sm mb-1">Aduan Saya</h3>
        <p class="text-xs text-gray-500">Lihat status laporan</p>
      </Link>
    </div>

    <!-- My Complaints Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-20">
      <div class="px-4 py-3 border-b border-gray-200">
        <h3 class="font-bold text-gray-800">Aduan Terbaru Saya</h3>
        <p class="text-xs text-gray-500 mt-1">Pengaduan yang telah Anda kirimkan</p>
      </div>

      <div v-if="myComplaints.length === 0" class="p-8 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="text-gray-500 text-sm mb-4">Belum ada pengaduan</p>
        <Link href="/aduan/create" class="inline-block px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition">
          Buat Aduan Pertama
        </Link>
      </div>

      <div v-else class="divide-y divide-gray-200">
        <div v-for="complaint in myComplaints" :key="complaint.id" class="p-4 hover:bg-gray-50 transition">
          <div class="flex items-start justify-between mb-2">
            <h4 class="font-semibold text-gray-800 text-sm">{{ complaint.judul }}</h4>
            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
              {{ complaint.status }}
            </span>
          </div>
          <p class="text-xs text-gray-500 mb-2">{{ complaint.kategori }}</p>
          <p class="text-xs text-gray-400">{{ complaint.tanggal }}</p>
        </div>
      </div>
    </div>
  </CitizenLayout>
</template>
