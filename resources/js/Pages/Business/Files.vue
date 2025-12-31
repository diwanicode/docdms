<template>
  <Head title="Employees" />
  <FlashMessage :flash-msg="flashMsg" />
  <BusinessLayout :business="business">
       <Jumbotron
                :businessName="business.name"
                :main="translations?.businessFiles?.title"
                :kpi1="{ title: 'Ukupno', value: 2 }"
                :kpi2="{ title: 'Ukupno', value: 3 }"
                :kpi3="{ title: 'Ukupno', value: 4}"
                :kpi4="{ title: 'Ukupno', value: 5}"
                :tagline="translations?.businessFiles?.description"
              />
        <FileList :business="business"  
                  :business-clients="businessClients"
                  :business-files="businessFiles"
                  :business-files-columns="businessFilesColumns"
                  :file-categories="fileCategories"
                  :file-statuses="fileStatuses"
                  :file-types="fileTypes"
                  />
</BusinessLayout>
</template>

<script setup :nonce="$page.props.cspNonce">
    import {ref, defineProps, computed, watchEffect } from "vue";
    import { Head, usePage } from '@inertiajs/vue3'; 
    import BusinessLayout from '@/Layouts/BusinessLayout.vue';  
    import FlashMessage from '@/Components/Core/FlashMessage.vue'; 
    import Jumbotron from "@/Components/Reports/Jumbotron.vue"; 
    import FileList from "@/Components/File/FileList.vue";

    const props = defineProps({
        business: Object, 
        businessClients:{
            type: Array,
            default: () => []
        },
        businessFiles:{
            type: Array,
            default: () => []
        },
        businessFilesColumns: Array,
        fileCategories: Array,
        fileStatuses: Array,
        fileTypes: Array
    });

    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const flashMsg = computed(() => page.props.flash); 
 
    
    //loading message and function
    const loading =ref(false);
    const loadingMsg =ref(null);
    const setLoadingMsg=(type, msg = '')=>{
        loading.value = type;
        loadingMsg.value=msg;
    }
    
</script>