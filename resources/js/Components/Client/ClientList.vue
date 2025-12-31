<template>
    <DataTable
            :title="translations?.businessClients?.title"
            :data="businessClients"
            :columns="businessClientsColumns"
            :has-action="true"
            :items-per-page="5"
            > 
             <template #cell-name="{ item }">
              <div>
                <div class="font-medium font-semibold text-brandColor-900">{{ item.name }} {{ item.type }}</div>
                <div class="text-sm text-baseColor-500">ID: {{ item.vat_number }} | {{ item.address }} </div>
              </div>
            </template>
            <template #cell-contact="{ item }">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-brandColor-500 to-brandColor-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ getNameInitials(item.contact) }}
                    </div>

                    <div>
                        <div class="font-medium text-baseColor-900">
                        {{ item.contact }}
                        </div>
                        <div class="text-sm text-baseColor-500">{{ item.email }} | {{ item.number }} </div>
                    </div>
                </div>
            </template>
             <template #cell-employee="{ item }">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-brandSecondaryColor-500 to-brandSecondaryColor-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ getNameInitials(item.employee) }}
                    </div>

                    <div>
                        <div class="font-medium text-baseColor-900">
                        {{ item.employee }}
                        </div>
                    </div>
                </div>
            </template>
            <template #cell-active="{ value }">
              <ButtonToggle :model-value="value" color="brand" :disabled="true" />
          </template>
        <template #row-actions="{ item }">
            <Button v-if="can('clients.update')"
                btn-type="isBrandSecondary"
                btn-icon="PencilSquareIcon"
                :title="translations?.general?.edit"
                size="sm"
                @click="editClient(item)"
                />
        </template>
         <template #actions="{ item }">
            <Button v-if="can('clients.create')"
                btn-type="isBrandSecondary"
                btn-icon="PlusCircleIcon"
                :text="translations?.businessClients?.createClient"
                :title="translations?.businessClients?.createClient"
                @click="createClient()"
                />
        </template>
        </DataTable> 
    <Modal :show="showModalClient"
        :title="isEdit ?  translations?.businessClients?.createClient  : translations?.businessClients?.editClient"
        maxWidth="2xl"
        @close="showModalClient = false"
    > 
          <ClientForm :business="business" 
                      :selected-client="selectedClient" 
                      :is-edit="isEdit"
                      :business-types="businessTypes"
                      @close-client-modal="closeModalClient()"/>
    </Modal>
</template>

<script setup :nonce="$page.props.cspNonce">
    import {ref, defineProps, computed, watch } from "vue";
    import { Head, usePage } from '@inertiajs/vue3';  
    import DataTable from "@/Components/Reports/DataTable.vue"; 
    import { useDateUtils } from '@/Composables/useDateUtils';  
    import ButtonToggle from "@/Components/Forms/ButtonToggle.vue";
    import Button from "@/Components/Forms/Button.vue";
    import Modal from '@/Components/Core/Modal.vue'
    import { usePermissions } from '@/Composables/usePermissions'
    import ClientForm from '@/Components/Client/ClientForm.vue'
    
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
    const showModalClient=ref(false);
     const selectedClient=ref(null);
    const isEdit= ref(false);

    const editClient =(row)=>{
        showModalClient.value=true;
        selectedClient.value=row;
        isEdit.value=true;
        console.log('openModalEmployeee');
        console.log(row);
    }
    const createClient =()=>{
          isEdit.value=false;
          selectedClient.value=null;
          showModalClient.value=true;
    }
     const closeModalClient=()=>{
        showModalClient.value=false; 
    }
</script>