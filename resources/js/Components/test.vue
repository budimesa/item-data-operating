<template>
  <div class="p-6">
    <!-- Pencarian dan Filter -->
    <div class="mb-4 flex justify-between items-center">
      <div>
        <slot name="actions"></slot>
        <SecondaryButton @click="exportToExcel" class="ms-2">Export to Excel</SecondaryButton>
      </div>
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search..."
        class="border border-gray-300 px-4 py-2 rounded w-1/4"
      />
    </div>

    <!-- Tabel -->
    <div id="tableToExport" class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-md">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
            <th v-for="column in columns" :key="column.key" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer" @click="sortBy(column.key)">
              {{ column.label }}
              <svg v-if="sortColumn === column.key" :class="{'rotate-180': sortDirection === 'desc'}" class="inline h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider sticky right-0 bg-gray-100">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="(item, index) in paginatedItems" :key="item.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 + startItem - 1 }}</td>
            <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item[column.key] }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium sticky right-0 bg-white border-r border-gray-200">
              <div class="flex items-center space-x-4">
                <router-link
                  @click.native.prevent="$emit('edit-item', item)"
                  class="flex items-center text-blue-500 hover:text-blue-700"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>
                </router-link>
                <button
                  @click="() => $emit('delete-item', item.id)"
                  class="flex items-center text-red-500 hover:text-red-700 bg-transparent border-none"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-between items-center">
      <div class="text-sm text-gray-600">
        Showing {{ startItem }} to {{ endItem }} of {{ totalItems }} items
      </div>

      <nav aria-label="Pagination" class="flex items-center space-x-1">
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 disabled:opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button
          v-if="shouldShowFirstEllipsis"
          class="px-3 py-1 text-sm font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50"
        >
          ...
        </button>

        <button
          v-for="page in paginatedPageNumbers"
          :key="page"
          @click="changePage(page)"
          :class="['px-3 py-1 text-sm font-medium rounded-md', {
            'bg-gray-800 text-white': page === currentPage,
            'bg-white text-gray-500 hover:bg-gray-50': page !== currentPage
          }]"
        >
          {{ page }}
        </button>

        <button
          v-if="shouldShowLastEllipsis"
          class="px-3 py-1 text-sm font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50"
        >
          ...
        </button>

        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 disabled:opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <div>
          <select
            id="entries-per-page"
            v-model="itemsPerPage"
            class="border border-gray-300 px-4 py-1 rounded"
            @change="updateItemsPerPage"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineEmits, watch } from 'vue';
import { saveAs } from 'file-saver';
import * as XLSX from 'xlsx';

const props = defineProps({
  columns: Array,
  items: Array,
  totalItems: Number,
  itemsPerPage: Number,
  currentPage: Number,
});

const emit = defineEmits(['edit-item', 'delete-item']);

const searchQuery = ref('');
const currentPage = ref(props.currentPage);
const itemsPerPage = ref(props.itemsPerPage);

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  return props.items
    .filter(item => {
      return Object.values(item).some(value =>
        value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
      );
    })
    .slice(start, start + itemsPerPage.value);
});

const totalPages = computed(() => Math.ceil(props.totalItems / itemsPerPage.value));

const paginatedPageNumbers = computed(() => {
  const pageNumbers = [];
  for (let i = 1; i <= totalPages.value; i++) {
    pageNumbers.push(i);
  }
  return pageNumbers;
});

const startItem = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1);
const endItem = computed(() => Math.min(currentPage.value * itemsPerPage.value, props.totalItems));

const shouldShowFirstEllipsis = computed(() => paginatedPageNumbers.value.length > 5 && currentPage.value > 4);
const shouldShowLastEllipsis = computed(() => paginatedPageNumbers.value.length > 5 && currentPage.value < totalPages.value - 3);

function changePage(page) {
  if (page < 1 || page > totalPages.value) return;
  emit('page-changed', page);
}

function updateItemsPerPage(event) {
  itemsPerPage.value = parseInt(event.target.value);
  emit('items-per-page-changed', itemsPerPage.value);
}

function exportToExcel() {
  const ws = XLSX.utils.json_to_sheet(props.items);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

  function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
    return buf;
  }

  saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'data.xlsx');
}

watch(
  () => props.currentPage,
  (newPage) => {
    currentPage.value = newPage;
  }
);

watch(
  () => props.itemsPerPage,
  (newItemsPerPage) => {
    itemsPerPage.value = newItemsPerPage;
  }
);
</script>
