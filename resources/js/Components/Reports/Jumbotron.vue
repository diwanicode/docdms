<template>
  <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-brandColor-500 to-brandColor-200 shadow-sm">
    <!-- Background Overlay -->
    <div class="absolute inset-0">
      <img 
        src="/images/bg_6.png"
        alt="Analytics Background" 
        class="h-full w-full object-cover opacity-50"
      />
      <div class="absolute inset-0 bg-gradient-to-r from-brandColor-500/85 to-brandColor-200/70"></div>
    </div>
    
    <!-- Content -->
    <div class="relative px-4 py-4 sm:px-6 lg:px-12">
      <div class="flex flex-row flex-wrap items-center gap-6 lg:gap-8">
        
        <!-- Left Side: Business Name + KPIs -->
        <div class="flex-1 w-full xs:w-2/3 sm:w-2/3 md:w-2/3 lg:w-2/3 text-center lg:text-left order-1">
          
          <!-- Business Name -->
          <div class="mb-6">
            <h1 class="text-2xl mb-2 xs:text-xl sm:text-2xl">
              <span class="text-3xl xs:text-2xl text-brandColor-50 font-extrabold" id="businessName">
                {{ businessName }}
              </span>
            </h1>
            <p class="text-baseColor-700 text-sm xs:text-xs sm:text-base" id="main">
              {{ main }}
            </p>
          </div>

          <!-- KPI Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 lg:gap-6">
            <KpiBox
              :title="kpi1.title"
              :value="kpi1.value"
              boxType="dashboardDark"
              class="text-sm xs:text-xs"
            /> 
            <KpiBox
              :title="kpi2.title"
              :value="kpi2.value"
              boxType="dashboardDark"
              class="text-sm xs:text-xs"
            /> 
            <KpiBox
              :title="kpi3.title"
              :value="kpi3.value"
              boxType="dashboardDark"
              class="text-sm xs:text-xs"
            /> 
             <KpiBox
              :title="kpi4.title"
              :value="kpi4.value"
              boxType="dashboardDark"
              class="text-sm xs:text-xs"
            />      
          </div>
        </div> 
      </div>
    </div>
  </div>
</template>

<script setup :nonce="$page.props.cspNonce">
import KpiBox from './KpiBox.vue'

const props = defineProps({
  businessName: { type: String, required: true, default: 'Your Business Name' },
  main: { type: String, required: true, default: 'Business Info' },
  kpi1: { type: Object, required: true, default: () => ({ title: 'Revenue', value: 125000 }) },
  kpi2: { type: Object, required: true, default: () => ({ title: 'Customers', value: 2500 }) },
  kpi3: { type: Object, required: true, default: () => ({ title: 'Growth', value: '24%' }) },
  kpi4: { type: Object, required: true, default: () => ({ title: 'Growth', value: '24%' }) },
  tagline: { type: String, default: '' }, 
})
</script>

<style scoped :nonce="$page.props.cspNonce">
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.group:hover img {
  animation: float 3s ease-in-out infinite;
}

* {
  transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
}

/* Scrollbar Styling */
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: theme('colors.baseColor.800'); }
::-webkit-scrollbar-thumb { background: theme('colors.brandColor.600'); border-radius: 2px; }
::-webkit-scrollbar-thumb:hover { background: theme('colors.brandColor.500'); }
</style>
