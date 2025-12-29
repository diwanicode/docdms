<script setup>
import { onMounted,computed, nextTick, ref, watch } from 'vue';
import { EnvelopeIcon,EyeIcon,EyeSlashIcon,MagnifyingGlassIcon} from '@heroicons/vue/24/solid';
const props = defineProps({
  modelValue: {
    type: [String, Number, null], 
    required: true,
    default: null,
    validator: (value) => {
    return value === null || typeof value === 'string' || typeof value === 'number'
  }
  },
  hasCurrency: { type: Boolean, default: false },
  currency: { type: String, default: '' },
  currencyOptions: { type: Array, default: () => ['KM', '$', '£', '€'] },
  type: {
    type: String,
    default: 'text',
  },
  description: {
    type: String,
    default: null,
  },
  placeholder: {
    type: String,
    default: ''
  },
  txtIcon: { type: String, default: '' },
  iconSize: {
    type: String,
    default: 'md',
    validator: value => ['xs', 'sm', 'md', 'lg'].includes(value),
  },
});

const emit = defineEmits(['update:modelValue', 'update:currency']);

const input = ref(null);
const iconSizeClass = computed(() => ({
  sm: 'w-4 h-4',
  md: 'w-5 h-5',
  lg: 'w-6 h-6',
}[props.iconSize]));
const iconComponent = computed(() => {
  const icons = {
    EnvelopeIcon,EyeIcon,EyeSlashIcon,MagnifyingGlassIcon
  };
  return icons[props.txtIcon] || null;
});

const adjustHeight = () => {
  if (props.type === 'textarea') {
    input.value.style.height = 'auto';
    nextTick(() => {
      const contentHeight = input.value.scrollHeight;
      const minHeight = input.value.dataset.minHeight || 40;
      input.value.style.height = `${Math.max(contentHeight, minHeight)}px`;
    });
  }
};

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }

  if (props.type === 'textarea') {
    input.value.dataset.minHeight = '40';
    input.value.style.minHeight = `${input.value.dataset.minHeight}px`;
  }

  adjustHeight();
  input.value.addEventListener('input', adjustHeight);
});

watch(() => props.modelValue, adjustHeight);

const updateValue = (event) => {
  let value = event.target.value;

  if (props.type === 'number') {
    // Remove non-numeric characters except dot (for decimals)
    value = value.replace(/[^0-9.]/g, '');

    // Prevent multiple dots
    if ((value.match(/\./g) || []).length > 1) {
      value = value.slice(0, -1);
    }
  }

  emit('update:modelValue', value);
};
</script>

<template>
  <div class="relative w-full">
    <component
      :is="props.type === 'textarea' ? 'textarea' : 'input'"
      v-bind="$attrs"
      class="mt-1 block w-full bg-baseColor-50 rounded-md border-baseColor-200 text-baseColor-800 shadow-sm 
            focus:border-brandColor-500 focus:ring-brandColor-500 resize-none pr-10
            text-sm leading-tight py-2
            max-h-32 overflow-y-auto
            sm:text-base sm:leading-normal sm:py-2.5 sm:max-h-none"
      :value="modelValue"
      @input="updateValue"
      ref="input"
      rows="1"
      :type="props.type"
      :inputmode="props.type === 'number' ? 'numeric' : undefined"
      :step="props.type === 'number' ? 'any' : undefined"
      :style="props.type === 'textarea' ? '' : 'appearance: none;'"
      :placeholder="placeholder"
    />
    <span 
      v-if="description" 
      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-baseColor-600"
    >
      {{ description }}
    </span>
    <span v-if="hasCurrency">
      <select  v-if="props.currencyOptions.length"
        v-model="props.currency"
        @change="event => emit('update:currency', event.target.value)"
        class="absolute right-1 top-1/2 transform -translate-y-1/2 text-baseColor-600 bg-baseColor-200 rounded-md border-l-1 border-baseColor-300"
      >
        <option v-for="c in props.currencyOptions" :key="c" :value="c" class="border-baseColor-200 bg-baseColor-100">
          {{ c }}
        </option>
      </select>
    </span>
    <button v-if="txtIcon"
          type="button"
           @click="emit('icon-click')" 
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-brandColor-600">
      <component :is="iconComponent" :class="['font-extrabold', iconSizeClass]" />
    </button>
  </div>
</template>
