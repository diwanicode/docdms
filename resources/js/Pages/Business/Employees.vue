<template>
  <Head title="Employees" />
  <FlashMessage :flash-msg="flashMsg" />
  <BusinessLayout :business="business">
       <Jumbotron
                :businessName="business.name"
                :main="translations?.businessEmployees?.title"
                :kpi1="{ title: 'Ukupno', value: 2 }"
                :kpi2="{ title: 'Ukupno', value: 3 }"
                :kpi3="{ title: 'Ukupno', value: 4}"
                :kpi4="{ title: 'Ukupno', value: 5}"
                :tagline="translations?.businessEmployees?.description"
              />
       <EmployeeList :business="business"
                  :business-employees="businessEmployees"
                  :business-departments="businessDepartments" 
                  :business-employees-columns="businessEmployeesColumns"/>
</BusinessLayout>
</template>

<script setup :nonce="$page.props.cspNonce">
    import {ref, defineProps, computed, watch } from "vue";
    import { Head, usePage } from '@inertiajs/vue3'; 
    import BusinessLayout from '@/Layouts/BusinessLayout.vue'; 
    import FlashMessage from '@/Components/Core/FlashMessage.vue';
    import Jumbotron from "@/Components/Reports/Jumbotron.vue";
    import EmployeeList from "@/Components/Employee/EmployeeList.vue";
    
    const props = defineProps({
        business: Object, 
        businessEmployees:{
            type: Array,
            default: () => []
        },
        businessEmployeesColumns: Array,
        businessDepartments:{
            type: Array,
            default: () => []
        },
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