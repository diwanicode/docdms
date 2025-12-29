<template>
 <Card card-type="isDefaultNoHover" class="mt-4">
    <!-- Header -->
   <div class="px-6 py-4 border-b border-baseColor-200 bg-baseColor-50">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Left: Title + Desc -->
        <div>
          <h3 class="text-lg font-bold text-brandColor-900">{{ title }}</h3>
          <p v-if="desc !== ''" class="text-sm text-baseColor-500 mt-1">
            {{ desc }}
          </p>
        </div>

        <!-- Right: Search + Actions -->
        <div class="flex flex-col sm:flex-row items-center gap-3"> 
          <div v-if="searchable" class="relative">
             <TextInput
                  class="h-10 pb-2"
                  type="text"
                  txt-icon="MagnifyingGlassIcon" 
                  :placeholder="translations.general.search +'...'"
                  v-model="searchQuery"                 
                  autofocus                    
              />
          </div>

          <!-- Actions slot -->
          <slot name="actions"></slot>
        </div>
      </div>
    </div>


    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-baseColor-200">
        <!-- Table Header -->
        <thead class="bg-brandColor-100">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              @click="column.sortable ? sort(column.key) : null"
              class="px-6 py-3 text-left text-xs font-medium text-brandColor-800 uppercase tracking-wider"
              :class="{ 'cursor-pointer hover:bg-baseColor-100': column.sortable }"
            >
              <div class="flex items-center space-x-1">
                <span>{{ column.label }}</span>
                <div v-if="column.sortable" class="flex flex-col">
                  <ChevronUpIcon 
                    :class="[
                      'w-3 h-3',
                      sortKey === column.key && sortOrder === 'asc' ? 'text-brandColor-600' : 'text-brandColor-800'
                    ]" 
                  />
                  <ChevronDownIcon 
                    :class="[
                      'w-3 h-3 -mt-1',
                      sortKey === column.key && sortOrder === 'desc' ? 'text-brandColor-600' : 'text-brandColor-800'
                    ]" 
                  />
                </div>
              </div>
            </th>
            <th v-if="hasAction" class="bg-brandColor-100 px-6 py-3 text-left text-xs font-medium text-brandColor-800 uppercase tracking-wider">
              <!-- {{ translations.general.actions }} -->
            </th>

          </tr>
        </thead>

        <!-- Table Body -->
        <tbody class="bg-brandColor-50/20 divide-y divide-baseColor-200">
          <tr
            v-for="(item, index) in paginatedData"
            :key="item.id || index"
            class="even:bg-brandColor-50 hover:bg-baseColor-50 transition-colors duration-200"
          >
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-4 whitespace-nowrap text-sm"
              :class="column.class || 'text-baseColor-900'"
            >
              <slot :name="`cell-${column.key}`" :item="item" :value="getValue(item, column.key)">
                {{ formatValue(getValue(item, column.key), column.format) }}
              </slot>
            </td>
            <!-- actions column -->
            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
              <slot name="row-actions" :item="item" />
            </td>
          </tr>

          <!-- Empty state -->
          <tr v-if="paginatedData.length === 0">
            <td :colspan="columns.length" class="px-6 py-12 text-center text-baseColor-500">
              <div class="flex flex-col items-center">
                <slot name="empty">
                  <div class="w-12 h-12 bg-baseColor-100 rounded-full flex items-center justify-center mb-4">
                    <DocumentIcon class="w-6 h-6 text-baseColor-400" />
                  </div>
                  <p class="text-lg font-medium text-baseColor-900 mb-2">{{ translations.general.noData }}</p>
                  <p class="text-baseColor-500">{{ searchQuery ?  translations.general.noDataSearch :    translations.general.noDataDesc }}</p>
                </slot>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
  <div 
  v-if="showPagination && totalPages > 1" 
  class="px-6 py-4 border-t border-baseColor-200 bg-baseColor-50"
>
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    
    <!-- Left: results info -->
    <div class="text-sm text-baseColor-700">
      {{ translations.general.showing }} {{ startIndex + 1 }} - {{ Math.min(endIndex, filteredData.length) }}
      {{ translations.general.of }} {{ filteredData.length }} {{ translations.general.results }}
    </div>
    
    <!-- Right: pagination controls -->
    <div class="flex flex-wrap sm:flex-nowrap items-center space-x-2">
      <!-- Previous button -->
      <button
        @click="currentPage = Math.max(1, currentPage - 1)"
        :disabled="currentPage === 1"
        class="px-1 py-1 text-sm border border-baseColor-300 rounded-md hover:bg-baseColor-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
       <ChevronDoubleLeftIcon class="h-5 w-5" :title="translations?.general?.previous" /> 
      </button>
      
      <!-- Page buttons -->
      <div class="flex items-center flex-wrap sm:flex-nowrap space-x-1">
        <button
          v-for="page in visiblePages"
          :key="page"
          @click="currentPage = page"
          :class="[
            'px-3 py-1 text-sm border rounded-md',
            page === currentPage 
              ? 'bg-brandColor-600 text-brandColor-50 border-brandColor-600' 
              : 'border-baseColor-300 hover:bg-baseColor-100'
          ]"
        >
          {{ page }}
        </button>
      </div>
      
      <!-- Next button -->
      <button
        @click="currentPage = Math.min(totalPages, currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="px-1 py-1 text-sm border border-baseColor-300 rounded-md hover:bg-baseColor-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
         <ChevronDoubleRightIcon class="h-5 w-5" :title="translations?.general?.next" />
      </button>
    </div>
  </div>
</div>

  </Card>
</template>

<script setup :nonce="$page.props.cspNonce">
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Card from '@/Components/Core/Card.vue';
  import TextInput from '@/Components/Forms/TextInput.vue';
import { 
  ChevronUpIcon, 
  ChevronDownIcon,
  DocumentIcon 
} from '@heroicons/vue/24/outline';
import { ChevronDoubleRightIcon,ChevronDoubleLeftIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  desc:{
    type: String,
    default: '',
  },
  data: {
    type: Array,
    required: true
  },
  columns: {
    type: Array,
    required: true
  },
  searchable: {
    type: Boolean,
    default: true
  },
  showPagination: {
    type: Boolean,
    default: true
  },
  itemsPerPage: {
    type: Number,
    default: 10
  },
  hasAction:{
    type: Boolean,
    default: false
  }
});

const page = usePage();
const translations = computed(() => page.props.translations || {}); 

const searchQuery = ref('');
const sortKey = ref('');
const sortOrder = ref('asc');
const currentPage = ref(1);

// Computed properties
const filteredData = computed(() => {
  let filtered = props.data;
  
  // Apply search filter
  if (searchQuery.value) {
    filtered = filtered.filter(item =>
      props.columns.some(column => {
        const value = getValue(item, column.key);
        return String(value).toLowerCase().includes(searchQuery.value.toLowerCase());
      })
    );
  }
  
  // Apply sorting
  if (sortKey.value) {
    filtered = [...filtered].sort((a, b) => {
      const aVal = getValue(a, sortKey.value);
      const bVal = getValue(b, sortKey.value);
      
      if (aVal < bVal) return sortOrder.value === 'asc' ? -1 : 1;
      if (aVal > bVal) return sortOrder.value === 'asc' ? 1 : -1;
      return 0;
    });
  }
  
  return filtered;
});

const totalPages = computed(() => {
  return Math.ceil(filteredData.value.length / props.itemsPerPage);
});

const startIndex = computed(() => {
  return (currentPage.value - 1) * props.itemsPerPage;
});

const endIndex = computed(() => {
  return startIndex.value + props.itemsPerPage;
});

const paginatedData = computed(() => {
  return filteredData.value.slice(startIndex.value, endIndex.value);
});

const visiblePages = computed(() => {
  const pages = [];
  const start = Math.max(1, currentPage.value - 2);
  const end = Math.min(totalPages.value, start + 3);
  
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  
  return pages;
});

// Methods
const getValue = (item, key) => {
  if (!key || typeof key !== 'string') return null;  
  return key.split('.').reduce((obj, k) => obj?.[k], item);
};

const formatValue = (value, format) => {
  if (!format) return value;
  
  switch (format) {
    case 'currency':
      return new Intl.NumberFormat('en-US', { 
        style: 'currency', 
        currency: 'USD' 
      }).format(value);
    case 'number':
      return new Intl.NumberFormat('en-US').format(value);
    case 'date':
      return new Date(value).toLocaleDateString();
    case 'datetime':
      return new Date(value).toLocaleString();
    default:
      return value;
  }
};

const sort = (key) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortOrder.value = 'asc';
  }
  currentPage.value = 1; // Reset to first page when sorting
};
</script>
<style :nonce="$page.props.cspNonce">
/* For modern browsers */
::-webkit-scrollbar {
  height: 8px; /* horizontal scrollbar height */
}

::-webkit-scrollbar-track {
  background: #eef4ff; /* brandColor-50 */
}

::-webkit-scrollbar-thumb {
  background: #b8cfff; /* brandColor-200 */
  border-radius: 9999px;
}

::-webkit-scrollbar-thumb:hover {
  background: #2f66ff; /* brandColor-500 */
}

/* Firefox */
* {
  scrollbar-width: thin;
   scrollbar-color: #2f66ff #eef4ff;  /* brandColor-500 */
}

</style>
