<template>
  <div class="relative w-full">
    <!-- Dropdown Button -->
    <button 
      ref="buttonRef" 
      type="button" 
      @click="open = !open"
      class="w-full flex justify-between items-center 
             mt-1 px-2 py-1.5 sm:px-3 sm:py-2 
             border rounded-md bg-baseColor-50 text-baseColor-800 border-baseColor-200 shadow-sm
             focus:border-brandColor focus:ring-brandColor 
             text-xs sm:text-sm"
    >
      <span class="truncate">{{ selectedName }}</span>
      <ChevronDownIcon 
        class="w-4 h-4 sm:w-5 sm:h-5 transform transition-transform duration-200" 
        :class="{ 'rotate-180': open }" 
      />
    </button>

    <!-- Overlay -->
    <div v-show="open" class="fixed inset-0 z-60" @click="open = false"></div>

    <!-- Dropdown Menu -->
    <Transition 
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div 
        v-show="open" 
        class="absolute z-50 mt-1 w-full rounded-md shadow-lg bg-baseColor-100 text-baseColor-800
               text-xs sm:text-sm"
      >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 max-h-40 sm:max-h-48 overflow-y-auto">

          <!-- Empty Option -->
          <div v-if="allowEmpty"
               @click="selectItem({ id: null, name: '' })"
               class="p-1.5 sm:p-2 cursor-pointer transition text-xs sm:text-sm italic text-baseColor-400 hover:bg-brandColor-400">
            {{ translations.general?.none || '-' }}
          </div>

          <!-- Items -->
          <div v-for="item in items" :key="item.id"
               @click="selectItem(item)"
               class="p-1.5 sm:p-2 cursor-pointer transition hover:bg-brandColor-400 text-xs sm:text-sm">
            {{ item.name }}
          </div>

          <!-- No Data -->
          <p v-if="showNoDataMsg" class="text-xs sm:text-sm italic text-baseColor-400 p-2">
            {{ translations.general?.noData }}
          </p>
        </div>
      </div>
    </Transition>
  </div>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3'
import { ChevronDownIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    modelValue: [String, Number, null], // Selected value
    items: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: ''
    },
    allowEmpty: {   // <-- new prop
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const buttonRef = ref(null);

const page = usePage()
const translations = computed(() => page.props.translations || {}) 

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => {
    if (buttonRef.value?.hasAttribute('autofocus')) {
        buttonRef.value.focus();
    }
    document.addEventListener('keydown', closeOnEscape);
});
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const selectItem = (item) => {
    emit('update:modelValue', item.id); 
    open.value = false;
};

const showNoDataMsg = ref(false);
const selectedName = computed(() => {
    const list = props.items ?? [];
    const text = props.placeholder ?? translations.value.general?.select;
    if (!list.length) {
        showNoDataMsg.value = true;
    }
    return list.find(i => i.id === props.modelValue)?.name || text;
});
</script>
