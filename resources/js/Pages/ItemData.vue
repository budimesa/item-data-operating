<template>
    <dashboard-layout>
      <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Item Data</h1>        
        <BrandContent />
      </div>
    </dashboard-layout>
  </template>
  
  <script setup>
  import DashboardLayout from '@/Layouts/DashboardLayout.vue';
  import { onMounted } from 'vue';
  import BrandContent from './Contents/BrandContent.vue';
  </script>
  