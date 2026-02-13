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
      diajukan: 0,
      diverifikasi: 0,
      diproses: 0,
      selesai: 0,
      ditolak: 0,
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
          <option value="6">Selesai</option>
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
  // Mengarahkan ke route laporan.fixlaporan (FixLaporanAduan.vue)
  router.get(`/laporan-pengaduan/${aduanId}`);
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
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
      <!-- Card 1: Selesai -->
      <div
        class="bg-[#A3D95B] rounded-lg shadow border border-gray-200 text-center p-4 flex flex-col items-center justify-center transition hover:shadow-lg"
      >
        <p class="text-gray-800 text-sm font-medium mb-1">Laporan Selesai</p>
        <h3 class="text-3xl font-bold text-black">{{ stats.selesai }}</h3>
      </div>

      <!-- Card 2: Diproses -->
      <div
        class="bg-[#A7D2FF] rounded-lg shadow border border-gray-200 text-center p-4 flex flex-col items-center justify-center transition hover:shadow-lg"
      >
        <p class="text-gray-800 text-sm font-medium mb-1">Laporan Diproses</p>
        <h3 class="text-3xl font-bold text-black">{{ stats.diproses }}</h3>
      </div>

      <!-- Card 3: Diverifikasi -->
      <div
        class="bg-[#FF9F9F] rounded-lg shadow border border-gray-200 text-center p-4 flex flex-col items-center justify-center transition hover:shadow-lg"
      >
        <p class="text-gray-800 text-sm font-medium mb-1">Laporan Diverifikasi</p>
        <h3 class="text-3xl font-bold text-black">{{ stats.diverifikasi }}</h3>
      </div>

      <!-- Card 4: Diajukan -->
      <div
        class="bg-[#FFD166] rounded-lg shadow border border-gray-200 text-center p-4 flex flex-col items-center justify-center transition hover:shadow-lg"
      >
        <p class="text-gray-800 text-sm font-medium mb-1">Laporan Diajukan</p>
        <h3 class="text-3xl font-bold text-black">{{ stats.diajukan }}</h3>
      </div>

      <!-- Card 5: Ditolak -->
      <div
        class="bg-gradient-to-r from-gray-300 to-gray-100 rounded-lg shadow border border-gray-200 text-center p-4 flex flex-col items-center justify-center transition hover:shadow-lg"
      >
        <p class="text-gray-800 text-sm font-medium mb-1">Laporan Ditolak</p>
        <h3 class="text-3xl font-bold text-black">{{ stats.ditolak }}</h3>
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
                {{ complaint.nama_akses_aduan }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ complaint.kategori }}
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

                

                <button @click="viewLaporan(complaint.id)"
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
