<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      selesai: 107,
      proses: 20,
      verifikasi: 30,
      diajukan: 24,
    }),
  },
  publicReports: {
    type: Array,
    default: () => [],
  },
  heroImages: {
    type: Array,
    default: () => [],
  },
})

// Mobile menu state
const mobileMenuOpen = ref(false)

// Public reports pagination
const visibleReportsCount = ref(4)

const loadMore = () => {
  visibleReportsCount.value += 4
}

const visibleReports = computed(() => {
  return props.publicReports.slice(0, visibleReportsCount.value)
})

const hasMore = computed(() => {
  return visibleReportsCount.value < props.publicReports.length
})

// Carousel state
const currentSlide = ref(0)
let carouselInterval = null

const nextSlide = () => {
  if (props.heroImages.length > 0) {
    currentSlide.value = (currentSlide.value + 1) % props.heroImages.length
  }
}

const goToSlide = index => {
  currentSlide.value = index
}

// Auto-play carousel
onMounted(() => {
  if (props.heroImages.length > 1) {
    carouselInterval = setInterval(nextSlide, 5000)
  }
})

onUnmounted(() => {
  if (carouselInterval) {
    clearInterval(carouselInterval)
  }
})
</script>

<template>
  <Head title="E-Lapor Pemkab Pemalang" />

  <div class="min-h-screen flex flex-col bg-gradient-to-b from-gray-50 to-white">
    <!-- Header / Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <!-- Logo and Title -->
          <div class="flex items-center space-x-3">
            <img src="/logo.png" alt="Logo Pemkab Pemalang" class="h-12 w-12 object-contain" />
            <div class="hidden sm:block">
              <h1 class="text-lg font-bold text-gray-900">E-Lapor</h1>
              <p class="text-sm text-gray-600">Pemkab Pemalang</p>
            </div>
          </div>

          <!-- Desktop Navigation -->
          <nav class="hidden md:flex items-center space-x-6">
            <Link href="/" class="text-gray-700 hover:text-blue-600 transition font-medium">
              Home
            </Link>
            <Link
              href="#laporan-publik"
              class="text-gray-700 hover:text-blue-600 transition font-medium"
            >
              Laporan Publik
            </Link>
            <Link href="/login" class="text-gray-700 hover:text-blue-600 transition font-medium">
              Login
            </Link>
            <Link
              href="/aduan/create"
              class="bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600 transition font-medium shadow-md"
            >
              Lapor Online
            </Link>
          </nav>

          <!-- Mobile Menu Button -->
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition"
          >
            <svg
              class="w-6 h-6 text-gray-700"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                v-if="!mobileMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div v-show="mobileMenuOpen" class="md:hidden pb-4 space-y-2">
          <Link
            href="/"
            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition"
          >
            Home
          </Link>
          <Link
            href="#laporan-publik"
            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition"
          >
            Laporan Publik
          </Link>
          <Link
            href="/login"
            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition"
          >
            Login
          </Link>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
      <!-- Hero Carousel -->
      <section
        v-if="heroImages.length > 0"
        class="relative bg-white rounded-2xl shadow-lg overflow-hidden"
      >
        <div class="relative h-64 sm:h-80 md:h-96">
          <!-- Carousel Images -->
          <div
            v-for="(image, index) in heroImages"
            :key="index"
            class="absolute inset-0 transition-opacity duration-500"
            :class="currentSlide === index ? 'opacity-100' : 'opacity-0'"
          >
            <img
              :src="image.url"
              :alt="image.alt || 'Hero Image'"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Carousel Dots -->
          <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
            <button
              v-for="(image, index) in heroImages"
              :key="index"
              @click="goToSlide(index)"
              class="w-2 h-2 rounded-full transition-all"
              :class="currentSlide === index ? 'bg-white w-6' : 'bg-white/50'"
            />
          </div>
        </div>
      </section>

      <!-- Statistics Section -->
      <section>
        <h2 class="text-lg font-bold text-gray-900 mb-3">Statistik Tindakan</h2>
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
          <!-- Selesai -->
          <div
            class="bg-gradient-to-r from-green-400 to-green-500 rounded-lg px-4 py-3 text-white shadow-md flex items-center justify-between md:flex-col md:items-start md:justify-center md:h-24"
          >
            <div class="text-2xl font-bold md:text-3xl">{{ stats.selesai }}</div>
            <div class="text-sm font-medium text-right md:text-left md:mt-1">Selesai</div>
          </div>

          <!-- Proses -->
          <div
            class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-lg px-4 py-3 text-white shadow-md flex items-center justify-between md:flex-col md:items-start md:justify-center md:h-24"
          >
            <div class="text-2xl font-bold md:text-3xl">{{ stats.proses }}</div>
            <div class="text-sm font-medium text-right md:text-left md:mt-1">Proses</div>
          </div>

          <!-- Verifikasi -->
          <div
            class="bg-gradient-to-r from-pink-400 to-pink-500 rounded-lg px-4 py-3 text-white shadow-md flex items-center justify-between md:flex-col md:items-start md:justify-center md:h-24"
          >
            <div class="text-2xl font-bold md:text-3xl">{{ stats.verifikasi }}</div>
            <div class="text-sm font-medium text-right md:text-left md:mt-1">Verifikasi</div>
          </div>

          <!-- Diajukan -->
          <div
            class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-lg px-4 py-3 text-white shadow-md flex items-center justify-between md:flex-col md:items-start md:justify-center md:h-24"
          >
            <div class="text-2xl font-bold md:text-3xl">{{ stats.diajukan }}</div>
            <div class="text-sm font-medium text-right md:text-left md:mt-1">Diajukan</div>
          </div>
        </div>
      </section>

      <!-- Public Reports Section -->
      <section id="laporan-publik">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Laporan Publik</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <Link
            v-for="report in visibleReports"
            :key="report.id"
            :href="`/aduan/${report.id}`"
            class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow cursor-pointer"
          >
            <!-- Report Image -->
            <div class="relative h-48 bg-gray-200 overflow-hidden">
              <img
                v-if="report.image"
                :src="report.image"
                :alt="report.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
                <svg
                  class="w-12 h-12 text-gray-300"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>

              <!-- Gradient Overlay -->
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"
              ></div>

              <!-- Status Badge -->
              <div class="absolute top-3 left-3">
                <span
                  class="px-2.5 py-1 rounded-md text-[10px] font-bold text-white uppercase tracking-wider shadow-sm backdrop-blur-sm"
                  :class="{
                    'bg-green-500/90': report.status === 'Selesai',
                    'bg-blue-500/90': report.status === 'Proses',
                    'bg-pink-500/90': report.status === 'Verifikasi',
                    'bg-orange-500/90': report.status === 'Diajukan',
                  }"
                >
                  {{ report.status }}
                </span>
              </div>

              <!-- Photo Count Badge -->
              <div v-if="report.photos_count > 1" class="absolute top-3 right-3">
                <span
                  class="flex items-center space-x-1 px-2 py-1 bg-black/60 backdrop-blur-sm rounded-md text-white text-xs font-medium shadow-sm border border-white/20"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                  </svg>
                  <span>+{{ report.photos_count - 1 }}</span>
                </span>
              </div>

              <!-- Location (Over Image) -->
              <div class="absolute bottom-3 left-3 right-3 text-white">
                <div class="flex items-center text-xs font-medium opacity-90 drop-shadow-md">
                  <svg
                    class="w-3.5 h-3.5 mr-1 text-white/90"
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
                  <span class="line-clamp-1">{{ report.location }}</span>
                </div>
              </div>
            </div>

            <!-- Report Info -->
            <div class="p-4">
              <h3
                class="font-bold text-gray-800 text-sm mb-2 line-clamp-2 leading-relaxed group-hover:text-blue-600 transition-colors"
              >
                {{ report.title }}
              </h3>

              <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                  <svg
                    class="w-3.5 h-3.5 mr-1.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                  </svg>
                  <!-- Assuming we don't have date in report object based on controller, but if we did -->
                  <span>Baru saja</span>
                </div>
                <!-- Using existing Vote count logic if user wants, but making it cleaner -->
                <!-- Assuming 'views' was actually vote count in previous code -->
              </div>
            </div>
          </Link>
        </div>

        <!-- Load More Button -->
        <div v-if="hasMore" class="flex justify-center mt-6">
          <button
            @click="loadMore"
            class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-medium shadow-md transition-all transform hover:scale-105"
          >
            Muat Lebih Banyak
          </button>
        </div>

        <!-- Empty State -->
        <div v-if="publicReports.length === 0" class="text-center py-12">
          <svg
            class="w-16 h-16 text-gray-400 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />
          </svg>
          <p class="text-gray-500">Belum ada laporan publik</p>
        </div>
      </section>
    </main>

    <!-- Floating Action Button - Lapor Online (Mobile Only) -->
    <div
      class="md:hidden fixed bottom-6 left-0 right-0 flex justify-center z-50 pointer-events-none"
    >
      <Link
        href="/aduan/create"
        class="pointer-events-auto bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-full shadow-2xl flex items-center space-x-3 transition-all transform hover:scale-105 font-semibold text-lg"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
          />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
          />
        </svg>
        <span>Lapor Online</span>
      </Link>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-auto py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <p class="text-gray-400 text-sm">
            &copy; {{ new Date().getFullYear() }} E-Lapor Pemkab Pemalang. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
