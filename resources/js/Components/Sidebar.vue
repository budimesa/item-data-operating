<template>
  <aside :class="['transition-width duration-300 bg-white border-r overflow-hidden', isCollapsed ? 'w-14' : 'w-64']">
    <div class="h-full px-2 py-6">
      <nav>
        <ul class="space-y-2">
          <!-- Dashboard Menu -->
          <li>
            <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
              <svg class="w-6 h-6 text-gray-800 dark:text-white flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd"/>
              </svg>
              <transition name="fade">
                <span v-if="!isCollapsed" class="ml-3">Dashboard</span>
              </transition>
            </a>
          </li>

          <!-- Master Menu with Dropdown -->
          <li>
            <div @click="toggleMasterMenu" class="flex items-center justify-between p-2 text-gray-700 rounded-lg hover:bg-gray-100 cursor-pointer">
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-800 dark:text-white flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M4 3h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm1 2v14h14V5H5Zm4 4h6v2H9V9Zm0 4h6v2H9v-2Z" clip-rule="evenodd"/>
                </svg>
                <transition name="fade">
                  <span v-if="!isCollapsed" class="ml-3">Master</span>
                </transition>
              </div>
              <transition name="fade">
                <svg v-if="!isCollapsed" :class="{'transform rotate-180': isMasterMenuOpen}" class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </transition>
            </div>

            <!-- Dropdown Items -->
            <transition name="slide fade">
              <ul v-if="isMasterMenuOpen && !isCollapsed" class="ml-10 space-y-2">
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Brand</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Color</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Color Tol</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Density</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Series Type</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Size</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Size Tol</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span class="ml-3">Item Type</span>
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100">
                    <span :class="['ml-3 transition-all duration-300', isCollapsed ? 'max-w-0 overflow-hidden' : 'max-w-full']">
                      Item Data
                    </span>
                  </a>
                </li>
              </ul>
            </transition>
          </li>

          <!-- Tambahkan item menu lainnya di sini -->
        </ul>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  isCollapsed: {
    type: Boolean,
    default: false,
  },
});

const isMasterMenuOpen = ref(false);

function toggleMasterMenu() {
  isMasterMenuOpen.value = !isMasterMenuOpen.value;
}
</script>

<style scoped>
/* Transisi fade hanya pada teks */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
  transform: translateX(-10px);
}

/* Transisi khusus untuk perubahan lebar */
.transition-width {
  transition: width 0.3s ease;
}

/* Transisi untuk dropdown slide */
.slide-enter-active, .slide-leave-active {
  transition: max-height 0.3s ease;
}
.slide-enter, .slide-leave-to /* .slide-leave-active in <2.1.8 */ {
  max-height: 0;
  overflow: hidden;
}
</style>
