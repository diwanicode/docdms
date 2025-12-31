<template>
  <DataTable
            :title="translations?.businessFiles?.title"
            :data="businessFiles"
            :columns="businessFilesColumns"
            :has-action="true"
            :items-per-page="5"
            > 
             <template #cell-name="{ item }">
               <div>
                    <a :href="item.file_url"  class="font-medium text-brandColor-900">
                        {{ item.name }}
                    </a>
                </div>
            </template>
            <template #cell-client="{ item }">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-brandColor-500 to-brandColor-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ getNameInitials(item.client) }}
                    </div>

                    <div>
                        <div class="font-medium text-baseColor-900">
                        {{ item.client }}
                        </div>
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
            <div class="flex gap-2">
                <Button v-if="can('files.update')"
                    btn-type="isBrandSecondary"
                    btn-icon="PencilSquareIcon"
                    :title="translations?.businessFiles?.editFile"
                    size="sm"
                    @click="editFile(item)"
                    />
                <Button v-if="can('files.delete')"
                    btn-type="isBrandSecondary"
                    btn-icon="TrashIcon"
                    :title="translations?.businessFiles?.deleteFile"
                    size="sm"
                    @click="openDeleteFileModal(item)"
                    />
            </div>          
           
        </template>
        <template #actions="{ item }">
            <Button v-if="can('files.create')"
                btn-type="isBrandSecondary"
                btn-icon="PlusCircleIcon"
                :text="translations?.businessFiles?.createFile"
                :title="translations?.businessFiles?.createFile"
                @click="createFile()"
                />
        </template>
        </DataTable> 
    <Modal :show="showModalFile"
        :title="isEdit ? translations?.businessFiles?.editFile : translations?.businessFiles?.createFile"
        maxWidth="2xl"
        @close="showModalFile = false"
    > 
        <FileForm  :business="business"
                   :business-clients="businessClients"
                   :file-categories="fileCategories"
                   :file-statuses="fileStatuses"
                   :file-types="fileTypes"
                   :file-data="fileData"
                   :is-edit="isEdit"
                    @close-file-modal="closeModalFile()" />
    </Modal>
     <Modal :show="showModalDeleteFile"
        :title="translations?.businessFiles?.deleteFile"
        maxWidth="2xl"
        @close="showModalDeleteFile = false"> 
           <p>  {{ translations?.businessFiles?.deleteFileNote }} <span class=" font-bold text-brandColor-800"> {{ fileData?.name }}</span> ?</p>   
     
          <div class="mt-4 flex items-center justify-end">
            <Button btnType="isBrandSecondary" :text="translations?.general?.cancel" @click="showModalDeleteFile = false"/>
            <Button btnType="isBrand" :loading="loading" :disabled="loading" :text="translations?.general?.delete" @click="deleteFile" />
          </div>
    </Modal>
</template>

<script setup :nonce="$page.props.cspNonce">
    import {ref, defineProps, computed, watchEffect } from "vue";
    import {router, usePage } from '@inertiajs/vue3';  
    import DataTable from "@/Components/Reports/DataTable.vue"; 
    import ButtonToggle from "@/Components/Forms/ButtonToggle.vue";
    import Button from "@/Components/Forms/Button.vue";
    import Modal from '@/Components/Core/Modal.vue'
    import FileForm from "@/Components/File/FileForm.vue";
    import { usePermissions } from '@/Composables/usePermissions'
    import { useDateUtils } from '@/Composables/useDateUtils'; 

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
 
    const { can } = usePermissions()
    const { formatDate, getNameInitials} = useDateUtils();
    
    //loading message and function
    const loading =ref(false);
    const loadingMsg =ref(null);
    const setLoadingMsg=(type, msg = '')=>{
        loading.value = type;
        loadingMsg.value=msg;
    }
    //edit file
    const isEdit=ref(false);
    const showModalFile=ref(false);
    const showModalDeleteFile=ref(false);
    const fileData =ref(null);
    const editFile =(row)=>{
        showModalFile.value=true;
        isEdit.value=true;
        fileData.value=row;
    }
    const createFile =()=>{
        showModalFile.value=true;
        isEdit.value=false;
        fileData.value=null;
    }
    const closeModalFile=()=>{
        showModalFile.value=false;
    }
   
   const deleteFile=()=>{
      console.log('deleteFile'); 
         console.log(props.business.slug); 
        if (!fileData.value) return
        loading.value = true;
        router.delete(
            route('business.files.destroy', {
                business: props.business.slug,
                businessFile: fileData.value.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    fileData.value = null
                    showModalDeleteFile.value = false;
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
    const openDeleteFileModal=(row)=>{ 
        fileData.value=row;
        showModalDeleteFile.value=true;
    }
</script>