<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps({
  user: Object,
})

const sidebarOpen = ref(false)

const logout = () => {
  router.post('/logout')
}

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

const closeSidebar = () => {
  sidebarOpen.value = false
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
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
              <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>

            <!-- Logo & App Name -->
            <div class="flex items-center gap-3">
              <img src="/logo-pemalang.png" alt="Logo" class="h-10 w-10" onerror="this.style.display='none'">
              <div class="hidden sm:block">
                <h1 class="text-lg font-bold text-gray-800 leading-tight">E-Lapor</h1>
                <p class="text-xs text-gray-500 leading-tight">Pemkab Pemalang</p>
              </div>
            </div>
          </div>

          <!-- Right: User Info + Logout -->
          <div class="flex items-center gap-4">
            <div class="hidden md:block text-right">
              <p class="text-sm font-semibold text-gray-800">{{ user?.nama_pengguna || 'Administrator' }}</p>
              <p class="text-xs text-gray-500">{{ user?.email || 'admin@pemalang.go.id' }}</p>
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
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <nav class="p-4 space-y-2">
        <a href="/" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Pengaduan
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          Pengguna
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Pengaturan
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>
  </div>
</template>
