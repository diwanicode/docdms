<template>
   <FlashMessage :flash-msg="flashMsg" />
   <DataTable
        :title="translations?.businessDepartments?.title"
        :data="businessDepartments"
        :columns="businessDepartmentsColumns"
        :has-action="true"
        :items-per-page="5"
        >
        <template #cell-employees="{ item  }">
        <div class="flex flex-wrap gap-1">   
            <Badge   v-for="emp in item.employees" 
                    :key="emp.id"
                    :title="emp.name" 
                    size="xs" 
                    color="SecBrandRing"/>
            </div>         
        </template>   
        <template #cell-permissions="{ item  }">
        <div class="flex flex-wrap gap-1">   
            <Badge   v-for="dep in item.permissions" 
                    :key="dep.id"
                    :title="dep.name" 
                    size="xs" 
                    color="secondary"/>
            </div>         
        </template>     
        <template #row-actions="{ item }">
        <div class="flex gap-2">
            <Button  
                btn-type="isBrandSecondary"
                btn-icon="PencilSquareIcon"
                :title="translations?.businessDepartments?.editDepartment"
                size="sm"
            @click="editDepartment(item)" />
            <Button v-if="can('departments.delete')"
                btn-type="isBrandSecondary"
                btn-icon="TrashIcon"  
                :title="translations?.businessDepartments?.deleteDepartment"
                size="sm"
                @click="openDeleteDepartmentModal(item)" />
        </div>
        </template>
        <template #actions="{ item }">
            <Button v-if="can('departments.create')"
                btn-type="isBrandSecondary"
                btn-icon="PlusCircleIcon"
                :text="translations?.businessDepartments?.createDepartment"
                :title="translations?.businessDepartments?.createDepartment"
                @click="createDepartment()"
                />
        </template>
    </DataTable> 
    <Modal :show="showModalDepartment"
        :title="isEdit ? translations?.businessDepartments?.editDepartment : translations?.businessDepartments?.createDepartment"
        maxWidth="2xl"
        @close="showModalDepartment = false"> 
        <DepartmentForm  :business="business"
                   :business-employees="businessEmployees" 
                   :department-data="selectedDepartment"
                   :permissons="permissons"
                   :is-edit="isEdit"
                    @close-department-modal="closeModalFile()" />
    </Modal>
     <Modal :show="showModalDeleteDepartment"
        :title="translations?.businessDepartments?.deleteDepartment"
        maxWidth="2xl"
        @close="showModalDeleteDepartment = false"> 
           <p>  {{ translations?.businessDepartments?.deleteDepartmentNote }} <span class=" font-bold text-brandColor-800"> {{ selectedDepartment?.name }}</span> ?</p>   
     
          <div class="mt-4 flex items-center justify-end">
            <Button btnType="isBrandSecondary" :text="translations?.general?.cancel" @click="showModalDeleteDepartment = false"/>
            <Button btnType="isBrand" :loading="loading" :disabled="loading" :text="translations?.general?.delete" @click="deleteDepartment" />
          </div>
    </Modal>
</template>

<script setup :nonce="$page.props.cspNonce">
  import { ref, computed  } from "vue"; 
  import {router, usePage } from '@inertiajs/vue3';  
  import { useDateUtils } from '@/Composables/useDateUtils'; 
  import FlashMessage from '@/Components/Core/FlashMessage.vue';
  import Badge from "@/Components/Forms/Badge.vue";
  import DataTable from "@/Components/Reports/DataTable.vue";
  import Button from "@/Components/Forms/Button.vue";
  import Modal from '@/Components/Core/Modal.vue'
  import DepartmentForm from "@/Components/Department/DepartmentForm.vue";
  import { usePermissions } from '@/Composables/usePermissions'

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
    
    const { can } = usePermissions()

    const { getNameInitials} = useDateUtils(); 
    const showModalDepartment=ref(false);
    const showModalDeleteDepartment=ref(false);
    const selectedDepartment =ref(null);
    const isEdit= ref(false);
    const loading=ref(false);
    const createDepartment =()=>{
        showModalDepartment.value=true;
        selectedDepartment.value=null; 
        isEdit.value=false; 
    }
    const editDepartment =(row)=>{
        showModalDepartment.value=true;
        isEdit.value=true;
        selectedDepartment.value=row; 
    }
    const deleteDepartment=()=>{
      console.log('deleteDepartment'); 
        if (!selectedDepartment.value) return
        loading.value = true;
        router.delete(
            route('business.departments.destroy', {
                business: props.business.slug,
                businessDepartment: selectedDepartment.value.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    selectedDepartment.value = null
                    showModalDeleteDepartment.value = false;
                },
                onError: (errors) => {
                    console.error(errors)
                }
            }
        )
        setTimeout(() => {
            loading.value = false;
        }, 20000);
    }
    const openDeleteDepartmentModal=(row)=>{ 
        selectedDepartment.value=row;
        showModalDeleteDepartment.value=true;
    }
    const closeModalFile=()=>{
        showModalDepartment.value=false; 
    }
</script>