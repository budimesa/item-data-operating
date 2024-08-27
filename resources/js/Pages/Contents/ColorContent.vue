<template>
    <div>
      <DataTable :items="colors" :columns="columns" @edit-item="editItem" @delete-item="deleteItem">
        <template #actions>
          <!-- <PrimaryButton @click="handleAddNew">Add New</PrimaryButton> -->
          <PrimaryButton @click="openCreateModal">Add New</PrimaryButton>
        </template>
      </DataTable>
  
      <!-- Modal Edit-->
      <transition name="fade">
        <div v-if="isModalEditOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" @click="closeEditModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md" @click.stop>
            <h2 class="text-xl font-semibold mb-4">Edit Color</h2>
            <form @submit.prevent="update">
              <div class="mb-4">
                <label for="color_name" class="block text-sm font-medium text-gray-700">Color Name</label>
                <input type="text" id="color_name" v-model="currentColor.color_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="mb-4">
                <label for="color_code" class="block text-sm font-medium text-gray-700">Color Code</label>
                <textarea id="color_code" v-model="currentColor.color_code" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
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
            <h2 class="text-xl font-semibold mb-4">Create New Color</h2>
            <form @submit.prevent="create">
              <div class="mb-4">
                <label for="new_color_name" class="block text-sm font-medium text-gray-700">Color Name</label>
                <input type="text" id="new_color_name" v-model="newColor.color_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
              </div>
              <div class="mb-4">
                <label for="new_color_code" class="block text-sm font-medium text-gray-700">Color Code</label>
                <textarea id="new_color_code" v-model="newColor.color_code" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
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
import axios from 'axios';
import DataTable from '@/Components/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useToast } from 'vue-toastification';

const isModalEditOpen = ref(false);
const isCreateModalOpen = ref(false);
const currentColor = ref({ color_name: '', color_code: '' });
const newColor = ref({ color_name: '', color_code: '' });
const toast = useToast();
const colors = ref([]);
const columns = ref([
  { key: 'color_name', label: 'Color Name' },
  { key: 'color_code', label: 'Color Code' }
]);

const fetchData = async () => {
  try {
    const response = await axios.get(route('colors.index'));
    colors.value = response.data.colors;
  } catch (error) {
    console.error('Error fetching colors:', error);
  }
};

onMounted(() => {
  fetchData();
});

const editItem = (data) => {
  currentColor.value = { ...data };
  isModalEditOpen.value = true;
};

const closeEditModal = () => (isModalEditOpen.value = false);

const openCreateModal = () => {
  newColor.value = { color_name: '', color_code: '' };
  isCreateModalOpen.value = true;
};

const closeCreateModal = () => (isCreateModalOpen.value = false);

const update = async () => {
  const url = route('colors.update', currentColor.value.id);
  try {
    const response = await axios.put(url, {
      color_name: currentColor.value.color_name,
      color_code: currentColor.value.color_code,
    });

    await fetchData(); 
    closeEditModal();
    toast.success("Update Color successfully");
  } catch (error) {
    console.error('Error updating Color:', error.response.data);
  }
};

const create = async () => {
  try {
    const response = await axios.post(route('colors.store'), {
      color_name: newColor.value.color_name,
      color_code: newColor.value.color_code,
    });
    await fetchData()
    closeCreateModal();
    toast.success("Color created successfully");
  } catch (error) {
    console.error('Error creating Color:', error.response.data);
  }
};

const deleteItem = async (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    try {
      await axios.delete(`/colors/${id}`);
      await fetchData(); 
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
