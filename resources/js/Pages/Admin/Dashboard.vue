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
      diverifikasi: 0,
      diproses: 0,
      selesai: 0,
      ditolak: 0,
      totalUsers: 0,
      totalOPD: 0
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
  <Head title="Dashboard Admin" />

  <StaffLayout :user="user">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-start justify-between">
        <div>
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Admin</h2>
          <p class="text-sm text-gray-500 mt-1">Kelola sistem pengaduan masyarakat secara menyeluruh</p>
        </div>
        <div class="bg-blue-500 text-white px-6 py-3 rounded-xl shadow-lg">
          <p class="text-blue-100 text-xs font-medium">Total Pengaduan</p>
          <h3 class="text-2xl font-bold mt-1">{{ stats.totalAduan }}</h3>
        </div>
      </div>
    </div>

    <!-- Statistics Cards Grid: 5 Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <!-- Card 1: Diajukan -->
      <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-yellow-100 text-sm font-medium">Diajukan</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diajukan }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 2: Diverifikasi -->
      <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-blue-100 text-sm font-medium">Diverifikasi</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diverifikasi }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 3: Ditolak -->
      <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-red-100 text-sm font-medium">Ditolak</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.ditolak }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 4: Diproses -->
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-purple-100 text-sm font-medium">Diproses</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diproses }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 5: Selesai -->
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-green-100 text-sm font-medium">Selesai</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.selesai }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ stats.totalUsers }}</h3>
          </div>
          <div class="bg-blue-100 p-4 rounded-lg">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total OPD</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ stats.totalOPD }}</h3>
          </div>
          <div class="bg-purple-100 p-4 rounded-lg">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Complaints Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
          <h3 class="text-lg font-bold text-gray-800">Semua Pengaduan</h3>
          <p class="text-sm text-gray-500 mt-1">Kelola dan tugaskan pengaduan ke OPD terkait</p>
        </div>
        <button class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition">
          Tambah Kategori
        </button>
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
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OPD</th>
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
                {{ complaint.opd || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ complaint.tanggal }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button class="text-blue-600 hover:text-blue-800 font-medium transition">
                  Detail
                </button>
                <button class="text-purple-600 hover:text-purple-800 font-medium transition">
                  Tugaskan
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
