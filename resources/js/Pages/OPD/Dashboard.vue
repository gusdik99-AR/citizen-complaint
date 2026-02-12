<script setup>
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import OpdLayout from "@/Layouts/OpdLayout.vue";
import Swal from "sweetalert2";
import { ref } from "vue";

defineProps({
  user: Object,
  stats: {
    type: Object,
    default: () => ({
      totalAduan: 0,
      diajukan: 0,
      diproses: 0,
      selesai: 0,
    }),
  },
  assignedComplaints: {
    type: Array,
    default: () => [],
  },
});

const getStatusColor = (status) => {
  const colors = {
    Diajukan: "bg-yellow-100 text-yellow-800",
    Diverifikasi: "bg-blue-100 text-blue-800",
    Diproses: "bg-purple-100 text-purple-800",
    Selesai: "bg-green-100 text-green-800",
    Ditolak: "bg-red-100 text-red-800",
  };
  return colors[status] || "bg-gray-100 text-gray-800";
};

const wizard = (aduanId) => {
  router.get(`/opd/wizard/${aduanId}`);
};

const changeStatus = async (aduanId) => {
  const result = await Swal.fire({
    icon: "question",
    title: "Ubah Status Aduan",
    html: `
      <div style="text-align: left;">
        <label style="display: block; margin: 10px 0; font-weight: bold;">Pilih Status Baru:</label>
        <select id="statusSelect" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
          <option value="">-- Pilih Status --</option>
          <option value="1">Diajukan</option>
          <option value="2">Diverifikasi</option>
          <option value="3">Diproses</option>
          <option value="4">Selesai</option>
          <option value="5">Ditolak</option>
        </select>
        <textarea id="keteranganInput" placeholder="Keterangan (opsional)" style="width: 100%; padding: 8px; margin-top: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px; font-family: Arial;"></textarea>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: "Simpan",
    cancelButtonText: "Batal",
    confirmButtonColor: "#3B82F6",
    preConfirm: () => {
      const status = document.getElementById("statusSelect").value;
      const keterangan = document.getElementById("keteranganInput").value;
      if (!status) {
        Swal.showValidationMessage("Pilih status terlebih dahulu!");
        return false;
      }
      return { status, keterangan };
    }
  });

  if (!result.isConfirmed) return;

  try {
    await router.put(`/manajemenaduan/daftaraduan/${aduanId}/status`, {
      status_aduan_id: result.value.status,
      keterangan: result.value.keterangan || "Status diubah oleh OPD",
    });

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Status aduan berhasil diubah.",
      confirmButtonColor: "#3B82F6",
    });
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal!",
      text: "Terjadi kesalahan saat mengubah status aduan.",
      confirmButtonColor: "#3B82F6",
    });
  }
};

const finishComplaint = async (aduanId) => {
  const result = await Swal.fire({
    icon: "question",
    title: "Tandai Selesai",
    text: "Apakah Anda yakin mengubah status aduan menjadi Selesai?",
    showCancelButton: true,
    confirmButtonText: "Ya, Selesai",
    cancelButtonText: "Batal",
    confirmButtonColor: "#10B981",
  });

  if (!result.isConfirmed) return;

  try {
    await router.put(`/manajemenaduan/daftaraduan/${aduanId}/status`, {
      status_aduan_id: 4,
      keterangan: "Selesai oleh OPD",
    });

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Status aduan berhasil diubah menjadi Selesai.",
      confirmButtonColor: "#3B82F6",
    });
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal!",
      text: "Terjadi kesalahan saat mengubah status aduan.",
      confirmButtonColor: "#3B82F6",
    });
  }
};

const viewLaporan = (aduanId) => {
  router.get(`/laporan-pengaduan?aduan=${aduanId}`);
};
</script>

<template>
  <Head title="Dashboard OPD" />

  <OpdLayout :user="user">
    <!-- Page Header -->
    <div class="mb-8">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard OPD</h2>
      <p class="text-sm text-gray-500 mt-1">
        Kelola pengaduan yang ditugaskan ke OPD Anda
      </p>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <!-- Card 1: Total Assigned -->
      <div
        class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-blue-100 text-sm font-medium">Total Ditugaskan</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.totalAduan }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
              />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 2: Diajukan -->
      <div
        class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-yellow-100 text-sm font-medium">Diajukan</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diajukan }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 3: Diproses -->
      <div
        class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-purple-100 text-sm font-medium">Sedang Diproses</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.diproses }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
              />
            </svg>
          </div>
        </div>
      </div>

      <!-- Card 4: Selesai -->
      <div
        class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-green-100 text-sm font-medium">Selesai</p>
            <h3 class="text-3xl font-bold mt-2">{{ stats.selesai }}</h3>
          </div>
          <div class="bg-white bg-opacity-20 p-3 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Assigned Complaints Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Pengaduan Ditugaskan</h3>
        <p class="text-sm text-gray-500 mt-1">
          Daftar pengaduan yang perlu ditindaklanjuti
        </p>
      </div>

      <!-- Table Wrapper with Horizontal Scroll -->
      <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Pelapor
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Deskripsi Aduan
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Lokasi
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Jenis 
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Kategori
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Status
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Tanggal
              </th>
              <th
                style="width: 120px;"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(complaint, index) in assignedComplaints"
              :key="complaint.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ index + 1 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ complaint.pelapor }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ complaint.judul }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ complaint.lokasi }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ complaint.jenis }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ complaint.nama_kategori }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="getStatusColor(complaint.status)"
                  class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ complaint.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ complaint.tanggal }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <div class="flex space-x-2">

                <button
                  @click="wizard(complaint.id)"
                  class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition flex items-center justify-center"
                >
                  <!-- Ikon Proses -->
                  <svg xmlns="http://www.w3.org/2000/svg" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke-width="1.5" 
                      stroke="currentColor" 
                      class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M12 6v6h6m2 0a8 8 0 11-16 0 8 8 0 0116 0z" />
                  </svg>
                </button>

                <button
                  @click="changeStatus(complaint.id)"
                  class="px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white text-xs font-medium rounded transition flex items-center justify-center"
                >
                  <!-- Ikon Rubah Status -->
                  <svg xmlns="http://www.w3.org/2000/svg" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke-width="1.5" 
                      stroke="currentColor" 
                      class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M4.5 12a7.5 7.5 0 1112.9 5.3l2.1 2.1m-2.1-2.1V18m0 0h-2.25" />
                  </svg>
                </button>

                <button
                  @click="finishComplaint(complaint.id)"
                  class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded transition flex items-center justify-center"
                >
                  <!-- Ikon Selesai -->
                  <svg xmlns="http://www.w3.org/2000/svg" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke-width="1.5" 
                      stroke="currentColor" 
                      class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M9 12.75l2.25 2.25L15 9.75m6.75 2.25a9.75 9.75 0 11-19.5 0 9.75 9.75 0 0119.5 0z" />
                  </svg>
                </button>

                <button
                  @click="viewLaporan(complaint.id)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition flex items-center justify-center"
                >
                  <!-- Ikon Cetak Laporan -->
                  <svg xmlns="http://www.w3.org/2000/svg" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke-width="1.5" 
                      stroke="currentColor" 
                      class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M6 9V3h12v6M6 18h12v3H6v-3zm0-9h12v6H6v-6z" />
                  </svg>
                </button>
                </div>


                <!-- Mulai -->
                
                <!-- Selesai -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </OpdLayout>
</template>
