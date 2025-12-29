<template>
  <div class="relative w-full">
    <!-- Input field -->
    <div class="relative">
      <input
        type="text"
        :value="formattedDate"
        @click="toggleCalendar"
        :placeholder="translations?.general?.select"
        readonly
        :class="inputClass"
      />

      <!-- Calendar icon -->
      <CalendarDaysIcon
        class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 cursor-pointer transition-colors duration-200"
        :class="iconClass"
        @click="toggleCalendar"
      />
    </div>

    <!-- Calendar popup -->
    <div
      v-if="showCalendar"
      class="absolute mt-2 p-3 rounded-lg shadow-lg z-[9999] w-64"
      :class="calendarClass"
    >
      <!-- Header -->
      <div class="flex justify-between items-center mb-2">
        <button type="button" @click="prevMonth" class="px-2 py-1 text-sm font-bold" :disabled="!canPrevMonth">&lt;</button>
        <span class="font-semibold text-sm">
          {{ currentMonthName }} {{ currentYear }}
        </span>
        <button type="button" @click="nextMonth" class="px-2 py-1 text-sm font-bold" :disabled="!canNextMonth">&gt;</button>
      </div>

      <!-- Week Days -->
      <div class="grid grid-cols-7 text-center text-xs font-semibold mb-1">
        <div v-for="day in weekDays" :key="day.short">{{ day.short }}</div>
      </div>

      <!-- Days -->
      <div class="grid grid-cols-7 text-center text-sm">
        <!-- Empty cells for alignment -->
        <div
          v-for="blank in startDay"
          :key="'blank-' + blank"
          class="text-transparent select-none"
        >
          0
        </div>

        <div
          v-for="day in daysInMonth"
          :key="day"
          class="p-1 rounded-md cursor-pointer text-center transition-colors"
          :class="dayClasses(day)"
          @click="selectDate(day)"
        >
          {{ day }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { CalendarDaysIcon } from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";
import { useDateUtils } from '@/Composables/useDateUtils';
// Props
const props = defineProps({
  modelValue: [Date,String],
  min: String, // min date (yyyy-mm-dd)
  max: String, // max date (yyyy-mm-dd)
  inType: {
    type: String,
    default: "isBaseLight",
  },
  disablePrevious: {
    type: Boolean,
    default: false,
  }
});

const emit = defineEmits(["update:modelValue"]);
const page = usePage();
const translations = computed(() => page.props.translations || {});
const weekDays = computed(() => translations.value.general?.days || []);
const months = computed(() => translations.value.general?.months || []);

const showCalendar = ref(false);
const today = new Date();
today.setHours(0, 0, 0, 0);

const selectedDate = ref(props.modelValue ? new Date(props.modelValue) : null);
const currentMonth = ref(selectedDate.value ? selectedDate.value.getMonth() : today.getMonth());
const currentYear = ref(selectedDate.value ? selectedDate.value.getFullYear() : today.getFullYear());

// Parse min and max dates
const minDate = computed(() => (props.min ? new Date(props.min) : null));
const maxDate = computed(() => (props.max ? new Date(props.max) : null));

// Toggle popup
const toggleCalendar = () => (showCalendar.value = !showCalendar.value);

// Days in month
const daysInMonth = computed(() => new Date(currentYear.value, currentMonth.value + 1, 0).getDate());

// First day of month for grid alignment
const startDay = computed(() => ((new Date(currentYear.value, currentMonth.value, 1).getDay() + 6) % 7));

// Current month name from translations
const currentMonthName = computed(() => months.value[currentMonth.value]?.long || "");

// Formatted input value
  const { formatDate } = useDateUtils();
const formattedDate = computed(() => {
  return selectedDate.value
    ? formatDate(selectedDate.value, 'ddd, D MMM YYYY')
    : '';
});

// Navigation controls
const canPrevMonth = computed(() => {
  if (!minDate.value) return true;
  return new Date(currentYear.value, currentMonth.value, 1) > new Date(minDate.value.getFullYear(), minDate.value.getMonth(), 1);
});

const canNextMonth = computed(() => {
  if (!maxDate.value) return true;
  return new Date(currentYear.value, currentMonth.value, 1) < new Date(maxDate.value.getFullYear(), maxDate.value.getMonth(), 1);
});

const nextMonth = () => {
  if (!canNextMonth.value) return;
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value++;
  } else {
    currentMonth.value++;
  }
};

const prevMonth = () => {
  if (!canPrevMonth.value) return;
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value--;
  } else {
    currentMonth.value--;
  }
};

// Date helpers
const isToday = (day) => new Date(currentYear.value, currentMonth.value, day).getTime() === today.getTime();

// Check if day is within min/max
const isDisabled = (day) => {
  const d = new Date(currentYear.value, currentMonth.value, day);
  if (minDate.value && d < minDate.value) return true;
  if (maxDate.value && d > maxDate.value) return true;
  if (props.disablePrevious) {
    const today = new Date();
    today.setHours(0, 0, 0, 0); // reset time
    if (d < today) return true;
  }

  return false;
};

// Combine classes for each day (DRY)
const dayClasses = (day) => {
  if (isDisabled(day)) return "text-baseColor-300 cursor-not-allowed";
  if (isToday(day)) return "border-b-2 border-brandColor-500 font-bold text-baseColor-800 ";
  if (isSelected(day)) return "bg-brandColor-500 text-brandColor-50 font-bold";
  return "text-baseColor-800 hover:text-brandColor-700 hover:bg-brandColor-100";
};

// Select a date
const selectDate = (day) => {
  if (isDisabled(day)) return;
  selectedDate.value = new Date(currentYear.value, currentMonth.value, day);
  const formatted = formatDate(selectedDate.value, ' YYYY-M-D')

  emit("update:modelValue", formatted);
  showCalendar.value = false;
};

// Check selected date
const isSelected = (day) => {
  const d = selectedDate.value;
  if(d){
    return d.getFullYear() === currentYear.value && d.getMonth() === currentMonth.value && d.getDate() === day;
  }
  return false;
};

// Styles
const inputClass = computed(() => {
  const base = "w-full px-3 py-2 pr-10 rounded-md shadow-sm transition-colors duration-200 focus:outline-none cursor-pointer";
  const styles = {
    isBaseLight: "border border-baseColor-200 bg-baseColor-50 text-baseColor-700 placeholder-baseColor-400 hover:border-baseColor-400 focus:ring-2 focus:ring-brandColor-500 focus:border-brandColor-500",
    isBrandGradient: "border border-brandColor-600/50 bg-brandColor-700/60 text-brandColor-100 focus:border-brandColor-500 focus:ring-1 focus:ring-brandColor-500/30",
    isDark: "border border-slate-700 bg-slate-800 text-white placeholder-slate-400 focus:ring-slate-500",
  };
  return `${base} ${styles[props.inType] || styles.isBaseLight}`;
});

const iconClass = computed(() => {
  const styles = {
    isBaseLight: "text-brandColor-500 hover:text-brandColor-600",
    isBrandGradient: "text-brandColor-50 hover:text-brandColor-300",
    isDark: "text-baseColor-300 hover:text-white",
  };
  return styles[props.inType] || styles.isBaseLight;
});

const calendarClass = computed(() => {
  const styles = {
    isBaseLight: "bg-baseColor-100 border border-baseColor-200 text-baseColor-700",
    isBrandGradient: "bg-brandColor-700/90 text-brandColor-50 border border-brandColor-700/30",
    isDark: "bg-slate-900 text-white border border-slate-700",
  };
  return styles[props.inType] || styles.isBaseLight;
});
</script>
