<template>
  <div class="min-h-screen bg-gray-50">
    <OPDLayout :user="props.user">
      <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
          Wizard Manajemen Aduan
        </h2>
        <p class="text-sm text-gray-500 mt-1">
          Proses penanganan aduan secara terstruktur dan detail
        </p>
      </div>

      <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <!-- Stepper -->
        <div class="flex space-x-4 mb-6">
          <div v-for="(step, idx) in steps" :key="idx" class="flex-1 text-center">
            <div
              :class="[
                'mx-auto w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2',
                currentStep === idx
                  ? 'bg-blue-500 text-white'
                  : 'bg-gray-200 text-gray-600',
              ]"
            >
              {{ idx + 1 }}
            </div>
            <div class="text-xs text-gray-600">{{ step.title }}</div>
          </div>
        </div>

        <!-- Step Content -->
        <div class="space-y-6">
          <!-- Step 0: Dokumentasi -->
          <div v-if="currentStep === 0">
            <h3 class="font-semibold mb-2">Dokumen Hasil Laporan</h3>
            <div class="text-sm text-gray-700 mb-4">
              Nomor: {{ sampleData.no_aduan }} • Tanggal: {{ sampleData.tanggal_lapor }}
            </div>

            <!-- Data Pelapor -->
            <div
              class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-3 bg-gray-50 rounded-lg"
            >
              <div>
                <p class="text-xs text-gray-600">Nama Pelapor</p>
                <p class="text-sm font-medium">{{ sampleData.nama_pelapor }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-600">Email</p>
                <p class="text-sm font-medium break-all">{{ sampleData.email }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-600">No. Telepon</p>
                <p class="text-sm font-medium">{{ sampleData.no_telepon }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-600">Kategori Aduan</p>
                <p class="text-sm font-medium">{{ sampleData.kategori }}</p>
              </div>
            </div>

            <!-- Lokasi -->
            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
              <p class="text-xs text-gray-600 mb-2">Lokasi Aduan</p>

              <!-- Lokasi Text -->
              <div class="text-sm font-medium mb-2">
                <p v-if="sampleData.lokasi">{{ sampleData.lokasi }}</p>
                <p v-else class="text-gray-500 text-xs">Alamat tidak tersedia</p>
              </div>

              <!-- Koordinat -->
              <div v-if="sampleData.latitude && sampleData.longitude" class="mb-3">
                <p class="text-xs text-gray-600 mb-1">
                  Koordinat:
                  <span class="font-mono font-semibold"
                    >{{ sampleData.latitude.toFixed(6) }},
                    {{ sampleData.longitude.toFixed(6) }}</span
                  >
                </p>
              </div>

              <!-- Map Embed -->
              <div
                v-if="sampleData.latitude && sampleData.longitude"
                class="rounded-lg overflow-hidden border border-gray-300"
              >
                <iframe
                  :src="getMapEmbedUrl(sampleData.latitude, sampleData.longitude)"
                  width="100%"
                  height="250"
                  style="border: 0"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
              </div>

              <!-- No Location Message -->
              <div
                v-if="!sampleData.latitude && !sampleData.longitude && !sampleData.lokasi"
                class="text-gray-500 text-sm"
              >
                Lokasi tidak tersedia
              </div>
            </div>

            <!-- Isi Aduan -->
            <div class="mb-4">
              <p class="text-xs text-gray-600 font-medium mb-1">Isi Aduan</p>
              <p class="text-sm text-gray-700 p-3 bg-gray-50 rounded-lg">
                {{ sampleData.isi_aduan }}
              </p>
            </div>

            <!-- Foto/Dokumentasi -->
            <div>
              <p class="font-medium mb-2">Dokumentasi / Foto</p>
              <div class="flex flex-wrap gap-3">
                <div
                  v-for="(f, i) in visibleFotos"
                  :key="i"
                  @click="selectedImage = getFotoUrl(f)"
                  class="w-32 h-24 overflow-hidden rounded-md bg-gray-100 border border-gray-200 cursor-pointer hover:shadow-lg hover:scale-105 transition transform"
                >
                  <img
                    :src="getFotoUrl(f)"
                    class="w-full h-full object-cover"
                    @error="
                      (e) =>
                        (e.target.src =
                          'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2214%22 fill=%22%239ca3af%22%3ENo Image%3C/text%3E%3C/svg%3E')
                    "
                  />
                </div>
                <div
                  v-if="visibleFotos.length === 0"
                  class="text-gray-500 text-sm"
                >
                  Tidak ada foto
                </div>
              </div>
            </div>

            <!-- Image Modal -->
            <div
              v-if="selectedImage"
              @click="selectedImage = null"
              class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
            >
              <div
                class="bg-white rounded-lg shadow-2xl max-w-2xl max-h-96 flex flex-col"
                @click.stop
              >
                <div class="flex justify-between items-center p-4 border-b">
                  <h3 class="font-semibold">Lihat Foto</h3>
                  <button
                    @click="selectedImage = null"
                    class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
                  >
                    ×
                  </button>
                </div>
                <div
                  class="flex-1 flex items-center justify-center overflow-auto bg-gray-50"
                >
                  <img
                    :src="selectedImage"
                    class="max-w-full max-h-full object-contain"
                  />
                </div>
                <div class="p-4 border-t text-center">
                  <button
                    @click="selectedImage = null"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm"
                  >
                    Tutup
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 1: Penugasan -->
          <div v-if="currentStep === 1">
            <h3 class="font-semibold mb-2">Verifikasi & Penugasan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Unit Kerja</label
                >
                <select
                  v-model="assignmentForm.unit_kerja"
                  class="w-full rounded-lg border p-2"
                >
                  <option value="">Pilih unit</option>
                  <option v-for="u in props.units" :key="u.id" :value="u.id">
                    {{ u.nama_unit }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PIC</label>
                <select v-model="assignmentForm.pic" class="w-full rounded-lg border p-2">
                  <option value="">Pilih PIC</option>
                  <option
                    v-for="p in picOptions"
                    :key="p.id"
                    :value="p.id || p.nama_pengguna"
                  >
                    {{ p.nama_pengguna || p.name || p.email }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Tanggal Mulai</label
                >
                <input
                  v-model="assignmentForm.tanggal_mulai"
                  type="date"
                  class="w-full rounded-lg border p-2"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Tanggal Target</label
                >
                <input
                  v-model="assignmentForm.tanggal_target"
                  type="date"
                  class="w-full rounded-lg border p-2"
                />
              </div>
            </div>
          </div>

          <!-- Step 2: Status -->
          <div v-if="currentStep === 2">
            <h3 class="font-semibold mb-2">Status Pengerjaan</h3>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="statusForm.status_pengerjaan"
                class="w-full rounded-lg border p-2"
              >
                <option value="">Pilih status</option>
                <option
                  v-for="s in statusOptions"
                  :key="s.id"
                  :value="s.id"
                >
                  {{ s.nama_status }}
                </option>
              </select>

            </div>
          </div>

          <!-- Step 3: Waktu Status & Selesai Pengerjaan -->
          <div v-if="currentStep === 2">
            <h3 class="font-semibold mb-4">Update Waktu Status & Selesai Pengerjaan</h3>
            
            <!-- Status Time Section -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
              <h4 class="font-medium text-blue-900 mb-4">📋 Waktu Status Pengerjaan</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Status</label>
                  <input
                    v-model="waktuStatusForm.waktu_status"
                    type="date"
                    class="w-full rounded-lg border p-2"
                  />
                </div>
              </div>
              <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Status Pengerjaan</label>
                <textarea
                  v-model="waktuStatusForm.catatan_waktu"
                  rows="3"
                  placeholder="Masukkan catatan terkait status pengerjaan saat ini..."
                  class="w-full rounded-lg border p-2"
                ></textarea>
              </div>
            </div>

            <!-- Completion Section -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-6">
              <h4 class="font-medium text-green-900 mb-4">✅ Waktu Selesai Pengerjaan</h4>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                  <input
                    v-model="waktuStatusForm.tanggal_selesai"
                    type="date"
                    class="w-full rounded-lg border p-2"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                  <input
                    v-model="waktuStatusForm.waktu_selesai"
                    type="time"
                    class="w-full rounded-lg border p-2"
                  />
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Selesai Pengerjaan</label>
                <textarea
                  v-model="waktuStatusForm.catatan_selesai"
                  rows="3"
                  placeholder="Masukkan catatan lengkap mengenai penyelesaian, hasil pekerjaan, atau tindakan yang telah dilakukan..."
                  class="w-full rounded-lg border p-2"
                ></textarea>
              </div>
            </div>

            <!-- Summary Table -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
              <h4 class="font-medium text-gray-900 mb-3">📊 Ringkasan Progress</h4>
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="bg-gray-200">
                      <th class="px-3 py-2 text-left font-semibold">Status Pengerjaan</th>
                      <th class="px-3 py-2 text-left font-semibold">Tanggal</th>
                      <th class="px-3 py-2 text-left font-semibold">Waktu</th>
                      <th class="px-3 py-2 text-left font-semibold">Catatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(history, index) in (props.aduan.riwayat_status || [])" :key="index" class="border-b hover:bg-gray-50">
                      <td class="px-3 py-2 font-medium" :class="getStatusColor(history.status_aduan?.nama_status)">
                        {{ history.status_aduan?.nama_status || '-' }}
                      </td>
                      <td class="px-3 py-2 text-sm">
                        {{ formatDate(history.waktu_status_aduan) }}
                      </td>
                      <td class="px-3 py-2 text-sm">
                        {{ formatTime(history.waktu_status_aduan) }}
                      </td>
                      <td class="px-3 py-2 text-gray-600 text-xs max-w-xs truncate">
                        {{ history.catatan || '-' }}
                      </td>
                    </tr>
                    <tr v-if="!(props.aduan.riwayat_status && props.aduan.riwayat_status.length)" class="border-b">
                      <td colspan="4" class="px-3 py-4 text-center text-gray-500 text-sm">
                        Belum ada riwayat status
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Navigation -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
          <button
            v-if="currentStep > 0"
            @click="previousStep"
            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg">
            Sebelumnya
          </button>
          <button
            v-if="currentStep < steps.length - 1"
            @click="nextStep"
            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg ml-auto"
          >
            Berikutnya
          </button>
          <button
            v-if="currentStep === steps.length - 1"
            @click="submitWizard"
            class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg ml-auto"
          >
            Simpan Aduan
          </button>
        </div>
      </div>
    </OPDLayout>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import OPDLayout from "@/Layouts/OpdLayout.vue";
import Swal from "sweetalert2";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  user: Object,
  aduan: Object,
  units: { type: Array, default: () => [] },
  currentUserOpd: Object,
  statusOptions: { type: Array, default: () => [] },
});

const currentStep = ref(0);
const picOptions = ref([]);
const loadingPics = ref(false);
const selectedImage = ref(null);
const isLoadingDraft = ref(false);
const fotoList = ref([]);

const steps = [
  { title: "Dokumen Laporan" },
  { title: "Verifikasi & Penugasan" },
  { title: "Update Waktu Status" },
];

const defaultSample = {
  no_aduan: "ADN-2024-00001",
  tanggal_lapor: "2024-02-01",
  kategori: "Jalan Rusak",
  lokasi: "Jl. Merdeka No. 10, Kelurahan Karangwaru",
  nama_pelapor: "Siti Nurhaliza",
  email: "siti@email.com",
  no_telepon: "08123456789",
  akses: "Publik",
  isi_aduan:
    "Jalan di depan rumah saya berlubang besar dan membahayakan keselamatan. Sudah berbulan-bulan seperti ini dan tidak ada perhatian dari pemerintah. Mohon segera diperbaiki sebelum ada korban.",
  latitude: -6.8822192,
  longitude: 107.6132567,
  foto: "",
};

const sampleData = props.aduan
  ? {
      no_aduan: props.aduan.no_aduan,
      tanggal_lapor: props.aduan.tanggal_lapor,
      kategori: props.aduan.kategori_aduan?.nama_kategori || "",
      lokasi: props.aduan.lokasi || "",
      nama_pelapor: props.aduan.masyarakat?.nama_lengkap || "",
      email: props.aduan.masyarakat?.pengguna?.email || "",
      no_telepon: props.aduan.masyarakat?.no_telp || "",
      akses:
        props.aduan.akses_aduan?.nama_akses_aduan ||
        props.aduan.akses_aduan?.nama_akses ||
        "",
      isi_aduan: props.aduan.isi_aduan || "",
      latitude: props.aduan.latitude ? parseFloat(props.aduan.latitude) : null,
      longitude: props.aduan.longitude ? parseFloat(props.aduan.longitude) : null,
      foto: props.aduan.foto || "",
    }
  : defaultSample

// verification step removed (not used)

const assignmentForm = ref({
  unit_kerja: "",
  pic: "",
  tanggal_mulai: new Date().toISOString().split("T")[0],
  tanggal_target: "",
});

const dokumentasiForm = ref({
  hasil_laporan: "",
  lampiran_dokumen: "",
});

// verifikasi inputs removed
const statusForm = ref({
  status_pengerjaan: "",
});


// Array statis untuk pilihan status
const statusOptions = [
  { id: "4", nama_status: "Diproses" }
];


const waktuStatusForm = ref({
  waktu_status: new Date().toISOString().split("T")[0],
  catatan_waktu: "",
  tanggal_selesai: new Date().toISOString().split("T")[0],
  waktu_selesai: new Date().toISOString().split("T")[1].substring(0, 5),
  catatan_selesai: "",
});

const getFotoUrl = (filename) => {
  if (!filename) return "";

  // Trim whitespace
  const trimmed = String(filename).trim();

  // If filename looks like a full path or URL, return as-is
  if (trimmed.startsWith("http") || trimmed.startsWith("/")) {
    return trimmed;
  }

  // If filename already contains upload_aduan path structure, just prepend /
  if (trimmed.includes("upload_aduan")) {
    const url = "/" + trimmed;
    console.debug("getFotoUrl: returning path URL", url);
    return url;
  }

  // Use no_aduan (complaint number) to build folder path for simple filenames
  const noAduan = props.aduan?.no_aduan || null;
  if (noAduan) {
    const url = `/upload_aduan/${noAduan}/${trimmed}`;
    console.debug("getFotoUrl: returning constructed URL", url);
    return url;
  }

  // fallback to upload_aduan root
  const url = `/upload_aduan/${trimmed}`;
  console.debug("getFotoUrl: returning fallback URL", url);
  return url;
};

const getFotoArray = (fotoField) => {
  if (!fotoField) {
    console.debug("getFotoArray: field is empty");
    return [];
  }
  // if already an array
  if (Array.isArray(fotoField)) {
    console.debug("getFotoArray: already an array", fotoField);
    return fotoField;
  }
  // try parse JSON array
  if (typeof fotoField === "string") {
    try {
      const parsed = JSON.parse(fotoField);
      if (Array.isArray(parsed)) {
        console.debug("getFotoArray: parsed JSON array", parsed);
        return parsed;
      }
    } catch (e) {
      // not JSON
    }

    // if comma separated
    if (fotoField.includes(",")) {
      const arr = fotoField
        .split(",")
        .map((s) => s.trim())
        .filter(Boolean);
      console.debug("getFotoArray: comma separated", arr);
      return arr;
    }

    // if pipe separated
    if (fotoField.includes("|")) {
      const arr = fotoField
        .split("|")
        .map((s) => s.trim())
        .filter(Boolean);
      console.debug("getFotoArray: pipe separated", arr);
      return arr;
    }

    // single file
    const arr = [fotoField.trim()];
    console.debug("getFotoArray: single file", arr);
    return arr;
  }

  return [];
};

// Try to load all photos from server folder for this aduan (fallback to sampleData.foto)
const loadPhotos = async () => {
  fotoList.value = [];
  const aduanId = props.aduan?.id;
  const noAduan = props.aduan?.no_aduan;
  if (!aduanId || !noAduan) {
    console.debug('loadPhotos: missing aduanId or noAduan');
    return;
  }

  try {
    // Expecting an API that returns { success: true, data: [ 'file1.jpg', 'file2.png', ... ] }
    const resp = await fetch(`/api/opd/aduan/${aduanId}/photos`);
    if (!resp.ok) {
      console.debug('loadPhotos: API returned status', resp.status);
      throw new Error(`API error: ${resp.status}`);
    }
    const data = await resp.json();
    console.debug('loadPhotos: API response', data);
    
    if (data && data.success && Array.isArray(data.data)) {
      if (data.data.length > 0) {
        // allow API to return either full URLs or filenames
        fotoList.value = data.data.map((f) => (String(f).trim())).filter(Boolean);
        console.debug('loadPhotos: loaded', fotoList.value.length, 'photos');
        return;
      } else {
        console.debug('loadPhotos: API returned empty data array');
      }
    } else {
      console.debug('loadPhotos: invalid API response structure', data);
    }
  } catch (e) {
    // ignore and fallback to parsing sampleData.foto
    console.debug('loadPhotos: failed to fetch photo list', e.message);
  }

  // fallback: use sampleData.foto parsing
  fotoList.value = [];
  console.debug('loadPhotos: falling back to sampleData.foto parsing');
};

const visibleFotos = computed(() => {
  return fotoList.value && fotoList.value.length > 0
    ? fotoList.value
    : getFotoArray(sampleData.foto || "");
});

const getMapsLink = (lat, lng) => {
  if (!lat && !lng) return "#";
  return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
    lat + "," + lng
  )}`;
};

const getMapEmbedUrl = (lat, lng) => {
  if (!lat || !lng) return "";
  return `https://www.google.com/maps?q=${lat},${lng}&z=17&output=embed`;
};

const closeImageModal = () => {
  selectedImage.value = null;
};

// Watch for unit_kerja changes and fetch PIC data
watch(
  () => assignmentForm.value.unit_kerja,
  async (newUnitId) => {
    // Skip if we're loading draft to preserve previously selected values
    if (isLoadingDraft.value) return;

    // Reset PIC when unit changes
    assignmentForm.value.pic = "";
    picOptions.value = [];

    if (newUnitId) {
      await populatePicOptionsForUnit(newUnitId);
    }
  }
);

// Populate PIC options for a given unit, preferring unit_opd.nama_pengguna when available
const populatePicOptionsForUnit = async (unitId, skipPicReset = false) => {
  picOptions.value = [];
  const unit = (props.units || []).find((u) => u.id === unitId);
  if (!unit) return;

  // If unit_opd.nama_pengguna is present, use it directly (string username as value)
  if (unit.nama_pengguna) {
    picOptions.value = [{ id: unit.nama_pengguna, nama_pengguna: unit.nama_pengguna }];
    // Only set pic if not loading from draft (not skipping reset)
    if (!skipPicReset) {
      assignmentForm.value.pic = unit.nama_pengguna;
    }
    return;
  }

  // Fallback: fetch pengguna list for the OPD and use numeric ids
  await fetchPicByUnit(unitId);

  // If a draft stored username or id, try to reconcile it with fetched options
  if (picOptions.value.length > 0 && assignmentForm.value.pic) {
    const match = picOptions.value.find(
      (p) =>
        p.nama_pengguna === assignmentForm.value.pic ||
        String(p.id) === String(assignmentForm.value.pic)
    );
    if (match) assignmentForm.value.pic = match.id;
  }
};

// Fetch PIC (Pengguna) for selected unit, preferring unit_opd.nama_pengguna when available
const fetchPicByUnit = async (unitOpdId) => {
  loadingPics.value = true;
  try {
    const response = await fetch(`/api/opd/unit/${unitOpdId}/pic`);
    const data = await response.json();

    if (data.success) {
      // Prefer unit_opd.nama_pengguna if returned from API
      if (data.unit_opd && data.unit_opd.nama_pengguna) {
        picOptions.value = [
          { id: data.unit_opd.nama_pengguna, nama_pengguna: data.unit_opd.nama_pengguna },
        ];
        assignmentForm.value.pic = data.unit_opd.nama_pengguna;
      } else {
        picOptions.value = data.pics;
      }
    } else {
      console.error(data.message);
      picOptions.value = [];
    }
  } catch (error) {
    console.error("Error fetching PIC data:", error);
    picOptions.value = [];
  } finally {
    loadingPics.value = false;
  }
};

const progressItems = [
  {
    title: "Verifikasi Awal",
    description: "Admin telah memverifikasi data aduan",
    date: "01 Feb 2024",
    completed: true,
  },
  {
    title: "Penugasan ke DPU",
    description: "Aduan ditugaskan ke Dinas Pekerjaan Umum",
    date: "02 Feb 2024",
    completed: true,
  },
  {
    title: "Survei Lapangan",
    description: "Tim teknis sedang melakukan survei lokasi",
    date: "On Progress",
    completed: false,
  },
];

// progress form removed (add-update UI deleted)

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const formatTime = (date) => {
  if (!date) return "-";
  return new Date(date).toLocaleTimeString("id-ID", {
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getStatusColor = (status) => {
  switch (status) {
    case "Diajukan":
      return "text-yellow-600 bg-yellow-100";
    case "Diproses":
    case "Sedang Dikerjakan":
      return "text-blue-600 bg-blue-100";
    case "Selesai":
      return "text-green-600 bg-green-100";
    case "Ditolak":
    case "Dibatalkan":
      return "text-red-600 bg-red-100";
    default:
      return "text-gray-600 bg-gray-100";
  }
};

const saveCurrentStep = async () => {
  if (!props.aduan || !props.aduan.id) return;

  const payload = {};
  // Send only the data relevant to the current step
  if (currentStep.value === 0) {
    payload.dokumentasi = dokumentasiForm.value;
  } else if (currentStep.value === 1) {
    // only assignment data (verifikasi inputs removed)
    payload.assignment = assignmentForm.value;
  } else if (currentStep.value === 2) {
    payload.status = statusForm.value;
  } else if (currentStep.value === 3) {
    // waktu_status contains waktu and optional catatan which will be saved to riwayat and tanggapan
    payload.waktu_status = waktuStatusForm.value;
  }

  try {
    await fetch(`/api/opd/wizard/${props.aduan.id}/save`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify(payload),
    });
  } catch (e) {
    console.error("Failed to save wizard draft:", e);
  }
};

const nextStep = async () => {
  if (currentStep.value < steps.length - 1) {
    await saveCurrentStep();
    currentStep.value++;
  }
};

const previousStep = async () => {
  if (currentStep.value > 0) {
    await saveCurrentStep();
    currentStep.value--;
  }
};

const submitWizard = async () => {
  const result = await Swal.fire({
    icon: "question",
    title: "Selesaikan Aduan?",
    text: "Apakah Anda yakin dengan semua data yang telah diisi?",
    showCancelButton: true,
    confirmButtonText: "Ya, Selesaikan",
    cancelButtonText: "Batal",
    confirmButtonColor: "#10B981",
    cancelButtonColor: "#EF4444",
  });

  if (result.isConfirmed) {
    const payload = {
      dokumentasi: dokumentasiForm.value,
      assignment: assignmentForm.value,
      status: statusForm.value,
      waktu_status: waktuStatusForm.value,
    };

    try {
      if (props.aduan && props.aduan.id) {
        await router.post(`/opd/wizard/${props.aduan.id}/submit`, payload);
      }

      Swal.fire({
        icon: "success",
        title: "Aduan Selesai!",
        text:
          "Proses penanganan aduan telah selesai dan pelapor akan menerima notifikasi.",
        confirmButtonColor: "#3B82F6",
      }).then(() => {
        router.visit("/opd/dashboard");
      });
    } catch (e) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: "Terjadi kesalahan saat mengirim data wizard.",
        confirmButtonColor: "#3B82F6",
      });
    }
  }
};

// Load saved draft (if any) when component mounts
const loadDraft = async () => {
  if (!props.aduan || !props.aduan.id) return;
  isLoadingDraft.value = true;
  try {
    const resp = await fetch(`/api/opd/wizard/${props.aduan.id}`);
    const data = await resp.json();
    if (data.success && data.data) {
      const d = data.data;
      if (d.dokumentasi) {
        Object.assign(dokumentasiForm.value, d.dokumentasi);
      }
      if (d.assignment) {
        Object.assign(assignmentForm.value, d.assignment);
      }
      if (d.status) {
        Object.assign(statusForm.value, d.status);
      }
      if (d.waktu_status) {
        Object.assign(waktuStatusForm.value, d.waktu_status);
      }

      // ensure PIC options are populated if draft contains unit_kerja
      // Pass true to skipPicReset to preserve previously selected PIC
      if (assignmentForm.value.unit_kerja) {
        await populatePicOptionsForUnit(assignmentForm.value.unit_kerja, true);
      }
    }
  } catch (e) {
    console.error("Failed to load wizard draft", e);
  } finally {
    isLoadingDraft.value = false;
  }
};

// run loadDraft once
loadDraft();
// load photos
loadPhotos();
</script>
