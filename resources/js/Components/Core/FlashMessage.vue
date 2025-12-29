
<template>
  <transition
    enter-active-class="transform transition ease-out duration-300"
    enter-from-class="translate-x-full opacity-0"
    enter-to-class="translate-x-0 opacity-100"
    leave-active-class="transform transition ease-in duration-300"
    leave-from-class="translate-x-0 opacity-100"
    leave-to-class="translate-x-full opacity-0"
  >
    <div
      v-if="visible && currentMessage"
      class="fixed top-5 right-5 z-[9999] shadow-lg rounded-xl p-4 w-80 flex items-start space-x-3"
      :class="[bgColor]"
    >
      <div class="flex-1 text-sm font-medium">
        {{ currentMessage }}
      </div> 
       <Button
          type="button"
          btn-type="isRemove"
          btn-icon="XMarkIcon"
          btn-rounded="full"
          size="xs"
          class="ml-2"
          @click="close"/>
    </div>
  </transition>
</template>

<script setup>
  import { ref, watch, computed, onMounted } from "vue";
  import { usePage } from "@inertiajs/vue3";
  import Button from '@/Components/Forms/Button.vue';

  const props = defineProps({
    flashMsg: {
      type: Object,
      default: () => ({}),
    },
    errorMsg: {
      type: Object,
      default: () => ({}),
    },
  });

  const page = usePage();

  const flashMsg = computed(() => props.flashMsg || {});
  const errorMsg = computed(() => props.errorMsg || {});

  const visible = ref(false);

  const currentType = computed(() => {
    if (flashMsg.value.success) return "success";
    if (flashMsg.value.error || errorMsg.value.error) return "error";
    if (flashMsg.value.warning) return "warning";
    if (flashMsg.value.message) return "message";
    return null;
  });

  const currentMessage = computed(() => {
    return (
      flashMsg.value.success ||
      flashMsg.value.error ||
      flashMsg.value.warning ||
      flashMsg.value.message ||
      errorMsg.value.error ||
      null
    );
  });

  const bgColor = computed(() => {
    switch (currentType.value) {
      case "success":
        return "bg-brandColor-600 text-brandColor-100";
      case "error":
        return "bg-error-200 text-error-600";
      case "warning":
        return "bg-warning-200 text-warning-600";
      case "message":
        return "bg-brandColor-200 text-brandColor-600";
      default:
        return "bg-brandColor-200 text-brandColor-600";
    }
  });
 
  watch([flashMsg, errorMsg], () => {
    if (currentMessage.value) {
      visible.value = true;
      // Auto-hide after 4s
      setTimeout(() => {
        visible.value = false;
      }, 20000);
    }
  });

  const close = () => {
    visible.value = false;
  };
</script>
