<template>
  <div>
    <DataTable
      :items="itemDatas"
      :columns="columns"
      :totalPages="totalPages"
      :totalItems="totalItems"
      :currentPage="currentPage"
      @update:pageChange="handlePageChange"
      @update:itemsPerPage="handleItemsPerPageChange"
    >
      <template #actions>
        <PrimaryButton @click="createItem">Add New</PrimaryButton>
      </template>
    </DataTable>
  </div>
</template>
  
  <script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from '@/Components/DataTable2.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const itemDatas = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const itemsPerPage = ref(10); // Default items per page

const columns = ref([
  { key: 'qty_safty', label: 'Qty Safety' },
  { key: 'safety_m', label: 'Safety M' },

]);

const fetchData = async (page = 1, perPage = 10) => {
  try {
    const response = await axios.get(route('item-datas.index', { page, perPage }));
    itemDatas.value = response.data.data; // Data item untuk halaman ini
    totalPages.value = response.data.last_page; // Total halaman
    totalItems.value = response.data.total; // Total item
  } catch (error) {
    console.error('Error fetching Item Data:', error);
  }
};


const handlePageChange = (page) => {
  if (page > 0 && page <= totalPages.value) {
    currentPage.value = page;
    fetchData(currentPage.value, itemsPerPage.value); 
  }
};

const handleItemsPerPageChange = (newItemsPerPage) => {
  itemsPerPage.value = newItemsPerPage;
  currentPage.value = 1; // Reset ke halaman 1 saat mengubah jumlah item per halaman
  fetchData(currentPage.value, itemsPerPage.value); // Fetch data dengan jumlah item baru
};

onMounted(() => {
  fetchData(currentPage.value, itemsPerPage.value);
});
</script>
  
  <style scoped>
  /* Tambahkan CSS khusus untuk halaman jika diperlukan */
  </style>
