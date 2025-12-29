<template>
  <Head title="Employees" />
  <FlashMessage :flash-msg="flashMsg" />
  <BusinessLayout :business="business">
       <Jumbotron
                :businessName="business.name"
                :main="translations?.businessClients?.title"
                :kpi1="{ title: 'Ukupno', value: 2 }"
                :kpi2="{ title: 'Ukupno', value: 3 }"
                :kpi3="{ title: 'Ukupno', value: 4}"
                :kpi4="{ title: 'Ukupno', value: 5}"
                :tagline="translations?.businessClients?.description"
              />
        <ClientList :business="business"
                    :business-clients="businessClients"
                    :business-clients-columns="businessClientsColumns" 
                    :business-types="businessTypes" />
</BusinessLayout>
</template>

<script setup :nonce="$page.props.cspNonce">
    import {ref, defineProps, computed, watch } from "vue";
    import { Head, usePage } from '@inertiajs/vue3'; 
    import BusinessLayout from '@/Layouts/BusinessLayout.vue'; 
    import FlashMessage from '@/Components/Core/FlashMessage.vue';
    import Jumbotron from "@/Components/Reports/Jumbotron.vue";
    import { useDateUtils } from '@/Composables/useDateUtils'; 
    import ClientList from "@/Components/Client/ClientList.vue";

    const props = defineProps({
        business: Object, 
        businessClients:{
            type: Array,
            default: () => []
        },
        businessClientsColumns: Array,
        businessTypes: Array
    });

    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const flashMsg = computed(() => page.props.flash); 

   const { formatDate, getNameInitials} = useDateUtils();
    const formattedDate = (dateString) => { 
        return formatDate(dateString, 'D MMM YYYY');
    };
    //loading message and function
    const loading =ref(false);
    const loadingMsg =ref(null);
    const setLoadingMsg=(type, msg = '')=>{
        loading.value = type;
        loadingMsg.value=msg;
    }
    
</script>