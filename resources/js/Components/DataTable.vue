<template>
  <div class="p-6">
    <!-- Pencarian dan Filter -->
    <div class="mb-4 flex justify-between items-center">
      <div>
        <slot name="actions"></slot>
      </div>
      <div>
        <label for="entries-per-page" class="mr-2">Entries per page:</label>
        <select
          id="entries-per-page"
          v-model="itemsPerPage"
          class="border border-gray-300 px-4 py-2 rounded"
        >
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
      <div>
        <button @click="exportToExcel" class="me-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Export to Excel</button>
        <button @click="exportToPDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Export to PDF</button>
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
    <!-- <div class="mt-4 flex justify-between items-center">
      <div class="text-sm text-gray-600">
        Showing {{ startItem }} to {{ endItem }} of {{ totalItems }} items
      </div>

      <nav aria-label="Pagination" class="flex items-center space-x-1">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 disabled:opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <div class="flex items-center space-x-1">
          <button
            v-for="page in pageNumbers"
            :key="page"
            @click="currentPage = page"
            :class="['px-3 py-1 text-sm font-medium border rounded-md', {
              'bg-blue-500 text-white': page === currentPage,
              'bg-white text-gray-500 hover:bg-gray-50': page !== currentPage
            }]"
          >
            {{ page }}
          </button>
        </div>

        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 disabled:opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </nav>
    </div> -->

    <div class="mt-4 flex justify-between items-center">
      <div class="text-sm text-gray-600">
        Showing {{ startItem }} to {{ endItem }} of {{ totalItems }} items
      </div>

      <nav aria-label="Pagination" class="flex items-center space-x-1">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 disabled:opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button
          v-if="shouldShowFirstEllipsis"
          class="px-3 py-1 text-sm font-medium border rounded-md text-gray-500 bg-white hover:bg-gray-50"
        >
          ...
        </button>

        <button
          v-for="page in paginatedPageNumbers"
          :key="page"
          @click="currentPage = page"
          :class="['px-3 py-1 text-sm font-medium border rounded-md', {
            'bg-blue-500 text-white': page === currentPage,
            'bg-white text-gray-500 hover:bg-gray-50': page !== currentPage
          }]"
        >
          {{ page }}
        </button>

        <button
          v-if="shouldShowLastEllipsis"
          class="px-3 py-1 text-sm font-medium border rounded-md text-gray-500 bg-white hover:bg-gray-50"
        >
          ...
        </button>

        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 disabled:opacity-50"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </nav>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, defineEmits, watch } from 'vue';
import { saveAs } from 'file-saver';
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  },
  columns: {
    type: Array,
    default: () => []
  }
});

const items = ref(props.items);

watch(() => props.items, (newItems) => {
  items.value = newItems;
  updatePagination(); // Update pagination when items change
});

const emit = defineEmits(['edit-item', 'delete-item']);

const MAX_VISIBLE_PAGES = 5;

const paginatedPageNumbers = computed(() => {
  const pages = [];
  const startPage = Math.max(currentPage.value - Math.floor(MAX_VISIBLE_PAGES / 2), 1);
  const endPage = Math.min(startPage + MAX_VISIBLE_PAGES - 1, totalPages.value);

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  return pages;
});

const shouldShowFirstEllipsis = computed(() => {
  return paginatedPageNumbers.value[0] > 1;
});

const shouldShowLastEllipsis = computed(() => {
  return paginatedPageNumbers.value[paginatedPageNumbers.value.length - 1] < totalPages.value;
});

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortColumn = ref('');
const sortDirection = ref('asc');

const columnNames = computed(() => {
  if (props.columns.length > 0) {
    return props.columns.map(column => column.key);
  }
  return [];
});

function sortBy(column) {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
}

const sortedItems = computed(() => {
  let sorted = Array.isArray(items.value) ? [...items.value] : [];
  if (sortColumn.value) {
    sorted.sort((a, b) => {
      const aValue = a[sortColumn.value]?.toString().toLowerCase() || '';
      const bValue = b[sortColumn.value]?.toString().toLowerCase() || '';

      if (aValue < bValue) return sortDirection.value === 'asc' ? -1 : 1;
      if (aValue > bValue) return sortDirection.value === 'asc' ? 1 : -1;
      return 0;
    });
  }
  return sorted;
});

const filteredItems = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return Array.isArray(sortedItems.value) ? sortedItems.value.filter(item =>
    columnNames.value.some(column =>
      item[column]?.toString().toLowerCase().includes(query)
    )
  ) : [];
});

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * parseInt(itemsPerPage.value, 10);
  const end = start + parseInt(itemsPerPage.value, 10);
  return filteredItems.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage.value));

const pageNumbers = computed(() => {
  const pages = [];
  for (let i = 1; i <= totalPages.value; i++) {
    pages.push(i);
  }
  return pages;
});

const totalItems = computed(() => filteredItems.value.length);
const startItem = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1);
const endItem = computed(() => Math.min(currentPage.value * itemsPerPage.value, totalItems.value));

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}

function updatePagination() {
  const totalItemsCount = filteredItems.value.length;
  const totalPageCount = Math.ceil(totalItemsCount / itemsPerPage.value);

  if (currentPage.value > totalPageCount) {
    currentPage.value = totalPageCount;
  }

  // Mengupdate informasi tampilan
  startItem.value = (currentPage.value - 1) * itemsPerPage.value + 1;
  endItem.value = Math.min(currentPage.value * itemsPerPage.value, totalItemsCount);
}

watch(filteredItems, updatePagination);
watch(() => itemsPerPage.value, () => {
});

function exportToExcel() {
  const data = paginatedItems.value.map(item => {
    const row = {};
    props.columns.forEach(col => {
      row[col.label] = item[col.key];
    });
    return row;
  });

  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
  saveAs(new Blob([wbout], { type: 'application/octet-stream' }), 'data.xlsx');
}

const exportToPDF = async () => {
  const element = document.getElementById('tableToExport');
  html2canvas(element).then(canvas => {
    const imgData = canvas.toDataURL('image/png');
    const pdf = new jsPDF('p', 'mm', 'a4');
    const imgWidth = 210;
    const imgHeight = canvas.height * imgWidth / canvas.width;
    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
    pdf.save('data.pdf');
  });
}
</script>

<style scoped>
/* Add any custom styles if necessary */
</style>
