<template>
  <div class="min-h-screen bg-gray-50">
    <component :is="userLayout" :user="user">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Pengaduan</h1>
      </div>

      <!-- Statistik Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <p class="text-gray-600 text-sm font-medium">Total Pengaduan</p>
          <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ stats.total }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <p class="text-gray-600 text-sm font-medium">Hari Ini</p>
          <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ stats.hari_ini }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <p class="text-gray-600 text-sm font-medium">Bulan Ini</p>
          <h3 class="text-3xl font-bold text-purple-600 mt-2">{{ stats.bulan_ini }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <p class="text-gray-600 text-sm font-medium">Tahun Ini</p>
          <h3 class="text-3xl font-bold text-green-600 mt-2">{{ stats.tahun_ini }}</h3>
        </div>
      </div>

      <!-- Filter & Table Section -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Filter Form -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
          <div class="md:col-span-2">
            <input
              type="date"
              v-model="dateFrom"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
            />
          </div>
          <div class="md:col-span-2">
            <input
              type="date"
              v-model="dateTo"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
            />
          </div>
          <div>
            <button
              @click="printAllLaporan"
              class="w-full px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition"
            >
              Cetak Laporan
            </button>
          </div>
        </div>

        <!-- Show Entries & Search -->
        <div class="flex justify-between items-center mb-6">
          <div class="flex items-center gap-2">
            <span class="text-gray-600 text-sm">Show</span>
            <select class="px-3 py-1 border border-gray-300 rounded text-sm">
              <option>10</option>
              <option>25</option>
              <option>50</option>
            </select>
            <span class="text-gray-600 text-sm">entries</span>
          </div>
          <div>
            <input
              type="text"
              placeholder="Search:"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400"
            />
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto mb-6">
          <table class="w-full">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">NO</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  NO ADUAN
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  TANGGAL LAPOR
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  PELAPOR
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  UNIT
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  KATEGORI
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  OPD
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  LOKASI
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">
                  STATUS
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="laporanPengaduan.length === 0" class="border-t">
                <td colspan="9" class="px-4 py-8 text-center text-gray-600">
                  No data available in table
                </td>
              </tr>
              <tr
                v-else
                v-for="(laporan, index) in laporanPengaduan"
                :key="laporan.id"
                class="border-t hover:bg-gray-50"
              >
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ index + (aduanList.from ? aduanList.from - 1 : 0) + 1 }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ laporan.no_aduan }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ formatDate(laporan.tanggal_lapor) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ laporan.nama_lengkap }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ laporan.unit }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ laporan.kategori }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ laporan.opd }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                  {{ laporan.lokasi }}
                </td>
                <td class="px-4 py-3 text-sm">
                  <span
                    :class="getStatusClass(laporan.statusAduan)"
                    class="px-3 py-1 rounded-full text-xs font-medium"
                    >{{ laporan.statusAduan }}</span
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination Info -->
        <div class="flex justify-between items-center mb-6">
          <p class="text-sm text-gray-600">
            Showing {{ aduanList.from || 0 }} to {{ aduanList.to || 0 }} of
            {{ aduanList.total || 0 }} entries
          </p>
          <div class="flex gap-2">
            <Link
              v-if="aduanList.prev_page_url"
              :href="aduanList.prev_page_url"
              class="px-4 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50"
              >Previous</Link
            >
            <Link
              v-if="aduanList.next_page_url"
              :href="aduanList.next_page_url"
              class="px-4 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50"
              >Next</Link
            >
          </div>
        </div>
      </div>
    </component>
  </div>
</template>

<script setup>
import StaffLayout from "@/Layouts/StaffLayout.vue";
import OpdLayout from "@/Layouts/OpdLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
  user: Object,
  aduanList: Object,
  laporanPengaduan: Array,
  stats: Object,
});

const dateFrom = ref("");
const dateTo = ref("");

onMounted(() => {
  const params = new URLSearchParams(window.location.search);
  dateFrom.value = params.get("dari") || "";
  dateTo.value = params.get("sampai") || "";
});

// Determine which layout to use based on user role
const userLayout = computed(() => {
  // Check if user role is OPD
  if (props.user?.nama_peran?.toLowerCase().includes("opd")) {
    return OpdLayout;
  }
  return StaffLayout;
});

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  });
};

const getStatusClass = (status) => {
  const classes = {
    Diajukan: "bg-yellow-100 text-yellow-800",
    Diverifikasi: "bg-blue-100 text-blue-800",
    Diproses: "bg-purple-100 text-purple-800",
    Selesai: "bg-green-100 text-green-800",
    Ditolak: "bg-red-100 text-red-800",
  };
  return classes[status] || "bg-gray-100 text-gray-800";
};

const filterLaporan = () => {
  const params = {};
  if (dateFrom.value) params.dari = dateFrom.value;
  if (dateTo.value) params.sampai = dateTo.value;
  router.get("/laporan/laporanaduan", params, { 
    replace: true, 
    preserveState: true, 
    preserveScroll: true 
  });
};

watch([dateFrom, dateTo], () => {
  filterLaporan();
});

const printAllLaporan = async () => {
  try {
    const form = new FormData();
    if (dateFrom.value) form.append('dari', dateFrom.value);
    if (dateTo.value) form.append('sampai', dateTo.value);
    form.append('format', 'pdf');

    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const headers = {};
    if (tokenMeta) headers['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');

    const res = await fetch('/laporan/laporanaduan/export', {
      method: 'POST',
      headers,
      body: form,
      credentials: 'same-origin',
    });

    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) {
      const j = await res.json();
      if (j.message === 'no_data') {
        Swal.fire({
          icon: 'info',
          title: 'Tidak ada data',
          text: 'Tidak ditemukan aduan pada rentang tanggal yang dipilih.',
          confirmButtonColor: '#3B82F6',
        });
        return;
      }
      if (j.message === 'missing_pdf_library') {
        Swal.fire({
          icon: 'warning',
          title: 'Fitur PDF belum tersedia',
          html: `Server belum memiliki library PDF. Jalankan: <code>composer require dompdf/dompdf</code>`,
          confirmButtonColor: '#3B82F6',
        });
        return;
      }
      // fallback
      Swal.fire({ icon: 'info', title: 'Info', text: j.message || 'Respons tidak terduga' });
      return;
    }

    // If PDF, download directly
    if (ct.includes('application/pdf')) {
      const blob = await res.blob();
      const filename = res.headers.get('content-disposition')?.split('filename=')?.[1]?.replace(/"/g, '') || `laporan_${dateFrom.value || 'all'}_${dateTo.value || 'all'}.pdf`;
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
      return;
    }

    // otherwise treat as file stream (fallback)
    const blob = await res.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `laporan_${dateFrom.value || 'all'}_${dateTo.value || 'all'}`;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Print Error:', error);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal men-download laporan.' });
  }
};
</script>
