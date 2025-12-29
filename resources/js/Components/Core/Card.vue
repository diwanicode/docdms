<template>
  <div :class="[ 'rounded-2xl p-4 blurtext transparent h-full flex flex-col transition-all duration-200',cardClass, colSpanClass ]">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    cardType: {
      type: String,
      default: 'isSecondary',
      validator(value) {
        return ['isSecondary','isSecondaryHover','isBlur','isSimple','isBrandSec','isBrandBorder', 'isBrand',
        'isBrandLight','isBrand100',,'isBrand200',
         'isPrimary','isDefault','isDefaultNoHover'].includes(value);
      }
    },
    colSpan: {
      type: Number,
      default: null
    }
  },
  computed: {
    cardClass() {
      switch (this.cardType) {
        case 'isSimple':
          return '';
        case 'isBlur':
          return 'shadow-lg  border-1 border-brandColor-50 bg-baseColor-50/20 backdrop-blur-md';
        case 'isSecondary':
          return 'border border-1 border-baseColor-200 transparent';
        case 'isSecondaryHover':
          return 'border border-1 border-baseColor-200 transparent hover:border-brandColor-100 hover:shadow-md';
        case 'isBrandSec':
          return 'shadow-md border-2 border-brandColor-600 bg-brandColor-100 hover:bg-brandColor-200 hover:border-brandColor-600';
        case 'isBrandBorder':
          return 'shadow-md border-2 border-brandColor-200 transparent hover:border-brandColor-600';
        case 'isBrand':
          return 'shadow-md border-2 border-transparent bg-brandColor-600 text-baseColor-400 hover:bg-brandColor-800';
        case 'isBrandLight':
          return 'shadow-md  border-2 border-brandColor-50 bg-brandColor-50 text-baseColor-800';
        case 'isBrand100':
          return 'shadow-md  border-2 border-brandColor-100 bg-brandColor-100/60 backdrop-blur-md  text-baseColor-800';
        case 'isBrand200':
          return 'shadow-md  border-2 border-brandColor-100 bg-brandColor-200 text-baseColor-800';
        case 'isPrimary':
          return 'shadow-md border-2 border-transparent bg-blue-500 text-baseColor-400 hover:bg-blue-600';
        case 'isDefault':
          return 'shadow-md border-2 border-baseColor-200 bg-baseColor-100 text-baseColor-400 hover:shadow-lg hover:bg-baseColor-200';
        case 'isDefaultNoHover':
          return 'border-2 border-baseColor-200 transparent text-baseColor-400';
        default:
          return '';
      }
    },
    colSpanClass() {
      // Only add col-span-2 if specified; otherwise, no class
      return this.colSpan === 2 ? 'col-span-2' : '';
    }
  }
};
</script>
