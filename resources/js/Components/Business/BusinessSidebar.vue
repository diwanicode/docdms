<template>
  <div>
    <Loading :show="loading" :text="loadingMsg" />
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed z-40 left-0 top-0 border-r border-baseColor-200 text-brandColor-600 font-bold transition-all duration-300 bg-brandColor-100 flex flex-col',
        isMobile ? 'fixed bottom-0 h-16 flex-row justify-around border-t border-r-0' : isSidebarOpen ? 'w-64 h-screen' : 'w-20 h-screen'
      ]"
    >
      <!-- Application Header - Always Visible -->
      <div class="flex items-center justify-between p-4 border-b border-baseColor-200 bg-brandColor-100 min-h-[4rem]" v-if="!isMobile">
        <div class="flex items-center space-x-3">
          <!-- App Logo/Icon -->
          <div  v-if="!isSidebarOpen"  class="w-8 h-8 border border-2 border-brandColor-600 rounded-lg flex items-center justify-center">
             <img
                  src="/images/logoDocDms.png"
                  alt="docDMS"
                  class='w-auto'
                />
          </div>
          <!-- App Name - Show when expanded -->
          <span v-if="isSidebarOpen" class="text-lg font-bold text-brandColor-700 truncate">
             <ApplicationLogo heightClass="h-10"/>
          </span>
        </div>
        <!-- Toggle Button -->
        <button @click="toggleSidebar" class="p-1 hover:bg-brandColor-200 rounded-md">
          <ChevronDoubleLeftIcon v-if="isSidebarOpen" class="h-5 w-5 text-brandColor-600" :title="translations?.general?.hide" />
          <ChevronDoubleRightIcon v-else class="h-5 w-5 text-brandColor-600" :title="translations?.general?.expand" />
        </button>
      </div>
      <!-- Navigation Menu -->
      <nav aria-label="Navigation Menu" class="flex-1 overflow-y-auto" v-if="!isMobile">
        <ul class="py-4 space-y-1"> 
          <li>
            <Link 
              :href="route('business.show', { business })" 
              :title="translations?.businessSidebar?.home"
              :class="[
                'flex items-center mx-3 px-3 py-3 rounded-lg hover:bg-brandColor-200 transition-colors duration-200 group',
                isActiveRoute('business.show', { business }) ? 'bg-brandColor-300 shadow-sm' : ''
              ]"
            >
              <HomeIcon :alt="translations?.businessSidebar?.home" class="w-6 h-6 flex-shrink-0"/>
              <span v-if="isSidebarOpen" class="ml-3 text-sm font-medium truncate">
                {{ translations?.businessSidebar?.home }}
              </span>
              <div v-if="!isSidebarOpen" class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                {{ translations?.businessSidebar?.home }}
              </div>
            </Link>
          </li>

          <!-- <li>
            <Link 
              :href="route('business.dashboard', { business })" 
              :title="translations?.businessSidebar?.dashboard"
              :class="[
                'flex items-center mx-3 px-3 py-3 rounded-lg hover:bg-brandColor-200 transition-colors duration-200 group',
                isActiveRoute('business.dashboard', { business }) ? 'bg-brandColor-300 shadow-sm' : ''
              ]"
            >
              <ChartBarIcon :alt=" translations?.businessSidebar?.dashboard" class="w-6 h-6 flex-shrink-0"/>
              <span v-if="isSidebarOpen" class="ml-3 text-sm font-medium truncate">
                {{ translations?.businessSidebar?.dashboard }}
              </span>
              <div v-if="!isSidebarOpen" class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                {{ translations?.businessSidebar?.dashboard }}
              </div>
            </Link>
          </li> -->

          
          <li>
            <Link 
              :href="route('business.employees', { business })" 
              :title="translations?.businessSidebar?.employees"
              :class="[
                'flex items-center mx-3 px-3 py-3 rounded-lg hover:bg-brandColor-200 transition-colors duration-200 group',
                isActiveRoute('business.employees', { business }) ? 'bg-brandColor-300 shadow-sm' : ''
              ]"
            >
              <UsersIcon :alt="translations?.businessSidebar?.employees" class="w-6 h-6 flex-shrink-0"/>
              <span v-if="isSidebarOpen" class="ml-3 text-sm font-medium truncate">
                {{ translations?.businessSidebar?.employees }}
              </span>
              <div v-if="!isSidebarOpen" class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                {{ translations?.businessSidebar?.employees }}
              </div>
            </Link>
          </li>

<!--         
          <li>
            <Link 
              :href="route('business.calendar', { business })" 
              :title="translations?.businessSidebar?.calendar"
              :class="[
                'flex items-center mx-3 px-3 py-3 rounded-lg hover:bg-brandColor-200 transition-colors duration-200 group',
                isActiveRoute('business.calendar', { business }) ? 'bg-brandColor-300 shadow-sm' : ''
              ]"
            >
              <CalendarDaysIcon :alt="translations?.businessSidebar?.calendar" class="w-6 h-6 flex-shrink-0"/>
              <span v-if="isSidebarOpen" class="ml-3 text-sm font-medium truncate">
                {{ translations?.businessSidebar?.calendar }}
              </span>
              <div v-if="!isSidebarOpen" class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                {{ translations?.businessSidebar?.calendar }}
              </div>
            </Link>
          </li> -->

          <li>
            <Link 
              :href="route('business.clients', { business })" 
              :title="translations?.businessSidebar?.clients"
              :class="[
                'flex items-center mx-3 px-3 py-3 rounded-lg hover:bg-brandColor-200 transition-colors duration-200 group',
                isActiveRoute('business.clients', { business }) ? 'bg-brandColor-300 shadow-sm' : ''
              ]"
            >
              <SparklesIcon :alt="translations?.businessSidebar?.clients" class="w-6 h-6 flex-shrink-0"/>
              <span v-if="isSidebarOpen" class="ml-3 text-sm font-medium truncate">
                {{ translations?.businessSidebar?.clients }}
              </span>
              <div v-if="!isSidebarOpen" class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                {{ translations?.businessSidebar?.clients }}
              </div>
            </Link>
          </li>      
               <li>
            <Link 
              :href="route('business.files', { business })" 
              :title="translations?.businessSidebar?.files"
              :class="[
                'flex items-center mx-3 px-3 py-3 rounded-lg hover:bg-brandColor-200 transition-colors duration-200 group',
                isActiveRoute('business.files', { business }) ? 'bg-brandColor-300 shadow-sm' : ''
              ]"
            >
              <DocumentTextIcon :alt="translations?.businessSidebar?.files" class="w-6 h-6 flex-shrink-0"/>
              <span v-if="isSidebarOpen" class="ml-3 text-sm font-medium truncate">
                {{ translations?.businessSidebar?.files }}
              </span>
              <div v-if="!isSidebarOpen" class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                {{ translations?.businessSidebar?.files }}
              </div>
            </Link>
          </li>   
        </ul>
      </nav>

 <!-- Mobile Navigation -->
<nav v-if="isMobile"
     aria-label="Mobile Navigation"
     class="fixed bottom-0 left-0 w-full bg-brandColor-100 border-t border-baseColor-200 z-50"
>
  <div class="flex items-center space-x-4 overflow-x-auto whitespace-nowrap px-2 py-2 scrollbar-thin scrollbar-thumb-brandColor-400 scrollbar-track-brandColor-100">
    
    <!-- Logo -->
    <!-- <div class="w-8 h-8 border-2 border-brandColor-600 rounded-lg flex-shrink-0 flex items-center justify-center">
      <img src="/images/logoS.png" alt="docDMS" class="w-auto" />
    </div>  -->
    <Link 
      :href="route('business.show', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.show', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <HomeIcon :alt="translations?.businessSidebar?.home" class="w-6 h-6 mb-1"/>
      <span class="text-sm">{{ translations?.businessSidebar?.home }} </span>
    </Link> 
    <!-- Dashboard -->
    <!-- <Link 
      :href="route('business.dashboard', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.dashboard', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <ChartBarIcon :alt="translations?.businessSidebar?.dashboard" class="w-6 h-6 mb-1"/>
      <span class="text-xs">{{ translations?.businessSidebar?.dashboard }}</span>
    </Link> -->

      <!-- Employee -->
    <Link 
      :href="route('business.employees', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.employees', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <UsersIcon :alt="translations?.businessSidebar?.employees" class="w-6 h-6 mb-1"/>
      <span class="text-xs">{{ translations?.businessSidebar?.employees }}</span>
    </Link>


    <!-- Calendar -->
    <!-- <Link 
      :href="route('business.calendar', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.calendar', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <CalendarDaysIcon :alt="translations?.businessSidebar?.calendar" class="w-6 h-6 mb-1"/>
      <span class="text-xs">{{ translations?.businessSidebar?.calendar }}</span>
    </Link> -->

    <!-- Employees -->
    <!-- <Link 
      :href="route('business.employees', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.employees', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <img src="/images/ui_elements/employees.svg" :alt="translations?.businessSidebar?.employees" class="w-6 h-6 mb-1">
      <span class="text-xs">{{ translations?.businessSidebar?.employees }}</span>
    </Link> -->

    <!-- Customers -->
    <Link 
      :href="route('business.clients', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.clients', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <SparklesIcon :alt="translations?.businessSidebar?.customers" class="w-6 h-6 mb-1"/>
      <span class="text-xs">{{ translations?.businessSidebar?.customers }}</span>
    </Link>
     <Link 
      :href="route('business.files', { business })" 
      :class="[
        'flex flex-col items-center p-2 rounded-lg flex-shrink-0',
        isActiveRoute('business.files', { business }) ? 'bg-brandColor-300 shadow-sm' : 'text-brandColor-600'
      ]"
    >
      <DocumentTextIcon :alt="translations?.businessSidebar?.files" class="w-6 h-6 mb-1"/>
      <span class="text-xs">{{ translations?.businessSidebar?.files }}</span>
    </Link>
    <div class="flex flex-col items-center p-2 rounded-lg flex-shrink-0"> 
      <div  class="w-6 h-6 bg-brandColor-500 rounded-full flex items-center justify-center text-brandColor-50  text-sm flex-shrink-0">
        {{ userInitials }}
        
      </div>
      <Button btn-type="isBrandTextOnly"  size="sm"
              :title="translations?.general?.logout" 
              :text="translations?.general?.logout"
              @click="logOut()" /> 
    </div>
  </div>
</nav>

 
       <!-- User Section - Bottom of Sidebar -->
      <div class="border-t border-baseColor-300 bg-brandColor-200/40" v-if="!isMobile">
        <Dropdown align="bottom" width="48" v-if="isSidebarOpen">
          <template #trigger>
            <button class="w-full p-4 flex items-center space-x-3 hover:bg-brandColor-100 transition-colors duration-200 focus:outline-none focus:bg-brandColor-100">
              <!-- User Avatar -->
              <div class="w-8 h-8 bg-brandColor-500 rounded-full flex items-center justify-center text-brandColor-50 font-bold text-sm flex-shrink-0">
                {{ userInitials }}
              </div>
              <!-- User Info -->
              <div class="flex-1 min-w-0 text-left">
                <p class="text-sm font-medium text-brandColor-700 truncate">{{ user.name }}</p>
                <p class="text-xs text-brandColor-500 truncate">{{ userRole }}</p>
               <Button btn-type="isBrandTextOnly"  size="sm"
                      :title="translations?.general?.logout" 
                      :text="translations?.general?.logout"
                      @click="logOut()" />  
               
              </div>
              <!-- Dropdown Arrow -->
              <ChevronDownIcon class="h-4 w-4 text-brandColor-500" />
            </button>
          </template>
          
          <template #content>
            
            <Button btn-type="isBrandTextOnly"  size="sm"
                      :title="translations?.general?.logout" 
                      :text="translations?.general?.logout"
                      @click="logOut()" /> 
          </template>
        </Dropdown>
        
        <!-- Collapsed State - Just Avatar with Tooltip -->
        <div v-else class="p-4 flex justify-center relative group">
          <div  @click="toggleSidebar"  class="w-8 h-8 bg-brandColor-500 rounded-full flex items-center justify-center text-brandColor-50 font-bold text-sm cursor-pointer">
            {{ userInitials }}
          </div>
          <!-- Tooltip for collapsed state -->
          <div class="absolute left-16 bg-gray-900 text-brandColor-50 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
            {{ user.name }}
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div
      :class="{
        'ml-64': isSidebarOpen && !isMobile,
        'ml-20': !isSidebarOpen && !isMobile,
        'ml-0 mb-16': isMobile
      }"
      class="transition-all duration-300 p-2 min-h-screen"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script setup :nonce="$page.props.cspNonce">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link,router,usePage } from '@inertiajs/vue3';
import { ChevronDoubleLeftIcon, ChevronDoubleRightIcon,ChevronDoubleDownIcon,ChevronDownIcon, HomeIcon, ChartBarIcon,CalendarDaysIcon, UsersIcon, SparklesIcon, DocumentTextIcon } from '@heroicons/vue/24/solid'; 
 import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Button from '@/Components/Forms/Button.vue'; 
import Loading from '@/Components/Core/Loading.vue';
const props = defineProps({
  business: Object 
});

const page = usePage();
const translations = computed(() => page.props.translations || {});
const permissions = computed(() => page.props.permissions || []);
const hasPermission = (permission) => permissions.value.includes(permission);
  //loading message and function
  const loading =ref(false);
  const loadingMsg =ref(null);
  const setLoadingMsg = (type, msg = '') => {
      loading.value = type
      loadingMsg.value = msg
  }
 const user = computed(() => page.props.auth.user) 
 const userRole ='Administrator';
// Get user initials for avatar
const userInitials = computed(() => {
  return user.value.name
    .split(' ')
    .map(name => name.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2);
}); 
const lang = computed(() => page.props.language);
const appName ="docDMS";
const isSidebarOpen = ref(true);
const isMobile = ref(false);
const isReportOpen = ref(false);
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
  
  // Store sidebar state in localStorage
  localStorage.setItem('sidebarOpen', isSidebarOpen.value.toString());
};
const toggleReports= () => {
  isReportOpen.value = !isReportOpen.value;   
};

const updateScreenSize = () => {
  const mobile = window.innerWidth < 768;
  isMobile.value = mobile;
  
  // Auto-collapse sidebar on tablet sizes
  if (window.innerWidth < 1024 && window.innerWidth >= 768) {
    isSidebarOpen.value = false;
  }
};

const isActiveRoute = (routeName, params = {}) => {
  try {
    const currentPath = page.url;
    const routePath = new URL(route(routeName, params), window.location.origin).pathname;

    return currentPath === routePath;
  } catch (error) {
    console.log('Route not found:', routeName);
  }
};

// Initialize screen size check and restore sidebar state
onMounted(() => {
  updateScreenSize();
  
  // Restore sidebar state from localStorage
  const savedState = localStorage.getItem('sidebarOpen');
  if (savedState !== null) {
    isSidebarOpen.value = savedState === 'true';
  }
  
  window.addEventListener('resize', updateScreenSize);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateScreenSize);
});
const logOut = () => {
  setLoadingMsg(true, translations.value?.businessSidebar?.loadingLogOut)
  setTimeout(() => {
      router.post(route('logout'), {}, {
      onFinish: () => setLoadingMsg(false),
    });
  }, 1000);
  
};
</script>

<style scoped :nonce="$page.props.cspNonce">
/* Ensure smooth transitions and proper z-index layering */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for navigation */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.2);
}
</style>
