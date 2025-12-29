<template>
  <span 
    :class="['inline-flex  font-semibold  items-center justify-center rounded-full font-medium', badgeClasses]" 
    :style="customStyle"
  > 
    {{ title }}
  </span>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    color: {
      type: String,
      required: true,
    },
    size: {
      type: String,
      default: 'sm',
      validator: (value) => ['xs', 'sm', 'md', 'lg'].includes(value),
    },
    customColor: {
      type: Object,
      default: () => ({
        light: '#d3fafa',
        dark: '#199fb1'
      })
    },
  },
  computed: {
    badgeClasses() {
      // Determine color classes
      const colorClass = this.color === 'brand'
        ? 'bg-brandColor-600 text-brandColor-50 ring-1 ring-brandColor-600/10'
        : this.color === 'brandLight'
        ? 'bg-brandColor-100 text-brandColor-800 cursor-pointer hover:bg-brandColor-600 hover:text-brandColor-50'
        : this.color === 'brandSecondary'
        ? 'border rounded-full border-brandColor-600 border-1 bg-brandColor-100 text-baseColor-800'
        : this.color === 'isBlur'
        ? 'border rounded-full border-brandColor-600 border-1 bg-brandColor-100/40 text-baseColor-800'
        : this.color === 'brandLightRing'
        ? 'bg-brandColor-100 text-brandColor-600 ring-1 ring-brandColor-100 hover:bg-brandColor-200'
        : this.color === 'SecBrandRing'
        ? 'bg-brandSecondaryColor-100 text-brandSecondaryColor-800 ring-1 ring-brandSecondaryColor-100 hover:bg-brandSecondaryColor-200'
        : this.color === 'secondary'
        ? 'bg-brandColor-50 text-brandColor-600 ring-1 ring-brandColor-100 hover:bg-brandColor-600 hover:text-brandColor-50'
        : this.color === 'success'
        ? 'bg-success-300 text-success-700 ring-1 ring-success-700/10'
        : this.color === 'blue'
        ? 'bg-blue-500 text-white ring-1 ring-blue-500/10'
        : this.color === 'red'
        ? 'bg-red-500 text-white ring-1 ring-red-500/10'
        : this.color === 'default'
        ? 'bg-baseColor-100 text-baseColor-400'
        : this.color === 'disable'
        ? 'bg-baseColor-100 text-baseColor-400 cursor-not-allowed'
        : this.color === 'custom'
        ? '' // Inline styles will handle it
        : 'bg-baseColor-200 text-brandColor-600 ring-1 ring-baseColor-200/10';

      // Determine size classes
      const sizeClass = this.size === 'xs'
        ? 'px-2 text-[10px]'
        : this.size === 'sm'
        ? 'px-2 py-1 text-sm'
        : this.size === 'lg'
        ? 'px-4 py-3 text-lg'
        : 'px-3 py-2 text-base';

      return `${colorClass} ${sizeClass}`;
    },

   
    customStyle() {
      if (this.color === 'custom') {
        return {
          backgroundColor: this.customColor?.dark || '#199fb1',
          color: this.customColor?.light || '#d3fafa',
          borderColor: this.customColor?.dark || '#199fb1',
          borderWidth: '1px',
        };
      }
      return {};
    },
  },
};
</script>
