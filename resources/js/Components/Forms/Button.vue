<template>
  <button
    :type="type"
    :class="computedClasses"
    :disabled="disabled || loading"
    :title="title"
    @click="$emit('click', $event)"
  >
    <img v-if="btnImage" :src="btnImage" :alt="title" class="w-8 h-6 rounded-sm" />
     <span v-if="loading">
      <ArrowPathIcon class="w-5 h-5 animate-spin" />
    </span>
    <span v-if="btnIcon && !loading">
      <component :is="iconComponent" :class="['font-extrabold', iconSizeClass]" />
    </span>
    <span v-if="text">{{ text }}</span>
  </button>
</template>

<script setup>
import { computed } from 'vue';
import {
  PencilSquareIcon, AcademicCapIcon, BriefcaseIcon, TrashIcon, PlusCircleIcon,
  ChevronDoubleRightIcon, ChevronDoubleLeftIcon, XMarkIcon,XCircleIcon,
  EllipsisHorizontalIcon, SparklesIcon,UserGroupIcon,
  AdjustmentsHorizontalIcon, BarsArrowDownIcon, BarsArrowUpIcon,ListBulletIcon,
  ViewfinderCircleIcon,LinkIcon,CursorArrowRaysIcon, UsersIcon,
   ArrowUturnLeftIcon,ArrowPathIcon,PaperAirplaneIcon, MagnifyingGlassIcon
} from '@heroicons/vue/24/solid';

const props = defineProps({
  type: { type: String, default: 'button' },
  btnImage: { type: String, default: '' },
  btnType: {
    type: String,
    default: '',
    validator: value => [
      'isPrimary', 'isTextOnly', 'isBrandTextOnly', 'isSecondary', 'isBlur',
      'isBrandLight','isBrandLightSec','isBrandLightDisabled', 'isDefault', 'isRemove', 'isBrand',
      'isBrandSecondary', 'isBrandSecondaryJoined', 'isBrandJoined','isNotAllowed','isNotValid',
      'isGradientBrand','isBaseLight',
      'isSecBrand','isSecBrandFill100','isSecBrandBorder100','isSecBrand200','isSecBrandTextOnly','isGradientSecBrand'
    ].includes(value),
  },
  disabled: { type: Boolean, default: false },
  size: {
    type: String,
    default: 'md',
    validator: value => ['xs', 'sm', 'md', 'lg'].includes(value),
  },
  title: { type: [String, Number], default: '' },
  loading: { type: Boolean, default: false }, 
  text: { type: [String, Number], default: '' },
  btnIcon: { type: String, default: '' },
  btnRounded: {
    type: String,
    default: 'lg',
    validator: value => ['xs', 'sm', 'md', 'lg', 'l-lg', 'r-lg', 'full'].includes(value),
  },
});

const emit = defineEmits(['click']);

const computedClasses = computed(() => {
  const base = ['transition duration-200 ease-in-out flex items-center justify-center gap-2'];
  const size = props.size === 'xs'
    ? 'px-1 py-1 text-[10px]'
    : props.size === 'sm'
      ? 'px-2 py-1 text-sm'
      : props.size === 'lg'
        ? 'px-4 py-3 text-lg'
        : 'px-3 py-2 text-base';

  const round = props.btnRounded === 'xs'
    ? 'rounded-xs'
    : props.btnRounded === 'sm'
      ? 'rounded-sm'
      : props.btnRounded === 'md'
        ? 'rounded-md'
        : props.btnRounded === 'lg'
          ? 'rounded-lg'
          : props.btnRounded === 'full'
            ? 'w-10 h-10 rounded-full'
            : '';

  const type = {
    isPrimary: 'm-1 cursor-pointer bg-primary text-baseColor-200 hover:bg-primary-700',
    isBrand: 'm-1 cursor-pointer bg-brandColor-600 border-brandColor-700 text-baseColor-50 hover:bg-brandColor-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brandColor-500 transition-colors',
    isBrandSecondary: 'm-1 cursor-pointer border border-1 border-brandColor-600 text-brandColor-600 font-extrabold hover:bg-brandColor-100 hover:border-brandColor-300',
    isBrandLight: 'm-1 cursor-pointer border border-1 border-brandColor-200/60 bg-brandColor-100 text-brandColor-600 font-extrabold hover:bg-brandColor-300',
    isBrandLightSec: 'm-1 cursor-pointer border border-2 border-brandColor-300/60  font-extrabold text-brandColor-100/60   hover:border-brandColor-600 hover:text-brandColor-600',
    isBrandLightDisabled: 'm-1 opacity-50 cursor-not-allowed border border-1 border-baseColor-300/60 bg-baseColor-100 text-brandColor-800 font-semibold',
    isDefault: 'm-1 cursor-pointer border border-1 border-baseColor-300 bg-baseColor-200 text-baseColor-800 hover:bg-brandColor-300 hover:border-brandColor-600',
    isBlur: 'm-1 text-sm border border-brandColor-600 bg-brandColor-100/40 text-baseColor-800',
    isBrandSecondaryJoined: 'm-1 cursor-pointer rounded-l-lg mr-0 border border-2 border-brandColor-600 text-brandColor-600 hover:bg-brandColor-100 hover:border-brandColor-600 hover:shadow-lg',
    isBrandJoined: 'm-1 cursor-pointer rounded-r-lg bg-brandColor-600 border-brandColor-700 text-baseColor-50 ml-1 hover:bg-brandColor-700',
    isTextOnly: 'm-1 bg-transparent text-baseColor-600',
    isBrandTextOnly: 'text-brandColor-800 font-bold',
    isSecBrandTextOnly: 'text-brandSecondaryColor-600 font-bold',
    isRemove: 'cursor-pointer text-brandColor-800 hover:text-brandColor-600',
    isSecBrand: 'cursor-pointer m-1 bg-brandSecondaryColor-600 text-baseColor-50 uppercase hover:bg-brandSecondaryColor-700',
    isSecBrandBorder100: 'cursor-pointer m-1 border border-brandSecondaryColor-300 bg-transparent text-brandSecondaryColor-600 hover:bg-brandSecondaryColor-300',
    isSecBrandFill100: 'cursor-pointer m-1 border border-brandSecondaryColor-300 bg-brandSecondaryColor-100 text-brandSecondaryColor-600 hover:bg-brandSecondaryColor-300',
    isSecBrand200: 'cursor-pointer m-1 border border-brandSecondaryColor-300 bg-brandSecondaryColor-200 text-brandSecondaryColor-800 font-bold hover:bg-brandSecondaryColor-300',
    isNotAllowed: 'm-1 text-baseColor-600 cursor-not-allowed',
    isNotValid: 'm-1 text-baseColor-400 cursor-not-allowed',
    isGradientBrand: 'm-1 text-brandColor-50 bg-gradient-to-r from-brandColor-600 to-brandColor-500 hover:shadow-lg hover:from-brandColor-500 hover:to-brandColor-700',
    isGradientSecBrand: 'm-1 text-baseColor-600 bg-gradient-to-r from-brandSecondaryColor-300 to-brandSecondaryColor-600 hover:shadow-lg hover:from-brandSecondaryColor-600 hover:to-brandSecondaryColor-300',
    isGradientSecBrandBorder: 'm-1 text-baseColor-600 bg-gradient-to-r from-brandSecondaryColor-300 to-brandSecondaryColor-600 hover:shadow-lg hover:from-brandSecondaryColor-600 hover:to-brandSecondaryColor-300',
    isBaseLight: 'm-1 cursor-pointer border border-1 border-brandColor-200/60 bg-baseColor-100 text-brandColor-600 font-semibold hover:bg-brandColor-300',
  
  }[props.btnType] || 'm-1 cursor-pointer bg-baseColor-200 text-baseColor-800 hover:bg-baseColor-300';

  const disabled = props.disabled ? 'opacity-50 cursor-not-allowed' : '';

  return [base, type, size, round, disabled];
});

const iconComponent = computed(() => {
  const icons = {
    AcademicCapIcon,
    BriefcaseIcon,
    PencilSquareIcon,
    TrashIcon,PlusCircleIcon, SparklesIcon,
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
    EllipsisHorizontalIcon,
    XMarkIcon,XCircleIcon,CursorArrowRaysIcon,
    AdjustmentsHorizontalIcon, UsersIcon,
    BarsArrowDownIcon,UserGroupIcon,
    BarsArrowUpIcon,PaperAirplaneIcon,
    ListBulletIcon,ViewfinderCircleIcon,LinkIcon,
    ArrowUturnLeftIcon,ArrowPathIcon, MagnifyingGlassIcon
  };
  return icons[props.btnIcon] || null;
});

const iconSizeClass = computed(() => ({
  xs: 'w-4 h-4',
  sm: 'w-4 h-4',
  md: 'w-5 h-5',
  lg: 'w-6 h-6',
}[props.size]));
</script>
