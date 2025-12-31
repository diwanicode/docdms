<template>
  <form @submit.prevent="submit" class="space-y-5">
    <!-- Department name -->
    <div>
      <InputLabel :value="translations?.businessDepartments?.name" />
      <TextInput
        type="text"
        v-model="form.name"
        :placeholder="translations?.businessDepartments?.name"
      />
       <InputError :message="basicErrors?.name || form.errors.name"/>
    </div>
 

    <!-- Employee -->
    <div>
      <InputLabel :value="translations?.businessDepartments?.employee" />
      <DropdownMultiSelect
        v-model="form.employees"
        :items="businessEmployees" 
        :placeholder="translations?.businessDepartments?.selectEmployees"
      />
   </div>

    <!-- Category -->
    <div>
      <InputLabel :value="translations?.businessDepartments?.permissions" />
      <DropdownMultiSelect
        v-model="form.permissions"
        :items="permissons"
        :placeholder="translations?.businessDepartments?.selectPermissions"
      />
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
    import DropdownMultiSelect from '@/Components/Forms/InputSelectMulti.vue';
    import InputError from '@/Components/Forms/InputError.vue'
    import Button from '@/Components/Forms/Button.vue' 

    const props = defineProps({
        business: Object,
        departmentData:Object,
        permissons: Array,
        businessEmployees:{
            type: Array,
            default: () => []
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close-department-modal']);
        
    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const initialData =  props.departmentData || {};
 
    const form = useForm({ 
        id: initialData.id || '', 
        name: initialData.name || '',      
        description: initialData.description || '',        
        employees: initialData.employees ?? [],
        permissions: initialData.permissions ?? [] 
    }); 
    function submit() {
      if (!validate()) return
      form.processing = true;
      const options = {
          forceFormData: true,

          onSuccess: () => { 
              emit('close-department-modal')
              form.reset()
          },

          onError: (errors) => {
              console.error('Form errors:', errors) 
          }
      }

      if (props.isEdit) {
          form.post(
              route('business.departments.update', {
                  business: props.business.slug,
                  businessDepartment: form.id,
              }),
              options
          )
      } else {
          form.post(
              route('business.departments.store', {
                  business: props.business.slug
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
        if (!form.name) {
            basicErrors.value.name = translations.value?.businessDepartments?.nameRequired
            valid = false;
        }

     
        return valid;
    }
</script>