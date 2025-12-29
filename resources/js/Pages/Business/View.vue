<template>
  <FlashMessage :flash-msg="flashMsg" />
  <BusinessLayout :business="business"> 
    <template #default>    
      <div class="content">
        <BusinessCard   :business-details="business"
                        :locations="business.locations"
                        :social-media="business.social_media"
                        :business-types="business.business_types" /> 
       
        <DepartmentList :business="business"
                        :business-employees="businessEmployees" 
                        :business-departments="businessDepartments"
                        :business-departments-columns="businessDepartmentsColumns"
                        :department-data="selectedDepartment"
                        :permissons="permissons"
                        :is-edit="isEdit"  />
      </div> 
      
    </template>   
  </BusinessLayout>
</template>

<script setup :nonce="$page.props.cspNonce">
  import { ref, computed  } from "vue"; 
  import {router, usePage } from '@inertiajs/vue3'; 
  import BusinessLayout from '@/Layouts/BusinessLayout.vue'; 
  import BusinessCard from '@/Components/Business/BusinessInfo.vue'; 
  import { useDateUtils } from '@/Composables/useDateUtils'; 
  import FlashMessage from '@/Components/Core/FlashMessage.vue'; 
  import DepartmentList from "@/Components/Department/DepartmentList.vue";

    const props = defineProps({
        business: Object, 
        businessEmployees:{
            type: Array,
            default: () => []
        },
        businessDepartments:{
            type: Array,
            default: () => []
        },
        businessDepartmentsColumns: Array,
        permissons: Array
       
    }); 
    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const flashMsg = computed(() => page.props.flash);
  
    const { getNameInitials} = useDateUtils(); 
    const showModalDepartment=ref(false);
    const selectedDepartment =ref(null);
    const isEdit= ref(false);
    const createDepartment =()=>{
        showModalDepartment.value=true;
        isEdit.value=false;
       console.log('createFile');
    }
    const editDepartment =(row)=>{
        showModalDepartment.value=true;
        isEdit.value=true;
        selectedDepartment.value=row;
       console.log('editDepartment');
    }
    const deleteDepartment=()=>{
      console.log('deleteDepartment');
        selectedDepartment.value=row;
    }
      const closeModalFile=()=>{
        showModalDepartment.value=false;
        console.log('closeModalFile'); 
    }
</script>

 