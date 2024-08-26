<template>
    <dashboard-layout>
      <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Item Data</h1>
        <p>This is the Item Data page.</p>
        
        <!-- Card with Nav Tabs from Flowbite -->
        <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
          <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800" id="defaultTab" role="tablist">
            <li class="me-2">
              <button id="about-tab" data-tabs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="true" class="inline-block p-4 rounded-ss-lg hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-500" data-tab="about">About</button>
            </li>
            <li class="me-2">
              <button id="services-tab" data-tabs-target="#services" type="button" role="tab" aria-controls="services" aria-selected="false" class="inline-block p-4 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300" data-tab="services">Services</button>
            </li>
            <li class="me-2">
              <button id="statistics-tab" data-tabs-target="#statistics" type="button" role="tab" aria-controls="statistics" aria-selected="false" class="inline-block p-4 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300" data-tab="statistics">Facts</button>
            </li>
          </ul>
          <div id="defaultTabContent">
            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="about" role="tabpanel" aria-labelledby="about-tab">
              <h2 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">About Content</h2>
              <p class="mb-3 text-gray-500 dark:text-gray-400">Details about the item data.</p>
            </div>
            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="services" role="tabpanel" aria-labelledby="services-tab">
              <h2 class="mb-5 text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Services Content</h2>
              <!-- List of services -->
            </div>
            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="statistics" role="tabpanel" aria-labelledby="statistics-tab">
              <h2 class="mb-5 text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Statistics Content</h2>
              <!-- Statistics data -->
            </div>
          </div>
        </div>
      </div>
    </dashboard-layout>
  </template>
  
  <script setup>
  import DashboardLayout from '@/Layouts/DashboardLayout.vue';
  import { onMounted } from 'vue';
  
  onMounted(() => {
    // Get the last selected tab from localStorage
    const lastSelectedTab = localStorage.getItem('lastSelectedTab') || 'about';
  
    // Function to handle tab switching
    function handleTabClick(event) {
      const targetTab = event.currentTarget.dataset.tab;
  
      // Hide all tab panels
      document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
        panel.classList.add('hidden');
      });
  
      // Show the target panel
      const targetPanel = document.querySelector(`#${targetTab}`);
      if (targetPanel) {
        targetPanel.classList.remove('hidden');
      }
  
      // Remove active class from all tabs
      document.querySelectorAll('[role="tab"]').forEach(tab => {
        tab.classList.remove('text-blue-600');
        tab.classList.remove('dark:text-blue-500');
      });
  
      // Add active class to the clicked tab
      event.currentTarget.classList.add('text-blue-600');
      event.currentTarget.classList.add('dark:text-blue-500');
  
      // Save the selected tab to localStorage
      localStorage.setItem('lastSelectedTab', targetTab);
    }
  
    // Attach click event listeners to all tab buttons
    document.querySelectorAll('[data-tab]').forEach(button => {
      button.addEventListener('click', handleTabClick);
    });
  
    // Set the default tab as active
    const defaultTabButton = document.querySelector(`[data-tab="${lastSelectedTab}"]`);
    if (defaultTabButton) {
      defaultTabButton.click();
    }
  });
  </script>
  