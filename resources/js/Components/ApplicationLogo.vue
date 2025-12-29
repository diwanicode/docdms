<script setup>
defineProps({
  heightClass: {
    type: String,
    default: 'h-14'
  }
})
</script>

<template>
  <img
    src="/images/logoDocDms.png"
    alt="docDMS"
    :class="[heightClass, 'w-auto', 'block']"
  />
</template>
