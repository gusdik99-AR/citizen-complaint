<script setup>
import { ref, computed, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
  aduan: Object,
  riwayatStatus: Array,
  tanggapan: Array,
  isOwner: Boolean,
  isGuest: Boolean,
  fotos: { type: Array, default: () => [] },
})

const imageList = computed(() =>
  props.fotos.length > 0 ? props.fotos : props.aduan.foto_url ? [props.aduan.foto_url] : []
)
const activeImage = ref(imageList.value[0])

watch(
  () => props.aduan,
  () => {
    activeImage.value = imageList.value[0]
  }
)

const voteCount = ref(props.aduan.jumlah_vote || 0)
const isVoting = ref(false)

const handleVote = async () => {
  if (isVoting.value) return

  isVoting.value = true

  try {
    const response = await fetch(`/aduan/${props.aduan.id}/vote`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN':
          document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
    })

    const data = await response.json()

    if (data.success) {
      voteCount.value = data.jumlah_vote
    }
  } catch (error) {
    console.error('Error voting:', error)
  } finally {
    isVoting.value = false
  }
}

const formatDate = dateString => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const maskName = name => {
  if (!name) return 'Anonim'
  const words = name.trim().split(' ')
  if (words.length === 1) {
    // Single name: show first 2 chars + ***
    return words[0].substring(0, 2) + '***'
  }
  // Multiple names: show first name + initial of last name
  return words[0] + ' ' + words[words.length - 1].charAt(0) + '***'
}

const getStatusColor = status => {
  const colors = {
    Selesai: 'bg-green-500',
    Diproses: 'bg-blue-500',
    Diverifikasi: 'bg-pink-500',
    Diajukan: 'bg-orange-500',
    Ditolak: 'bg-red-500',
  }
  return colors[status] || 'bg-gray-500'
}
</script>

<template>
  <Head :title="`Detail Aduan - ${aduan.no_aduan}`" />

  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center relative">
          <Link href="/" class="absolute left-0 text-gray-700 hover:text-blue-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 19l-7-7m0 0l7-7m-7 7h18"
              />
            </svg>
          </Link>

          <h1 class="text-lg font-bold text-gray-900">Detail Aduan</h1>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Complaint Card -->
          <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Image -->
            <div v-if="imageList.length > 0" class="relative bg-gray-200">
              <div class="h-64 sm:h-96">
                <img :src="activeImage" :alt="aduan.no_aduan" class="w-full h-full object-cover" />
              </div>

              <!-- Thumbnails -->
              <div
                v-if="imageList.length > 1"
                class="flex gap-2 p-2 overflow-x-auto bg-white/10 backdrop-blur-sm absolute bottom-0 w-full"
              >
                <button
                  v-for="(img, idx) in imageList"
                  :key="idx"
                  @click="activeImage = img"
                  :class="[
                    'flex-shrink-0 w-16 h-16 rounded-md overflow-hidden border-2 transition',
                    activeImage === img
                      ? 'border-blue-500 ring-2 ring-blue-300'
                      : 'border-white opacity-70 hover:opacity-100',
                  ]"
                >
                  <img :src="img" class="w-full h-full object-cover" />
                </button>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-4">
              <!-- Header -->
              <div class="flex items-start justify-between">
                <div>
                  <h2 class="text-2xl font-bold text-gray-900">{{ aduan.no_aduan }}</h2>
                  <p class="text-sm text-gray-500 mt-1">
                    Dilaporkan {{ formatDate(aduan.tanggal_lapor) }}
                  </p>
                </div>
                <span
                  :class="[
                    getStatusColor(aduan.status),
                    'px-4 py-2 rounded-full text-sm font-semibold text-white',
                  ]"
                >
                  {{ aduan.status }}
                </span>
              </div>

              <!-- Vote Section -->
              <div
                class="flex items-center justify-between py-3 px-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100"
              >
                <div class="flex items-center space-x-3 text-gray-700">
                  <div class="p-2 bg-white rounded-full shadow-sm">
                    <svg
                      class="w-5 h-5 text-blue-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
                      />
                    </svg>
                  </div>
                  <div>
                    <p class="font-bold text-lg text-gray-900">{{ voteCount }}</p>
                    <p class="text-sm text-gray-600">dukungan</p>
                  </div>
                </div>
                <button
                  @click="handleVote"
                  :disabled="isVoting"
                  class="px-5 py-2.5 bg-white border-2 border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white disabled:bg-gray-100 disabled:border-gray-300 disabled:text-gray-400 rounded-lg transition-all font-semibold text-sm shadow-sm"
                >
                  {{ isVoting ? 'Memproses...' : 'Dukung Aduan Ini' }}
                </button>
              </div>

              <!-- Category & Access -->
              <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                  {{ aduan.kategori }}
                </span>
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                  {{ aduan.akses }}
                </span>
              </div>

              <!-- Description -->
              <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi Aduan</h3>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                  {{ aduan.isi_aduan }}
                </p>
              </div>

              <!-- Location -->
              <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Lokasi</h3>
                <a
                  :href="`https://www.google.com/maps?q=${aduan.latitude},${aduan.longitude}`"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex items-start space-x-2 text-blue-600 hover:text-blue-800 transition"
                >
                  <svg
                    class="w-5 h-5 mt-0.5 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                  <div>
                    <p class="underline">{{ aduan.lokasi }}</p>
                    <p class="text-sm text-gray-500">{{ aduan.latitude }}, {{ aduan.longitude }}</p>
                    <p class="text-xs text-gray-400 mt-1">Klik untuk buka di Google Maps</p>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <!-- Responses from OPD -->
          <div v-if="tanggapan.length > 0" class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tanggapan</h3>
            <div class="space-y-4">
              <div
                v-for="item in tanggapan"
                :key="item.id"
                class="border-l-4 border-blue-500 pl-4 py-2"
              >
                <div class="flex items-center justify-between mb-2">
                  <span class="font-semibold text-gray-900">{{ item.petugas }}</span>
                  <span class="text-sm text-gray-500">{{ formatDate(item.tanggal_dibuat) }}</span>
                </div>
                <p class="text-gray-700">{{ item.catatan }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Timeline -->
        <div class="space-y-6">
          <!-- Status History -->
          <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Status</h3>
            <div class="space-y-4">
              <div v-for="(item, index) in riwayatStatus" :key="item.id" class="relative">
                <!-- Timeline dot -->
                <div class="flex items-start">
                  <div class="flex-shrink-0 relative">
                    <div :class="[getStatusColor(item.status), 'w-3 h-3 rounded-full']"></div>
                    <div
                      v-if="index < riwayatStatus.length - 1"
                      class="absolute top-3 left-1.5 w-0.5 h-full bg-gray-300"
                    ></div>
                  </div>

                  <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold text-gray-900">{{ item.status }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                      {{ formatDate(item.waktu_status_aduan) }}
                    </p>
                    <p v-if="item.catatan" class="text-sm text-gray-600 mt-1">{{ item.catatan }}</p>
                    <p class="text-xs text-gray-400 mt-1">oleh {{ item.petugas }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Info Card -->
          <div class="bg-blue-50 rounded-xl p-6">
            <div class="flex items-start space-x-3">
              <svg
                class="w-6 h-6 text-blue-600 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div>
                <h4 class="font-semibold text-blue-900 mb-2">Informasi</h4>
                <p class="text-sm text-blue-800">
                  Aduan Anda sedang dalam proses penanganan. Kami akan memberikan update status
                  secara berkala.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Additional styles if needed */
</style>
