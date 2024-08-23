<template>
    <div class="flex h-screen bg-gray-50">
      <!-- Sidebar yang bisa di-collapse -->
      <Sidebar :is-collapsed="isSidebarCollapsed" />
  
      <div class="flex-1 flex flex-col">
        <!-- Topbar -->
        <Topbar @toggle-sidebar="toggleSidebar" />
        
        <!-- Page Content -->
        <main class="flex-1 p-4 overflow-y-auto">
          <slot />
        </main>
  
        <!-- Footer (Optional) -->
        <Footer class="p-4 bg-white border-t">
          <p class="text-sm text-gray-500 text-center">© 2024 Your Company. All rights reserved.</p>
        </Footer>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import Sidebar from '@/Components/Sidebar.vue';
  import Topbar from '@/Components/Topbar.vue';
  import Footer from '@/Components/Footer.vue';
  
  // State untuk mengatur apakah sidebar collapsed atau tidak
  const isSidebarCollapsed = ref(false);
  
  function toggleSidebar() {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
  }
  </script>
  
  <style scoped>
  /* No custom styles needed unless for specific adjustments */
  </style>
  