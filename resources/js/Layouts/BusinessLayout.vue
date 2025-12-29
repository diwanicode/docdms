<template>
  <AuthenticatedLayout>  
    <FlashMessage :flash-msg="flashMsg" /> 
    <div class="flex relative overflow-visible"> 
      <BusinessSidebar 
          :business="business"   
        />
      <!-- Main Content -->      
      <div class="flex-1 p-2 overflow-x-hidden"
           :class="{ 'pb-24': isMobile }">
        <slot />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
<script setup :nonce="$page.props.cspNonce">
  import {computed, ref, onMounted, onBeforeUnmount} from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import BusinessSidebar from '@/Components/Business/BusinessSidebar.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; 
  import FlashMessage from '@/Components/Core/FlashMessage.vue';
  
  const page = usePage()
 const flashMsg = computed(() => page.props.flash);
  
  const props = defineProps({
    business: Object
  });
  const isMobile = ref(false)
  function checkIsMobile() {
    isMobile.value = window.innerWidth < 768 // md breakpoint
  }

  onMounted(() => {
    checkIsMobile()
    window.addEventListener('resize', checkIsMobile)
  })

  onBeforeUnmount(() => {
    window.removeEventListener('resize', checkIsMobile)
  })
</script> 
  