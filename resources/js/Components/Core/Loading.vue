<template>
  <transition name="fade">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 backdrop-blur-sm"
    >
      <div class="flex flex-col items-center space-y-2">
        <svg
          class="animate-spin h-10 w-10 text-brandColor-500"
          xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 24 24"
        >
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
        </svg>
        <p class="px-4 text-brandColor-700 font-medium text-base text-center tracking-wide">
          {{ text }}
        </p>
      </div>
    </div>
  </transition>
</template>

<script setup :nonce="$page.props.cspNonce">
defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  text: {
    type: String,
    default: 'In progress...',
  },
});
</script>

<style scoped :nonce="$page.props.cspNonce">
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
