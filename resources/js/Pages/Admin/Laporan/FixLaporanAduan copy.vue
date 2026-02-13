<template>
  <div class="min-h-screen bg-gray-100">
    <OpdLayout :user="user">
      <!-- Header dengan Statistik -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Laporan Pengaduan</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Total Pengaduan</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ stats.total }}</h3>
          </div>
          <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Hari Ini</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ stats.hari_ini }}</h3>
          </div>
          <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Bulan Ini</p>
            <h3 class="text-2xl font-bold text-purple-600">{{ stats.bulan_ini }}</h3>
          </div>
          <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Tahun Ini</p>
            <h3 class="text-2xl font-bold text-green-600">{{ stats.tahun_ini }}</h3>
          </div>
        </div>
      </div>

      <!-- Daftar Laporan Pengaduan dengan Format Resmi -->
      <div class="space-y-6">
        <div
          v-for="laporan in laporanPengaduan"
          :key="laporan.id"
          class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-blue-600"
        >
          <!-- Header Laporan -->
          <div class="mb-8 pb-6 border-b-2 border-gray-300">
            <div class="flex justify-between items-start mb-6">
              <!-- Judul dan Nomor -->
              <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">E-LAPORAN</h2>
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                  PEMERINTAH KABUPATEN PAMALANG
                </h2>

                <h3 class="text-lg font-bold text-gray-800 mt-4">LAPORAN PENGADUAN</h3>
              </div>
              <!-- Logo Pamalang -->
              <div class="text-center">
                <img
                  src="/logo.png"
                  alt="Logo Pamalang"
                  class="w-20 h-20 object-contain"
                />
                <p class="text-xs text-gray-600 mt-2">{{ laporan.nomor_laporan }}</p>
              </div>
            </div>
            <div class="border-t-2 border-b-2 border-gray-400 py-2">
              <p class="text-sm text-gray-700">
                Nomor laporan : {{ laporan.nomor_laporan }}
              </p>
            </div>
          </div>

          <!-- Informasi Pelapor -->
          <div class="mb-8">
            <h4 class="text-lg font-bold text-gray-800 mb-4">IDENTITAS PELAPOR</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Nama Lengkap</span>
                <span class="text-gray-800">: {{ laporan.nama_lengkap }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Alamat</span>
                <span class="text-gray-800">: {{ laporan.alamat }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Nomor Telepon</span>
                <span class="text-gray-800">: {{ laporan.nomor_telepon }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Nomor Identitas</span>
                <span class="text-gray-800">: {{ laporan.nomor_identitas }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Status</span>
                <span class="text-gray-800">: {{ laporan.status }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Email</span>
                <span class="text-gray-800">: {{ laporan.email }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Sumber Pengaduan</span>
                <span class="text-gray-800">: {{ laporan.sumber_pengaduan }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Tanggal Laporan</span>
                <span class="text-gray-800"
                  >: {{ formatDate(laporan.tanggal_lapor) }}</span
                >
              </div>
            </div>
          </div>

          <!-- Detail Pengaduan -->
          <div class="mb-8">
            <h4 class="text-lg font-bold text-gray-800 mb-4">DETAIL PENGADUAN</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Perihal</span>
                <span class="text-gray-800 flex-1 ml-4">: {{ laporan.perihal }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Uraian</span>
                <span class="text-gray-800 flex-1 ml-4">: {{ laporan.uraian }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Kategori</span>
                <span class="text-gray-800">: {{ laporan.kategori }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Tempat Kejadian</span>
                <span class="text-gray-800">: {{ laporan.tempat_kejadian }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Tanggal Kejadian</span>
                <span class="text-gray-800"
                  >: {{ formatDate(laporan.tanggal_kejadian) }}</span
                >
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Waktu Kejadian</span>
                <span class="text-gray-800">: {{ laporan.waktu_kejadian }}</span>
              </div>
              <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700 w-40">Status Pengaduan</span>
                <span class="text-gray-800 font-semibold"
                  >: {{ laporan.status_aduan }}</span
                >
              </div>
            </div>
          </div>

          <!-- Petunjuk Akses Status
          <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <h4 class="text-base font-bold text-gray-800 mb-3">PETUNJUK :</h4>
            <p class="text-sm text-gray-700 mb-3">
              Anda dapat mengetahui status terkakhir proses pengaduan yang anda laporkan
              melalui :
            </p>
            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
              <li>
                Menggunakan Aplikasi SIWAS
                <ul class="list-disc list-inside ml-4 text-xs">
                  <li>
                    Gunakan username dan password yang telah anda daftarkan sebagai
                    pengguna aplikasi SIWAS untuk melakukan Login
                  </li>
                  <li>
                    Buka/klik Sub Menu "Pengaduan yang anda laporkan" pada menu
                    Whistleblower
                  </li>
                  <li>
                    Pada halaman tersebut akan terlihat status terkakhir dari pengaduan
                    anda
                  </li>
                </ul>
              </li>
              <li>
                Menggunakan Smartphone yang dilengkapi QR Code Scanner
                <ul class="list-disc list-inside ml-4 text-xs">
                  <li>
                    Smartphone yang anda gunakan harus dilengkapi dengan QR Code Scanner
                  </li>
                  <li>
                    Aktifkan QR Code Scanner pada smartphone anda dan arahkan ke QR code
                    di atas
                  </li>
                  <li>
                    Arahkan scanner smartphone anda pada hasil cetakan akan membuka
                    aplikasi pada halaman hasil cetakan akan membuka aplikasi pada halaman
                    status pengaduan anda melalui web browser yang terdapat di smartphone
                    anda
                  </li>
                </ul>
              </li>
            </ol>
            <p class="text-xs text-red-600 font-semibold mt-4">
              Tidak tersedia identitas dibuka ke Terlapor!
            </p>
          </div> -->

          <!-- Tombol Export -->
          <div class="flex gap-3 pt-6 border-t">
            <Link
              :href="route('laporan.fixlaporan.show', laporan.id)"
              class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded transition flex items-center gap-2"
            >
              <span>👁️</span> Lihat Detail
            </Link>
            <button
              @click="exportToPdf(laporan)"
              class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded transition flex items-center gap-2"
            >
              <span>📥</span> Unduh PDF
            </button>
            <button
              @click="printLaporan(laporan)"
              class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded transition flex items-center gap-2"
            >
              <span>🖨️</span> Cetak
            </button>
          </div>

          <!-- Hidden Print Template -->
          <div :id="`print-${laporan.id}`" class="hidden">
            <div
              style="
                font-family: Arial, sans-serif;
                padding: 40px;
                color: #333;
                line-height: 1.6;
                font-size: 12pt;
                page-break-after: always;
              "
            >
              <!-- Header with Logo -->
              <div style="margin-bottom: 25px">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 15px;
                  "
                >
                  <div>
                    <div style="font-size: 14pt; font-weight: bold; margin-bottom: 3px">
                      E-LAPORAN
                    </div>
                    <div style="font-size: 14pt; font-weight: bold; margin-bottom: 12px">
                      PEMERINTAH KABUPATEN PAMALANG
                    </div>
                    <div style="font-size: 14pt; font-weight: bold">
                      LAPORAN PENGADUAN
                    </div>
                  </div>
                  <div style="text-align: center">
                    <div
                      style="
                        width: 70px;
                        height: 70px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-bottom: 5px;
                      "
                    >
                      <img
                        src="/logo.png"
                        alt="Logo"
                        style="max-width: 100%; max-height: 100%; object-fit: contain"
                      />
                    </div>
                    <div style="font-size: 10px; color: #666">
                      {{ laporan.nomor_laporan }}
                    </div>
                  </div>
                </div>
                <div
                  style="
                    border-top: 1px solid #ddd;
                    border-bottom: 1px solid #ddd;
                    padding: 10px 0;
                    font-size: 12pt;
                  "
                >
                  Nomor laporan : {{ laporan.nomor_laporan }}
                </div>
              </div>

              <!-- Identitas Pelapor -->
              <div style="margin-bottom: 20px">
                <div
                  style="
                    font-weight: bold;
                    margin-bottom: 10px;
                    border-bottom: 2px solid #000;
                    padding-bottom: 6px;
                    font-size: 14pt;
                  "
                >
                  IDENTITAS PELAPOR
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 12pt">
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0; width: 25%">Nama Lengkap</td>
                    <td style="padding: 5px 0">: {{ laporan.nama_lengkap }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Alamat</td>
                    <td style="padding: 5px 0">: {{ laporan.alamat }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Nomor Telepon</td>
                    <td style="padding: 5px 0">: {{ laporan.nomor_telepon }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Nomor Identitas</td>
                    <td style="padding: 5px 0">: {{ laporan.nomor_identitas }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Status</td>
                    <td style="padding: 5px 0">: {{ laporan.status }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Email</td>
                    <td style="padding: 5px 0">: {{ laporan.email }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Sumber Pengaduan</td>
                    <td style="padding: 5px 0">: {{ laporan.sumber_pengaduan }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 5px 0">Tanggal Laporan</td>
                    <td style="padding: 5px 0">
                      : {{ formatDate(laporan.tanggal_lapor) }}
                    </td>
                  </tr>
                </table>
              </div>

              <!-- Detail Pengaduan -->
              <div style="margin-bottom: 20px">
                <div
                  style="
                    font-weight: bold;
                    margin-bottom: 10px;
                    border-bottom: 2px solid #000;
                    padding-bottom: 6px;
                    font-size: 14pt;
                  "
                >
                  DETAIL PENGADUAN
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 12pt">
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0; width: 25%">Perihal</td>
                    <td style="padding: 5px 0">: {{ laporan.perihal }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0; vertical-align: top">Uraian</td>
                    <td style="padding: 5px 0; word-wrap: break-word">
                      : {{ laporan.uraian }}
                    </td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Kategori</td>
                    <td style="padding: 5px 0">: {{ laporan.kategori }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Tempat Kejadian</td>
                    <td style="padding: 5px 0">: {{ laporan.tempat_kejadian }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Tanggal Kejadian</td>
                    <td style="padding: 5px 0">
                      : {{ formatDate(laporan.tanggal_kejadian) }}
                    </td>
                  </tr>
                  <tr style="border-bottom: 1px solid #ddd">
                    <td style="padding: 5px 0">Waktu Kejadian</td>
                    <td style="padding: 5px 0">: {{ laporan.waktu_kejadian }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 5px 0">Status Pengaduan</td>
                    <td style="padding: 5px 0; font-weight: bold">
                      : {{ laporan.status_aduan }}
                    </td>
                  </tr>
                </table>
              </div>

              <!-- Page number footer -->
              <div
                style="
                  margin-top: 50px;
                  text-align: center;
                  font-size: 12pt;
                  color: #666;
                  border-top: 1px solid #ddd;
                  padding-top: 18px;
                  position: relative;
                  min-height: 20px;
                "
              ></div>
            </div>
          </div>
        </div>
      </div>
    </OpdLayout>
  </div>
</template>

<script setup>
import OpdLayout from "@/Layouts/OpdLayout.vue";
import { Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
  user: Object,
  stats: Object,
  laporanPengaduan: Array,
});

const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const printLaporan = (laporan) => {
  try {
    const printElement = document.getElementById(`print-${laporan.id}`);
    if (!printElement) {
      throw new Error("Print template not found");
    }
    const printWindow = window.open("", "", "width=900,height=700");
    const styleContent =
      "@page { size: A4; margin: 15mm 15mm 20mm 15mm; } body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; margin: 0; padding: 0; color: #333; }";
    const html =
      '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Laporan ' +
      laporan.nomor_laporan +
      "</title><style>" +
      styleContent +
      "</style></head><body>" +
      printElement.innerHTML +
      "</body></html>";
    printWindow.document.write(html);
    printWindow.document.close();
    setTimeout(() => {
      printWindow.focus();
      printWindow.print();
      setTimeout(() => printWindow.close(), 500);
    }, 300);
  } catch (error) {
    console.error("Print Error:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Gagal membuka dialog cetak. Silakan coba lagi.",
      confirmButtonColor: "#EF4444",
    });
  }
};
</script>

<style scoped>
@media print {
  .min-h-screen {
    background: white !important;
  }
  .shadow-lg,
  .shadow,
  .rounded-lg,
  .border-t-4 {
    box-shadow: none !important;
    border: none !important;
  }
  .flex,
  .grid,
  .bg-white {
    display: block !important;
  }
  img,
  svg {
    max-width: 100%;
  }
}
</style>
