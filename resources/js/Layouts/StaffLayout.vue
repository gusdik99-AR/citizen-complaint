<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps({
  user: Object,
});

const sidebarOpen = ref(false);

const logout = () => {
  router.post("/logout");
};

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
  sidebarOpen.value = false;
};



</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-40">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Left: Hamburger + Logo -->
          <div class="flex items-center gap-3">
            <!-- Hamburger Button (Mobile Only) -->
            <button
              @click="toggleSidebar"
              class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition"
            >
              <svg
                class="w-6 h-6 text-gray-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>

            <!-- Logo & App Name -->
            <div class="flex items-center gap-3">
              <img
                src="/logo.png"
                alt="Logo"
                class="h-10 w-10"
                onerror="this.style.display='none'"
              />
              <div class="hidden sm:block">
                <h1 class="text-lg font-bold text-gray-800 leading-tight">E-Lapor</h1>
                <p class="text-xs text-gray-500 leading-tight">Pemkab Pemalang</p>
              </div>
            </div>
          </div>
          <!-- Navigation -->
          <nav class="hidden lg:flex items-center gap-2 flex-1 justify-start ml-10">
            <!-- Dashboard -->
            
            <a
              href=""
              class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7m-9 8V9h4v8m5 0V9h4v11H5V9h4v11z"
                />
              </svg>
              <span>Dashboard</span>
            </a>
            
  
            <!-- Master -->
            <div class="relative group">
              <a
                href=""
                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h18v18H3V3zm6 0v18m6-18v18M3 9h18m-18 6h18"
                  />
                </svg>
                <span>Master</span>
              </a>

              <!-- Sub Master -->
              <ul
                class="absolute left-0 mt-2 w-40 bg-white shadow-lg rounded-md opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <li>
                  <a
                    href="/master/kategori"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Kategori</a
                  >
                </li>
                <li>
                  <a
                    href="/master/pengguna"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Pengguna</a
                  >
                </li>
                <li>
                  <a
                    href="/master/peran"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Peran</a
                  >
                </li>
                <li>
                  <a
                    href="/master/aksesaduan"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Akses Aduan</a
                  >
                </li>
                <li>
                  <a
                    href="/master/opd"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >OPD</a
                  >
                </li>
                <li>
                  <a
                    href="/master/unit-opd"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Unit OPD</a
                  >
                </li>
              </ul>
            </div>
            <!-- Manajemen Aduan -->
            <div class="relative group">
              <a
                href=""
                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-4 4H8l-4-4m16 0H4"
                  />
                </svg>
                <span>Manajemen Aduan</span>
              </a>
              <!-- Sub  Manajemen Aduan -->
              <ul
                class="absolute left-0 mt-2 w-40 bg-white shadow-lg rounded-md opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <li>
                  <a
                    href="/manajemenaduan/daftaraduan"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Daftar Aduan</a
                  >
                </li>
              </ul>
            </div>
            <div class="relative group">
              <a
                href=""
                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M7 4h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2zm0 0v4h10V4"
                  />
                </svg>
                <span>Laporan</span>
              </a>
              <!-- Sub Laporan -->
              <ul
                class="absolute left-0 mt-2 w-40 bg-white shadow-lg rounded-md opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <li>
                  <a
                    href="/laporan/laporanaduan"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Laporan Aduan</a
                  >
                </li>
              </ul>
            </div>
            <div class="relative group">
              <a
                href=""
                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11.983 4.5a1.5 1.5 0 012.534 0l.867 1.5a1.5 1.5 0 001.3.75h1.732a1.5 1.5 0 011.5 1.5v1.732a1.5 1.5 0 00.75 1.3l1.5.867a1.5 1.5 0 010 2.534l-1.5.867a1.5 1.5 0 00-.75 1.3v1.732a1.5 1.5 0 01-1.5 1.5h-1.732a1.5 1.5 0 00-1.3.75l-.867 1.5a1.5 1.5 0 01-2.534 0l-.867-1.5a1.5 1.5 0 00-1.3-.75H8.083a1.5 1.5 0 01-1.5-1.5v-1.732a1.5 1.5 0 00-.75-1.3l-1.5-.867a1.5 1.5 0 010-2.534l1.5-.867a1.5 1.5 0 00.75-1.3V8.25a1.5 1.5 0 011.5-1.5h1.732a1.5 1.5 0 001.3-.75l.867-1.5z"
                  />
                </svg>
                <span>Pengaturan</span>
              </a>
              <!-- Sub Pengaturan -->
              <ul
                class="absolute left-0 mt-2 w-40 bg-white shadow-lg rounded-md opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <li>
                  <a
                    href="/pengaturan/profile"
                    class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-md transition"
                    >Profil</a
                  >
                </li>
              </ul>
            </div>
          </nav>
          <!-- Center: Navigation Links (Desktop Only) -->

          <!-- Right: User Info + Logout -->
          <div class="flex items-center gap-4">
            <div class="hidden md:block text-right">
              <p class="text-sm font-semibold text-gray-800">
                {{ user?.nama_pengguna || "Administrator" }}
              </p>
              <p class="text-xs text-gray-500">
                {{ user?.email || "admin@pemalang.go.id" }}
              </p>
            </div>
            <button
              @click="logout"
              class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div
      v-if="sidebarOpen"
      @click="closeSidebar"
      class="fixed inset-0 bg-black bg-opacity-50 z-50 md:hidden transition-opacity"
    ></div>

    <!-- Mobile Sidebar Drawer -->
    <aside
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed top-0 left-0 w-64 h-full bg-white shadow-2xl z-50 md:hidden transition-transform duration-300 ease-in-out"
    >
      <div class="p-4 border-b">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-gray-800">Menu</h2>
            <p class="text-xs text-gray-500">Navigasi Dashboard</p>
          </div>
          <button @click="closeSidebar" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg
              class="w-5 h-5 text-gray-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <ahref= href="/"
          class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7m-9 8V9h4v8m5 0V9h4v11H5V9h4v11z"
            />
          </svg>
          Dashboard
        </ahref=>

       <!-- Master -->
        <div class="group">
          <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h18v18H3V3zm6 0v18m6-18v18M3 9h18m-18 6h18" />
            </svg>
            <span>Master</span>
          </a>
          <!-- Submenu inline -->
          <ul class="max-h-0 overflow-hidden transition-all duration-300 ease-out group-hover:max-h-96 bg-white rounded-md shadow-md mt-1">
            <li><a href="/master/pengguna" class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700">Pengguna</a></li>
            <li><a href="/master/peran" class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700">Peran</a></li>
            <li><a href="/master/kategori" class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700">Kategori</a></li>
            <li><a href="/master/aksesaduan" class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700">Akses Aduan</a></li>
            <li><a href="/master/opd" class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700">Organisasi Perangkat Daerah (OPD)</a></li>
            <li><a href="/master/unit-opd" class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700">Unit OPD</a></li>
          </ul>
        </div>




        <!-- Menu lain tetap sama -->
        <div class="group">
          <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-4 4H8l-4-4m16 0H4"
              />
            </svg>
            <span>Manajemen Aduan</span>
          </a>
          <ul
            class="max-h-0 overflow-hidden transition-all duration-300 ease-out group-hover:max-h-40 bg-white rounded-md shadow-md mt-1"
          >
            <li>
              <a
                href="/pengaduan/daftar"
                class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700"
                >Daftar Aduan</a
              >
            </li>
          </ul>
        </div>

        <!-- Menu lain tetap sama -->
        <div class="group">
          <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 4h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2zm0 0v4h10V4"
              />
            </svg>
            <span>Laporan</span>
          </a>
          <ul
            class="max-h-0 overflow-hidden transition-all duration-300 ease-out group-hover:max-h-40 bg-white rounded-md shadow-md mt-1"
          >
            <li>
              <a
                href="/pengaduan/daftar"
                class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700"
                >Laporan Aduan</a
              >
            </li>
          </ul>
        </div>

        <!-- Menu lain tetap sama -->
        <div class="group">
          <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11.983 4.5a1.5 1.5 0 012.534 0l.867 1.5a1.5 1.5 0 001.3.75h1.732a1.5 1.5 0 011.5 1.5v1.732a1.5 1.5 0 00.75 1.3l1.5.867a1.5 1.5 0 010 2.534l-1.5.867a1.5 1.5 0 00-.75 1.3v1.732a1.5 1.5 0 01-1.5 1.5h-1.732a1.5 1.5 0 00-1.3.75l-.867 1.5a1.5 1.5 0 01-2.534 0l-.867-1.5a1.5 1.5 0 00-1.3-.75H8.083a1.5 1.5 0 01-1.5-1.5v-1.732a1.5 1.5 0 00-.75-1.3l-1.5-.867a1.5 1.5 0 010-2.534l1.5-.867a1.5 1.5 0 00.75-1.3V8.25a1.5 1.5 0 011.5-1.5h1.732a1.5 1.5 0 001.3-.75l.867-1.5z"
              />
            </svg>
            <span>Pengaturan</span>
          </a>
          <ul
            class="max-h-0 overflow-hidden transition-all duration-300 ease-out group-hover:max-h-40 bg-white rounded-md shadow-md mt-1"
          >
            <li>
              <a
                href="/pengaduan/daftar"
                class="block px-4 py-2 hover:bg-yellow-50 hover:text-yellow-700"
                >Profil</a
              >
            </li>
          </ul>
        </div>
      </nav>
      

    </aside>
    

    <!-- Main Content -->
    <main class="flex-grow px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>
  </div>
</template>


