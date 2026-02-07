<script setup>
import { Head } from '@inertiajs/vue3'
import StaffLayout from '@/Layouts/StaffLayout.vue'

defineProps({
  user: Object,
  stats: {
    type: Object,
    default: () => ({
      totalAduan: 0,
      diajukan: 0,
      diproses: 0,
      selesai: 0,
      ditolak: 0
    })
  },
  recentComplaints: {
    type: Array,
    default: () => []
  }
})

const getStatusColor = (status) => {
  const colors = {
    'Diajukan': 'bg-yellow-100 text-yellow-800',
    'Diverifikasi': 'bg-blue-100 text-blue-800',
    'Diproses': 'bg-purple-100 text-purple-800',
    'Selesai': 'bg-green-100 text-green-800',
    'Ditolak': 'bg-red-100 text-red-800'
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <Head title="Dashboard" />

  <StaffLayout :user="user">
    <!-- Page Header -->
    <div class="mb-8">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard</h2>
      <p class="text-sm text-gray-500 mt-1">Ringkasan data pengaduan masyarakat</p>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <!-- Card 1: Total Aduan -->
      <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-blue-100 text-sm font-medium">Total Aduan</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.totalAduan }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 2: Diajukan -->
      <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-yellow-100 text-sm font-medium">Diajukan</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diajukan }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 3: Diproses -->
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-purple-100 text-sm font-medium">Diproses</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diproses }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 4: Selesai -->
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-green-100 text-sm font-medium">Selesai</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.selesai }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 5: Ditolak -->
      <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-red-100 text-sm font-medium">Ditolak</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.ditolak }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Complaints Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Pengaduan Terbaru</h3>
        <p class="text-sm text-gray-500 mt-1">Daftar pengaduan yang masuk hari ini</p>
      </div>

      <!-- Table Wrapper with Horizontal Scroll -->
      <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Aduan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="complaint in recentComplaints" :key="complaint.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ complaint.id }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ complaint.judul }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ complaint.kategori }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusColor(complaint.status)" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ complaint.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ complaint.tanggal }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <button class="text-blue-600 hover:text-blue-800 font-medium transition">
                  Detail
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Table Footer -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <p class="text-sm text-gray-500">
            Menampilkan <span class="font-medium">{{ recentComplaints.length }}</span> dari 
            <span class="font-medium">{{ stats.totalAduan }}</span> pengaduan
          </p>
          <div class="flex gap-2">
            <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
              Previous
            </button>
            <button class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  </StaffLayout>
</template>

