<template>
    <aside :class="['transition-all duration-300 bg-white border-r', isCollapsed ? 'w-20' : 'w-64']">
      <div class="h-full px-4 py-6">
        <h2 v-if="!isCollapsed" class="text-lg font-semibold">Your App</h2>
        <nav class="mt-6">
          <ul class="space-y-2">
            <li>
              <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 9h18M3 15h18M3 21h18"></path>
                </svg>
                <span v-if="!isCollapsed" class="ml-3">Dashboard</span>
              </a>
            </li>
            <!-- Tambahkan item menu lainnya di sini -->
          </ul>
        </nav>
      </div>
    </aside>
  </template>
  
  <script setup>
  defineProps({
    isCollapsed: {
      type: Boolean,
      default: false,
    },
  });
  </script>
  
  <style scoped>
  /* No custom styles needed unless for specific adjustments */
  </style>
  