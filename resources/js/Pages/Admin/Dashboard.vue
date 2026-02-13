<script>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import StaffLayout from '@/Layouts/StaffLayout.vue'
import Swal from "sweetalert2"

export default {
  components: {
    Head,
    StaffLayout
  },
  props: {
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
    },
    opds: {
      type: Array,
      default: () => []
    },
  },
  data() {
    return {
      searchQuery: "",
      currentPage: 1,
      perPage: 5,
      headers: ["ID", "Judul Aduan", "Kategori", "Status", "OPD", "Tanggal", "Aksi"],
      complaints: []
    }
  },
  computed: {
    filteredComplaints() {
      return this.complaints.filter(c =>
        c.judul?.toLowerCase().includes(this.searchQuery.toLowerCase())
      )
    },
    totalPages() {
      return Math.ceil(this.filteredComplaints.length / this.perPage)
    },
    paginatedComplaints() {
      const start = (this.currentPage - 1) * this.perPage
      return this.filteredComplaints.slice(start, start + this.perPage)
    }
  },
  mounted() {
    this.complaints = this.recentComplaints || []
  },
  watch: {
    recentComplaints: {
      handler(newVal) {
        // Sinkronkan data tabel ketika props dari server berubah (misal setelah reload)
        this.complaints = newVal || []
      },
      deep: true,
    }
  },
  methods: {
    getStatusColor(status) {
      const colors = {
        'Diajukan': 'bg-yellow-100 text-yellow-800',
        'Diverifikasi': 'bg-blue-100 text-blue-800',
        'Diproses': 'bg-purple-100 text-purple-800',
        'Selesai': 'bg-green-100 text-green-800',
        'Ditolak': 'bg-red-100 text-red-800',
        'Pending': 'bg-yellow-100 text-yellow-800',
        'Proses': 'bg-purple-100 text-purple-800'
      }
      return colors[status] || 'bg-gray-100 text-gray-800'
    },
    shouldShowTransfer(complaint) {
      // Tampil hanya jika ADA riwayat status id 6
      if (Array.isArray(complaint.riwayat_status_aduan)) {
        return complaint.riwayat_status.some(r => r.status_aduan_id == 6)
      }
      return true;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++
    },
    prevPage() {
      if (this.currentPage > 1) this.currentPage--
    },
    addComplaint() {
      Swal.fire("Tambah Aduan", "Form tambah aduan akan ditampilkan.", "info")
    },
    editComplaint(complaint) {
      Swal.fire("Edit Aduan", `Edit aduan: ${complaint.judul}`, "warning")
    },
    deleteComplaint(id) {
      Swal.fire({
        title: "Yakin hapus?",
        text: "Data tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!"
      }).then(result => {
        if (result.isConfirmed) {
          this.complaints = this.complaints.filter(c => c.id !== id)
          Swal.fire("Terhapus!", "Data berhasil dihapus.", "success")
        }
      })
    },
    async detailComplaint(complaint) {
      try {
        const res = await fetch(`/api/aduan/${complaint.id}/riwayat-status`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          credentials: 'same-origin',
        })

        if (!res.ok) {
          throw new Error('Gagal mengambil riwayat status')
        }

        const data = await res.json()
        const items = data.riwayat || []

        if (!items.length) {
          Swal.fire(
            'Riwayat Status',
            'Belum ada riwayat status untuk aduan ini.',
            'info'
          )
          return
        }

        const html = `
          <div class="text-left">
            <div class="mb-3 text-sm text-gray-600">
              <span class="font-semibold">No. Aduan:</span> #${complaint.id} - ${complaint.judul || ''}
            </div>
            <div class="border-t border-gray-200 pt-2 space-y-2">
              ${items
                .map(
                  (item) => `
                    <div class="py-2">
                      <div class="flex justify-between items-center text-sm">
                        <span class="font-semibold">${item.status}</span>
                        <span class="text-xs text-gray-500">${new Date(
                          item.waktu_status_aduan
                        ).toLocaleString('id-ID')}</span>
                      </div>
                      ${
                        item.catatan
                          ? `<p class="text-sm text-gray-700 mt-1">${item.catatan}</p>`
                          : ''
                      }
                      <p class="text-xs text-gray-400 mt-1">oleh ${item.petugas}</p>
                    </div>
                  `
                )
                .join('')}
            </div>
          </div>
        `

        Swal.fire({
          title: 'Riwayat Status Aduan',
          html,
          width: 600,
          confirmButtonText: 'Tutup',
        })
      } catch (e) {
        Swal.fire('Error', 'Gagal menampilkan riwayat status aduan.', 'error')
      }
    },
    async changeStatus(complaint) {
      const result = await Swal.fire({
        icon: 'question',
        title: 'Ubah Status Aduan',
        html: `<div class="text-left">
          <p class="mb-3"><strong>No. Aduan:</strong> #${complaint.id}</p>
          <p class="mb-4"><strong>Status Saat Ini:</strong> ${complaint.status || '-'}</p>
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Status Baru</label>
          <select id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            <option value="">-- Pilih Status --</option>
            <option value="2">Diverifikasi</option>
            <option value="3">Ditolak</option>
            <option value="5">Selesai</option>
          </select>
          <label class="block text-sm font-medium text-gray-700 mb-1 mt-4">Keterangan / Catatan</label>
          <textarea id="catatanInput" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Tulis keterangan perubahan status..."></textarea>
        </div>`,
        showCancelButton: true,
        confirmButtonText: 'Ubah Status',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#EF4444',
        didOpen: () => {
          const selectElement = document.getElementById('statusSelect')
          selectElement?.focus()
        },
        preConfirm: () => {
          const newStatus = document.getElementById('statusSelect')?.value
          const keterangan = document.getElementById('catatanInput')?.value || ''
          if (!newStatus) {
            Swal.showValidationMessage('Silakan pilih status terlebih dahulu')
            return false
          }
          return { newStatus, keterangan }
        }
      })

      if (!result.isConfirmed || !result.value?.newStatus) return

      try {
        await router.put(`/manajemenaduan/daftaraduan/${complaint.id}/status`, {
          status_aduan_id: result.value.newStatus,
          keterangan: result.value.keterangan,
        })

        Swal.fire({
          icon: 'success',
          title: 'Status Diperbarui!',
          text: 'Status aduan berhasil diubah.',
          confirmButtonColor: '#3B82F6',
        }).then(() => {
          // Refresh data di dashboard (stats + tabel)
          router.reload({ only: ['stats', 'recentComplaints'] })
        })
      } catch (e) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Terjadi kesalahan saat mengubah status.',
          confirmButtonColor: '#3B82F6',
        })
      }
    },
    async transferComplaint(complaint) {
      if (!this.opds || !this.opds.length) {
        Swal.fire('Info', 'Daftar OPD belum tersedia.', 'info')
        return
      }

      const optionsHtml = this.opds
        .map(opd => `<option value="${opd.id}">${opd.nama_opd}</option>`)
        .join('')

      const result = await Swal.fire({
        title: 'Transfer Aduan ke OPD',
        html: `
          <div class="text-left">
            <p class="mb-3 text-sm"><strong>No. Aduan:</strong> #${complaint.id} - ${complaint.judul || ''}</p>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih OPD</label>
            <select id="opdSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 mb-3">
              <option value="">-- Pilih OPD --</option>
              ${optionsHtml}
            </select>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
            <textarea id="catatanInput" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Catatan untuk OPD..."></textarea>
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#EF4444',
        didOpen: () => {
          document.getElementById('opdSelect')?.focus()
        },
        preConfirm: () => {
          const opdId = document.getElementById('opdSelect')?.value
          const catatan = document.getElementById('catatanInput')?.value || ''
          if (!opdId) {
            Swal.showValidationMessage('Silakan pilih OPD terlebih dahulu')
            return false
          }
          return { opdId, catatan }
        }
      })

      if (!result.isConfirmed || !result.value?.opdId) return

      try {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]')
        const csrf = tokenMeta?.getAttribute('content') || ''

        const res = await fetch(`/api/aduan/${complaint.id}/transfer`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify({
            opd_id: result.value.opdId,
            catatan: result.value.catatan,
          }),
          credentials: 'same-origin',
        })

        if (!res.ok) {
          throw new Error('Gagal menyimpan transfer')
        }

        const data = await res.json()

        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: data.message || 'Aduan berhasil ditransfer.',
          confirmButtonColor: '#3B82F6',
        }).then(() => {
          // Refresh data di dashboard
          router.reload({ only: ['stats', 'recentComplaints'] })
        })
      } catch (e) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Terjadi kesalahan saat menyimpan transfer aduan.',
          confirmButtonColor: '#3B82F6',
        })
      }
    },
    exportData() {
      const header = this.headers.join(",") + "\n"
      const rows = this.complaints.map(c =>
        [c.id, c.judul, c.kategori, c.status, c.opd, c.tanggal].join(",")
      ).join("\n")

      const csvContent = header + rows
      const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" })
      const link = document.createElement("a")
      link.href = URL.createObjectURL(blob)
      link.setAttribute("download", "complaints.csv")
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)

      Swal.fire("Export Data", "Data berhasil diexport ke CSV.", "success")
    }
  }
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
        
        <div class="flex gap-2">
        <input v-model="searchQuery" type="text" placeholder="Cari aduan..."
               class="px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-yellow-300" />
        <button @click="addComplaint"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition">
          Import
        </button>
        <button @click="exportData"
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition">
          Export
        </button>
      </div>

      </div>

      <!-- Table Wrapper with Horizontal Scroll -->
      <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
          <thead class="bg-gray-50">
            <tr>
              <th v-for="header in headers" :key="header"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ header }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="complaint in paginatedComplaints" :key="complaint.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ complaint.id }}</td>
              <td class="px-6 py-4 text-sm text-gray-900">{{ complaint.judul }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ complaint.kategori }}</td>
              <td class="px-6 py-4">
                <span :class="getStatusColor(complaint.status)"
                      class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ complaint.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ complaint.opd || '-' }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ complaint.tanggal }}</td>
              <td class="px-6 py-4 text-sm space-x-2">
                <button
                  @click="detailComplaint(complaint)"
                  class="text-blue-600 hover:text-blue-800 font-medium transition"
                >
                  Detail
                </button>
                <button
                  @click="changeStatus(complaint)"
                  class="text-purple-600 hover:text-purple-800 font-medium transition"
                >
                  Ubah Status
                </button>
                <button
                  v-if="shouldShowTransfer(complaint)"
                  @click="transferComplaint(complaint)"
                  class="text-green-600 hover:text-green-800 font-medium transition"
                >
                  Transfer
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
            <button @click="prevPage" :disabled="currentPage === 1"
                    class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
              Previous
            </button>
            <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
              Next
            </button>
          </div>

        </div>
      </div>
    </div>
  </StaffLayout>
</template>





