<template>
  <CitizenLayout>
    <div class="min-h-screen flex justify-center bg-gray-100 p-4">
      <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-5 space-y-4 h-fit my-6">
        <!-- Header -->
        <div class="text-center mb-4">
          <h1 class="text-2xl font-bold text-gray-800">Buat Laporan Aduan</h1>
          <p class="text-sm text-gray-500 mt-1">Laporkan keluhan Anda kepada kami</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Upload Foto -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Foto Bukti <span class="text-red-500">*</span>
            </label>
            <input
              type="file"
              accept="image/*"
              multiple
              @change="handleFileChange"
              class="w-full border rounded-lg py-3 px-3 text-sm"
            />
            <p v-if="form.foto.length === 0 && form.isDirty" class="text-xs text-red-500 mt-1">
              Foto bukti wajib diupload
            </p>
            <p class="text-xs text-gray-400 mt-1">
              Format: JPG, PNG (Maks. 2MB per foto, Maks. 5 foto)
            </p>

            <!-- Preview Image -->
            <div v-if="imagePreviews.length > 0" class="mt-3 grid grid-cols-2 gap-2">
              <div v-for="(preview, index) in imagePreviews" :key="index" class="relative">
                <img
                  :src="preview"
                  alt="Preview"
                  class="w-full rounded-xl h-32 object-cover border-2 border-gray-200"
                />
                <button
                  type="button"
                  @click="removePhoto(index)"
                  class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 w-6 h-6 flex items-center justify-center text-xs shadow-md hover:bg-red-600"
                >
                  ✕
                </button>
              </div>
            </div>
          </div>

          <!-- Lokasi Otomatis -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Lokasi <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <button
                type="button"
                @click="detectLocation"
                :disabled="isDetectingLocation"
                class="w-full border rounded-lg py-3 px-4 bg-white text-left flex items-center justify-between hover:bg-gray-50 transition"
              >
                <div class="flex-1">
                  <div v-if="isDetectingLocation" class="flex items-center gap-2 text-gray-500">
                    <svg
                      class="animate-spin h-4 w-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                      />
                    </svg>
                    <span class="text-sm">Mendeteksi lokasi...</span>
                  </div>
                  <div v-else-if="!form.latitude || !form.longitude" class="text-gray-400 text-sm">
                    Klik untuk deteksi lokasi GPS
                  </div>
                  <div v-else class="text-sm text-gray-800">
                    <div class="font-medium text-green-700 mb-1">✓ Lokasi terdeteksi</div>
                  </div>
                </div>
                <svg
                  class="w-5 h-5 text-blue-500"
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
              </button>
            </div>

            <!-- Detail Lokasi -->
            <div
              v-if="form.latitude && form.longitude"
              class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg space-y-2"
            >
              <div class="flex items-start gap-2">
                <svg
                  class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0"
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
                <div class="flex-1">
                  <div class="text-xs font-semibold text-blue-900 mb-1">Koordinat GPS:</div>
                  <div class="text-xs text-blue-700 font-mono">
                    {{ form.latitude.toFixed(6) }}, {{ form.longitude.toFixed(6) }}
                  </div>
                </div>
              </div>

              <div class="flex items-start gap-2 pt-2 border-t border-blue-200">
                <svg
                  class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  />
                </svg>
                <div class="flex-1">
                  <div class="text-xs font-semibold text-blue-900 mb-1">Alamat:</div>
                  <div class="text-xs text-blue-700">
                    <span v-if="!locationName" class="italic text-gray-500">Memuat alamat...</span>
                    <span v-else>{{ locationName }}</span>
                  </div>
                </div>
              </div>
            </div>

            <p
              v-if="form.errors.latitude || form.errors.longitude"
              class="text-xs text-red-500 mt-2"
            >
              Lokasi diperlukan
            </p>
          </div>

          <!-- Jenis Aduan -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Jenis Aduan <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.jenis"
              class="w-full border rounded-lg py-3 px-3 text-sm"
              required
            >
              <option value="">-- Pilih Jenis --</option>
              <option v-for="jenis in jenisOptions" :key="jenis.id" :value="jenis.id">
                {{ jenis.nama_akses_aduan }}
              </option>
            </select>
            <p v-if="form.errors.jenis" class="text-xs text-red-500 mt-1">
              {{ form.errors.jenis }}
            </p>
          </div>

          <!-- Kategori Aduan -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Kategori Aduan <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.kategori"
              class="w-full border rounded-lg py-3 px-3 text-sm"
              required
            >
              <option value="">-- Pilih Kategori --</option>
              <option v-for="kategori in kategoriOptions" :key="kategori.id" :value="kategori.id">
                {{ kategori.nama_kategori }}
              </option>
            </select>
            <p v-if="form.errors.kategori" class="text-xs text-red-500 mt-1">
              {{ form.errors.kategori }}
            </p>
          </div>

          <!-- Deskripsi -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Deskripsi Aduan <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.deskripsi"
              rows="5"
              placeholder="Jelaskan detail keluhan Anda..."
              class="w-full border rounded-lg py-3 px-3 text-sm resize-none"
              required
            ></textarea>
            <p class="text-xs text-gray-400 mt-1">Min. 20 karakter</p>
            <p v-if="form.errors.deskripsi" class="text-xs text-red-500 mt-1">
              {{ form.errors.deskripsi }}
            </p>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="
              form.processing || !form.latitude || !form.longitude || form.foto.length === 0
            "
            class="w-full bg-yellow-400 hover:bg-yellow-500 rounded-full py-3 font-semibold text-gray-800 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing">Mengirim...</span>
            <span v-else>Laporkan Sekarang</span>
          </button>
        </form>
      </div>
    </div>
  </CitizenLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import CitizenLayout from '@/Layouts/CitizenLayout.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  jenisOptions: { type: Array, default: () => [] },
  kategoriOptions: { type: Array, default: () => [] },
})

const form = useForm({
  foto: [], // Changed to array
  latitude: null,
  longitude: null,
  lokasi: '', // akan diisi koordinat dulu, lalu alamat
  jenis: '',
  kategori: '',
  deskripsi: '',
})

const imagePreviews = ref([])
const isDetectingLocation = ref(false)
const locationName = ref('')

const locationDisplay = computed(() => {
  if (locationName.value) {
    return locationName.value // alamat lengkap jika sudah ada
  }
  if (form.latitude && form.longitude) {
    return `${form.latitude.toFixed(6)}, ${form.longitude.toFixed(6)}`
  }
  return 'Gagal mendapatkan alamat'
})

const handleFileChange = e => {
  const files = Array.from(e.target.files)

  if (files.length + form.foto.length > 5) {
    Swal.fire({
      icon: 'warning',
      title: 'Batas Maksimum',
      text: 'Maksimal 5 foto yang diperbolehkan',
      confirmButtonColor: '#3B82F6',
    })
    e.target.value = ''
    return
  }

  files.forEach(file => {
    if (file.size > 2 * 1024 * 1024) {
      Swal.fire({
        icon: 'warning',
        title: 'File Terlalu Besar',
        text: `File ${file.name} melebihi 2MB`,
        confirmButtonColor: '#3B82F6',
      })
      return
    }

    // Add to form data
    form.foto.push(file)

    // Create preview
    const reader = new FileReader()
    reader.onload = event => {
      imagePreviews.value.push(event.target.result)
    }
    reader.readAsDataURL(file)
  })

  // Reset input value to allow selecting same files again if needed (or adding more)
  e.target.value = ''
}

const removePhoto = index => {
  form.foto.splice(index, 1)
  imagePreviews.value.splice(index, 1)
}

// Reverse geocoding async (jalan di background)
const reverseGeocode = async (lat, lon) => {
  try {
    const response = await fetch('/api/reverse-geocode', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN':
          document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: JSON.stringify({ lat, lon }),
    })
    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        locationName.value = data.address
        form.lokasi = data.address // update alamat lengkap
      } else {
        locationName.value = 'Gagal mendapatkan alamat'
      }
    } else {
      locationName.value = 'Gagal mendapatkan alamat'
    }
  } catch (error) {
    locationName.value = 'Error: ' + error.message
  }
}

const detectLocation = () => {
  if (!navigator.geolocation) {
    Swal.fire({
      icon: 'error',
      title: 'Geolocation Tidak Didukung',
      text: 'Browser Anda tidak mendukung fitur lokasi',
      confirmButtonColor: '#3B82F6',
    })
    return
  }

  isDetectingLocation.value = true

  navigator.geolocation.getCurrentPosition(
    position => {
      form.latitude = position.coords.latitude
      form.longitude = position.coords.longitude

      // tampilkan koordinat dulu
      form.lokasi = `${form.latitude.toFixed(6)}, ${form.longitude.toFixed(6)}`

      // jalankan reverse geocoding di background
      reverseGeocode(form.latitude, form.longitude)

      isDetectingLocation.value = false
    },
    error => {
      isDetectingLocation.value = false
      let errorMessage = 'Gagal mendeteksi lokasi'
      switch (error.code) {
        case error.PERMISSION_DENIED:
          errorMessage = 'Izin akses lokasi ditolak. Silakan aktifkan di pengaturan browser.'
          break
        case error.POSITION_UNAVAILABLE:
          errorMessage = 'Informasi lokasi tidak tersedia'
          break
        case error.TIMEOUT:
          errorMessage = 'Waktu permintaan lokasi habis'
          break
      }
      Swal.fire({
        icon: 'error',
        title: 'Geolocation Error',
        text: errorMessage,
        confirmButtonColor: '#3B82F6',
      })
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
  )
}

onMounted(() => {
  detectLocation()
})

const submit = () => {
  form.post('/aduan', {
    forceFormData: true,
    onSuccess: () => {
      form.reset()
      imagePreviews.value = []
    },
  })
}
</script>
