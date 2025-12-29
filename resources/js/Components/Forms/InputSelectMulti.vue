<template>
    <div class="relative">
        <!-- Dropdown Button -->
        <button type="button" @click="open = !open"
            class="w-full flex items-center mt-1 p-2 border bg-baseColor-50 text-baseColor-800 border-baseColor-200 shadow-sm focus:border-brandColor focus:ring-brandColor rounded-md">

            <!-- Badges aligned left -->
            <div class="flex flex-wrap gap-1">
                <template v-for="item in selectedNames" :key="item.id">
                <Badge :title="item.name" color="isBrand"/>
                </template>

                <!-- If no items selected -->
                <span v-if="!selectedNames.length" class="text-baseColor-500 text-sm">
                {{ title }}
                </span>
            </div>

            <!-- Right side: arrows -->
            <div class="ml-auto flex items-center">
                <ChevronDoubleDownIcon 
                v-if="open" 
                class="h-5 w-5 text-brandColor-600" 
                :title="translations?.general?.hide" 
                />
                <ChevronDoubleRightIcon 
                v-else 
                class="h-5 w-5 text-brandColor-600" 
                :title="translations?.general?.expand" 
                />
            </div>
        </button>



        <!-- Overlay to Close Dropdown -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

        <!-- Dropdown Menu -->
        <Transition enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95">
            <div v-show="open" class="absolute z-50 mt-1 w-full rounded-md shadow-lg bg-baseColor-100 text-baseColor-800">
                <div class="rounded-md ring-1 ring-baseColor-300 ring-opacity-5 max-h-48 overflow-auto">
                    <div v-for="item in items" :key="item.id"
                        @click="toggleItem(item)"
                        class="flex items-center p-2 cursor-pointer transition duration-200 
                        hover:bg-brandColor-200 active:bg-brandColor-300 focus:bg-brandColor-300">
                         <Checkbox 
                            :name="item.name"  
                            :checked="props.modelValue.some(selected => selected.id === item.id)" 
                            class="mt-1 mr-2"
                            /> 
                        {{ item.name }}
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3'; 
import { ChevronDoubleLeftIcon, ChevronDoubleRightIcon,ChevronDoubleDownIcon,ChevronDownIcon } from '@heroicons/vue/24/solid'; 
import Badge from '@/Components/Forms/Badge.vue';
import Checkbox from '@/Components/Forms/Checkbox.vue';

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
        default: () => [] // Ensures modelValue is always an array
    },
    title:{
        type: String,
        default:'Select'
    },
    items: {
        type: Array,
        required: true,
        default: () => []  
    } // List of dropdown options { id, name }
});

const emit = defineEmits(['update:modelValue']);
const page = usePage();
const translations = computed(() => page.props.translations || {});

const open = ref(false);

// Close on Escape
const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

// Find selected item names
const selectedNames = computed(() => props.modelValue);
// Toggle item selection
const toggleItem = (item) => {
    const selected = [...props.modelValue];
    const index = selected.findIndex(selectedItem => selectedItem.id === item.id);

    if (index === -1) {
        selected.push(item); // Add full object
    } else {
        selected.splice(index, 1); // Remove by id
    }

    emit('update:modelValue', selected);
};

</script>
