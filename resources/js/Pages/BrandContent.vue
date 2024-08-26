<template>
    <div>
      <DataTable :items="brands" :columns="columns" @edit-item="openModal" @delete-item="deleteItem">
        <template #actions>
          <!-- <PrimaryButton @click="handleAddNew">Add New</PrimaryButton> -->
          <PrimaryButton @click="openCreateModal">Add New</PrimaryButton>
        </template>
      </DataTable>
  
      <!-- Modal Edit-->
      <transition name="fade">
        <div v-if="isModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" @click="closeModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md" @click.stop>
            <h2 class="text-xl font-semibold mb-4">Edit Brand</h2>
            <form @submit.prevent="updateBrand">
              <div class="mb-4">
                <label for="brand_name" class="block text-sm font-medium text-gray-700">Brand Name</label>
                <input type="text" id="brand_name" v-model="currentBrand.brand_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="mb-4">
                <label for="brand_code" class="block text-sm font-medium text-gray-700">Brand Code</label>
                <textarea id="brand_code" v-model="currentBrand.brand_code" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
              </div>
              <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                <button type="button" @click="closeModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </transition>

      <!-- Modal Create -->
      <transition name="fade">
        <div v-if="isCreateModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" @click="closeCreateModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md" @click.stop>
            <h2 class="text-xl font-semibold mb-4">Create New Brand</h2>
            <form @submit.prevent="createBrand">
              <div class="mb-4">
                <label for="new_brand_name" class="block text-sm font-medium text-gray-700">Brand Name</label>
                <input type="text" id="new_brand_name" v-model="newBrand.brand_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="mb-4">
                <label for="new_brand_code" class="block text-sm font-medium text-gray-700">Brand Code</label>
                <textarea id="new_brand_code" v-model="newBrand.brand_code" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
              </div>
              <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
                <button type="button" @click="closeCreateModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </transition>
    </div>
  </template>
  
  <script setup>
import { defineProps, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import DataTable from '@/Components/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useToast } from 'vue-toastification';

const isModalOpen = ref(false);
const isCreateModalOpen = ref(false);
const currentBrand = ref({ brand_name: '', brand_code: '' });
const newBrand = ref({ brand_name: '', brand_code: '' });
const toast = useToast();
const brands = ref([]);
const columns = ref([
  { key: 'brand_name', label: 'Brand Name' },
  { key: 'brand_code', label: 'Brand Code' }
]);

const fetchBrands = async () => {
  try {
    const response = await axios.get(route('brands.index'));
    brands.value = response.data.brand;
  } catch (error) {
    console.error('Error fetching brands:', error);
  }
};

onMounted(() => {
  fetchBrands();
});

const openModal = (postData) => {
  currentBrand.value = { ...postData };
  isModalOpen.value = true;
};

const closeModal = () => (isModalOpen.value = false);

const openCreateModal = () => {
  newBrand.value = { brand_name: '', brand_code: '' };
  isCreateModalOpen.value = true;
};

const closeCreateModal = () => (isCreateModalOpen.value = false);

const updateBrand = async () => {
  const url = route('brands.update', currentBrand.value.id);
  try {
    const response = await axios.put(url, {
      brand_name: currentBrand.value.brand_name,
      brand_code: currentBrand.value.brand_code,
    });

    const index = brands.value.findIndex(brand => brand.id === response.data.brand.id);
    if (index !== -1) {
      brands.value[index] = response.data.brand;
    }

    closeModal();
    toast.success("Update brand successfully");
  } catch (error) {
    console.error('Error updating brand:', error.response.data);
  }
};

const createBrand = async () => {
  try {
    const response = await axios.post(route('brands.store'), {
      brand_name: newBrand.value.brand_name,
      brand_code: newBrand.value.brand_code,
    });
    brands.value.push(response.data.brand);
    closeCreateModal();
    toast.success("Brand created successfully");
  } catch (error) {
    console.error('Error creating brand:', error.response.data);
  }
};

const deleteItem = async (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    try {
      await axios.delete(`/brands/${id}`);
      brands.value = brands.value.filter(item => item.id !== id);
      toast.success("Item deleted successfully");
    } catch (error) {
      console.error('Failed to delete item:', error);
    }
  }
};
</script>
  
  <style scoped>
  /* Tambahkan CSS khusus untuk halaman jika diperlukan */
  </style>
