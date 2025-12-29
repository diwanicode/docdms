<template>
  <form @submit.prevent="submit" class="space-y-5">

    <!-- Client -->
    <div>
      <InputLabel :value="translations?.businessFiles?.client" />
      <InputDropdown
        v-model="form.business_client_id"
        :items="businessClients" 
        :placeholder="translations?.businessFiles?.selectClient"
      />
      <InputError :message="basicErrors?.business_client_id || form.errors.business_client_id"/>
    </div>

    <!-- Category -->
    <div>
      <InputLabel :value="translations?.businessFiles?.fileCategory" />
      <InputDropdown
        v-model="form.country_file_category_id"
        :items="fileCategories"
        :placeholder="translations?.businessFiles?.selectFileCategory"
      />
       <InputError :message="basicErrors?.country_file_category_id || form.errors.country_file_category_id"/>
    </div>

    <!-- File Type -->
    <div v-if="showFileType">
      <InputLabel :value="translations?.businessFiles?.fileType" />
      <InputDropdown
        v-model="form.country_file_type_id"
        :items="filteredFileTypes"
        :placeholder="translations?.businessFiles?.selectFileType"
      />
       <InputError :message="basicErrors?.country_file_type_id || form.errors.country_file_type_id"/>
    </div>

    <!-- Status -->
    <!-- <div v-if="isEdit">
      <InputLabel :value="translations?.businessFiles?.client" />
      <InputDropdown
        v-model="form.country_file_status_id"
        :items="fileStatuses"
      />
       <InputError :message="basicErrors?.business_client_id || form.errors.business_client_id"/>
    </div>
  -->

    <!-- Reference -->
    <!-- <div>
      <InputLabel :value="translations?.businessFiles?.client" />
      <TextInput
        type="text"
        v-model="form.reference_number"
        :placeholder="translations?.businessFiles?.client"
      />
       <InputError :message="basicErrors?.business_client_id || form.errors.business_client_id"/>
    </div> -->

    <!-- Upload file (ONLY create) -->
    <div>
      
       <FileUpload v-model="form.file" />
      <InputError :message="basicErrors?.file || form.errors.file"/>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 pt-4">
      <Button
        type="submit"
        btn-type="isBrand"
        :text="isEdit ? translations?.general?.edit : translations?.general?.save"
        :disabled="form.processing"
        :loading="form.processing"
      />
    </div>

  </form>
</template>



<script setup>
    import { ref, computed, watch , defineEmits  } from 'vue';
    import { useForm, usePage,router } from '@inertiajs/vue3'; 
    import InputLabel from '@/Components/Forms/InputLabel.vue'
    import InputDropdown from '@/Components/Forms/InputDropdown.vue'
    import TextInput from '@/Components/Forms/TextInput.vue'
    import InputError from '@/Components/Forms/InputError.vue'
    import Button from '@/Components/Forms/Button.vue'
    import FileUpload from './FileUpload.vue';

    const props = defineProps({
        business: Object,
        businessClients: Array,
        fileCategories: Array,
        fileStatuses: Array,
        fileTypes: Array,
        fileData:Object, 
        isEdit: {
          type: Boolean,
          default: false,
        },
    });

    const emit = defineEmits(['close-file-modal']);
        
    const page = usePage();
    const translations = computed(() => page.props.translations || {});
 
    const initialFileData =  props.fileData || {};
 
    const form = useForm({ 
        id: initialFileData.id || '', 
        business_client_id: initialFileData.business_client_id || '',        
        country_file_category_id: initialFileData.country_file_category_id ?? null,
        country_file_type_id: initialFileData.country_file_type_id ?? null,
        country_file_status_id: initialFileData.country_file_status_id ?? null,
        document_date: initialFileData.document_date ?? null,
        reference_number: initialFileData.reference_number ?? '',
        file_url: initialFileData.file_url ?? '',
        file: initialFileData?.name ?? null,
    }); 
    //file type show only matches with category
    const filteredFileTypes = computed(() => {
      if (!form.country_file_category_id) return []

      return props.fileTypes.filter(type =>
        type.category=== form.country_file_category_id
      )
    })
    const showFileType = computed(() => {
      return filteredFileTypes.value.length > 0
    })
    
    watch(
      () => form.country_file_category_id,
      (newCategory, oldCategory) => {
        if (newCategory !== oldCategory) {
          form.country_file_type_id = null
        }
      }
    )

    function submit() {
      if (!validate()) return
      form.processing = true;
      const options = {
          forceFormData: true,

          onSuccess: () => { 
              emit('close-file-modal')
              form.reset()
          },

          onError: (errors) => {
              console.error('Form errors:', errors) 
          }
      }

      if (props.isEdit) {
        console.log( route('business.files.update', {
                  business: props.business.slug,
                  businessFile: form.id,
              }));
              console.log(form);
          form.post(
              route('business.files.update', {
                  business: props.business.slug,
                  businessFile: form.id,
              }),
              options
          )
      } else {
          form.post(
              route('business.files.store', {
                  business: props.business.slug,
                  businessClient: form.business_client_id
              }),
              options
          )
      }
      setTimeout(() => {
         form.processing = false;
      }, 20000);
    }


    const basicErrors = ref({})
    const validate = () => {
        basicErrors.value = {}
        let valid = true;
        if (!form.business_client_id) {
            basicErrors.value.business_client_id = translations.value?.businessFiles?.clientRequired
            valid = false;
        }

        if (!form.country_file_category_id) {
            basicErrors.value.country_file_category_id = translations.value?.businessFiles?.fileCategoryRequired
            valid = false;
        }
       if (showFileType.value  && !form.country_file_type_id) {
            basicErrors.value.country_file_type_id = translations.value?.businessFiles?.fileTypeRequired
            valid = false;
        }
        if ( !form.file) {
            basicErrors.value.file = translations.value?.businessFiles?.fileRequired
            valid = false;
        }

        return valid;
    }



</script>