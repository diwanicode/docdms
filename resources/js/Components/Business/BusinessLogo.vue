<template>
  <div class="flex-shrink-0">
    <!-- Case 1: Logo exists (preview mode, possibly new upload or stored) -->
    <div
      v-if="preview && previewLogo"
      class="w-24 h-24 rounded-full overflow-hidden ring-2 ring-brandColor-200 shadow"
    >
      <img :src="previewLogo" :alt="`${business.name} preview`" class="w-full h-full object-cover" />
    </div>

    <!-- Case 2: Non-preview mode with stored logo -->
    <div
      v-else-if="business.logo && !preview"
      class="w-24 h-24 rounded-full overflow-hidden ring-2 ring-brandColor-200 shadow"
    >
      <img :src="`/storage/${business.logo}`" :alt="`${business.name} logo`" class="w-full h-full object-cover" />
    </div>

    <!-- Case 3: No logo -->
    <div
      v-else
      class="w-24 h-24 rounded-full bg-gradient-to-br from-brandColor-500 to-brandColor-700 flex items-center justify-center ring-2 ring-brandColor-200 shadow"
    >
      <span class="text-white font-bold text-2xl">
        {{ getNameInitials(business.name) }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useDateUtils } from '@/Composables/useDateUtils'

const props = defineProps({
  business: {
    type: Object,
    required: true,
  },
  preview: {
    type: Boolean,
    default: false,
  },
})

const { getNameInitials } = useDateUtils()

const previewLogo = computed(() => {
  const logo = props.business.logo

  // If it's a new upload (File or Blob)
  if (logo instanceof File || logo instanceof Blob) {
    return URL.createObjectURL(logo)
  }

  // If it's a stored logo (string path from DB)
  if (typeof logo === 'string' && logo !== '') {
    return `/storage/${logo}`
  }

  // No logo
  return null
})
</script>
