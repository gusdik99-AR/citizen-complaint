<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
          <img src="/logo.png" alt="icon" class="h-10 w-10" />
          <h1 class="text-2xl font-bold text-gray-800">
            {{ title || 'Citizen Complaint' }}
          </h1>
          <nav class="flex gap-4">
            <slot name="nav" />
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
      <div class="container mx-auto px-4 py-6 text-center text-gray-600">
        <p>&copy; {{ new Date().getFullYear() }} Citizen Complaint System. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
defineProps({
  title: String,
})
</script>
