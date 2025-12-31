<template>
    <FlashMessage :flash-msg="flashMsg" />
    <DataTable
        :title="translations?.businessEmployees?.title"
        :data="businessEmployees"
        :columns="businessEmployeesColumns"
        :has-action="true"
        :items-per-page="5">
            <template #cell-name="{ item }">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-brandColor-500 to-brandColor-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ getNameInitials(item.name) }}
                    </div>

                    <div>
                        <div class="font-medium text-baseColor-900">
                        {{ item.name }}
                        </div>
                    </div>
                </div>
            </template>
            <template #cell-departments="{ item  }">
                <div class="flex flex-wrap gap-1">   
                    <Badge   v-for="dep in item.departments" 
                            :key="dep.id"
                            :title="dep.name" 
                            size="xs" 
                            color="secondary"/>
                 </div>         
            </template>
            <template #cell-owner="{ item }">
                <Badge :title="item.owner ? translations?.businessEmployees?.owner : translations?.businessEmployees?.employee"
                        size="xs"
                       :color="item.owner ? 'SecBrandRing': 'brandLightRing'"/>           
            </template>
           
         
                 <template #cell-active="{ value }">
              <ButtonToggle :model-value="value" color="brand" :disabled="true" />
          </template>
        <template #row-actions="{ item }">
            <Button v-if="can('employees.update')"
                btn-type="isBrandSecondary"
                btn-icon="PencilSquareIcon"
                :title="translations?.general?.edit"
                size="sm"
                @click="editEmployeee(item)"
                />
        </template>
         <template #actions="{ item }">
            <Button v-if="can('employees.create')"
                btn-type="isBrandSecondary"
                btn-icon="PlusCircleIcon"
                :text="translations?.businessEmployees?.createEmployee"
                :title="translations?.businessEmployees?.createEmployee"
                @click="createEmployee()"
                />
        </template>
    </DataTable> 
    <Modal :show="showModalEmployee"
        :title="isEdit ? translations?.businessEmployees?.editEmployee :  translations?.businessEmployees?.createEmployee"
        maxWidth="2xl"
        @close="showModalEmployee = false"> 
         <EmployeeForm  :business="business"
                        :selected-employee="selectedEmployee"
                        :is-edit="isEdit"
                        :business-departments="businessDepartments" 
                        @close-file-modal="closeModalFile()"/>
    </Modal>
</template>


<script setup :nonce="$page.props.cspNonce">
    import {ref, defineProps, computed, watch } from "vue";
    import { Head, usePage } from '@inertiajs/vue3';  
    import FlashMessage from '@/Components/Core/FlashMessage.vue';
    import DataTable from "@/Components/Reports/DataTable.vue";
    import { useDateUtils } from '@/Composables/useDateUtils'; 
    import Badge from "@/Components/Forms/Badge.vue";
    import ButtonToggle from "@/Components/Forms/ButtonToggle.vue";
    import Button from "@/Components/Forms/Button.vue";
    import Modal from '@/Components/Core/Modal.vue'
    import EmployeeForm from '@/Components/Employee/EmployeeForm.vue'
    import { usePermissions } from '@/Composables/usePermissions'

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
   
    const { can } = usePermissions()
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
    //edit employee
    const showModalEmployee=ref(false);
    const selectedEmployee=ref(null);
    const isEdit= ref(false);

    const editEmployeee =(row)=>{
        showModalEmployee.value=true;
        selectedEmployee.value=row;
        isEdit.value=true;
        console.log('openModalEmployeee');
        console.log(row);
    }
    const createEmployee =()=>{
          isEdit.value=false;
          selectedEmployee.value=null;
          showModalEmployee.value=true;
    }
     const closeModalFile=()=>{
        showModalEmployee.value=false; 
    }
</script>