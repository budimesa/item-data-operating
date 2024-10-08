<template>
    <div>
      <DataTable :items="brands" :columns="columns" @edit-item="editItem" @delete-item="deleteConfirmModal">
        <template #actions>
          <PrimaryButton @click="createItem">Add New</PrimaryButton>
        </template>
      </DataTable>
  
      <!-- Modal Edit-->
      <transition name="fade">
        <div v-if="isEditModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" @click="closeEditModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md" @click.stop>
            <h2 class="text-xl font-semibold mb-4">Edit Brand</h2>
            <form @submit.prevent="update">
              <div class="mb-4">
                <label for="brand_name" class="block text-sm font-medium text-gray-700">Brand Name</label>
                <input type="text" id="brand_name" v-model="currentBrand.brand_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="mb-4">
                <label for="brand_code" class="block text-sm font-medium text-gray-700">Brand Code</label>
                <input type="text" id="brand_code" v-model="currentBrand.brand_code" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></input type="text">
              </div>
              <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                <button type="button" @click="closeEditModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
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
            <form @submit.prevent="create">
              <div class="mb-4">
                <label for="new_brand_name" class="block text-sm font-medium text-gray-700">Brand Name</label>
                <input type="text" id="new_brand_name" v-model="newBrand.brand_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="mb-4">
                <label for="new_brand_code" class="block text-sm font-medium text-gray-700">Brand Code</label>
                <input type="text" id="new_brand_code" v-model="newBrand.brand_code" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></input type="text">
              </div>
              <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
                <button type="button" @click="closeCreateModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </transition>
      <DeleteConfirm v-model:modelValue="isDeleteModalOpen" @confirm="deleteItem" />
    </div>
  </template>
  
  <script setup>
import { defineProps, ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from '@/Components/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useToast } from 'vue-toastification';
import DeleteConfirm from '@/Components/DeleteConfirm.vue';

const isEditModalOpen = ref(false);
const isCreateModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const currentBrand = ref({ brand_name: '', brand_code: '' });
const newBrand = ref({ brand_name: '', brand_code: '' });
const itemToDelete = ref(null);
const toast = useToast();
const brands = ref([]);
const columns = ref([
  { key: 'brand_name', label: 'Brand Name' },
  { key: 'brand_code', label: 'Brand Code' }
]);

const deleteConfirmModal = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const fetchData = async () => {
  try {
    const response = await axios.get(route('brands.index'));
    brands.value = response.data.brands;
  } catch (error) {
    console.error('Error fetching brands:', error);
  }
};

onMounted(() => {
  fetchData();
});

const editItem = (data) => {
  currentBrand.value = { ...data };
  isEditModalOpen.value = true;
};

const closeEditModal = () => (isEditModalOpen.value = false);

const createItem = () => {
  newBrand.value = { brand_name: '', brand_code: '' };
  isCreateModalOpen.value = true;
};

const closeCreateModal = () => (isCreateModalOpen.value = false);

const update = async () => {
  const url = route('brands.update', currentBrand.value.id);
  try {
    const response = await axios.put(url, {
      brand_name: currentBrand.value.brand_name,
      brand_code: currentBrand.value.brand_code,
    });

    await fetchData(); 
    closeEditModal();
    toast.success("Update brand successfully");
  } catch (error) {
    console.error('Error updating brand:', error.response.data);
  }
};

const create = async () => {
  try {
    const response = await axios.post(route('brands.store'), {
      brand_name: newBrand.value.brand_name,
      brand_code: newBrand.value.brand_code,
    });
    await fetchData()
    closeCreateModal();
    toast.success("Brand created successfully");
  } catch (error) {
    console.error('Error creating brand:', error.response.data);
  }
};

const deleteItem = async () => {
  if (itemToDelete.value !== null) {
    try {
      await axios.delete(`/brands/${itemToDelete.value}`);
      await fetchData(); 
      toast.success("Item deleted successfully");
    } catch (error) {
      console.error('Failed to delete item:', error);
    }
  }
  isModalOpen.value = false;
  itemToDelete.value = null;
};
</script>
  
  <style scoped>
  /* Tambahkan CSS khusus untuk halaman jika diperlukan */
  </style>
