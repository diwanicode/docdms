<template>
  <div
    class="toggle inline-flex relative items-center"
    :class="disabled ? 'cursor-default opacity-60' : 'cursor-pointer'"
  >
    <button
      type="button"
      class="transition ease-in-out duration-300 w-10 rounded-full focus:outline-none"
      :class="[modelValue ? activeBgColor : inactiveBgColor]"
      @click="!disabled && toggleSwitch()"
    >
      <div
        class="transition ease-in-out duration-300 rounded-full h-5 w-5 shadow"
        :class="[
          modelValue
            ? 'bg-brandColor-600 transform translate-x-full'
            : 'bg-baseColor-300'
        ]"
      ></div>
    </button>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  modelValue: {
    type: [Boolean, Number],
    default: false,
  },
  color: {
    type: String,
    default: "brand",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:modelValue"]);

const colorVariants = {
  brand:
    "bg-brandColor border border-brandColor-600 peer-focus:ring-brandColor-400",
  success:
    "bg-success-600 border border-success-600 peer-focus:ring-success-400",
};

const activeBgColor = computed(() => colorVariants[props.color]);
const inactiveBgColor = computed(
  () => "bg-baseColor-100 border border-baseColor-300 peer-focus:ring-baseColor-300"
);

const toggleSwitch = () => {
  emit("update:modelValue", !props.modelValue);
};
</script>
