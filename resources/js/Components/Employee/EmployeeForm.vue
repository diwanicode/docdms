<template>
  <form @submit.prevent="submit" class="space-y-5">
    <!-- Employee name -->
    <div>
      <InputLabel :value="translations?.businessEmployees?.name" />
      <TextInput
        type="text"
        v-model="form.name"
        :placeholder="translations?.businessEmployees?.name"
      />
       <InputError :message="basicErrors?.name || form.errors.name"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessEmployees?.email" />
      <TextInput
        type="text"
        v-model="form.email"
        :placeholder="translations?.businessEmployees?.enterEmail"
      />
       <InputError :message="basicErrors?.email || form.errors.email"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessEmployees?.number" />
      <TextInput
        type="text"
        v-model="form.phone_number"
        :placeholder="translations?.businessEmployees?.enterNumber"
      />
       <InputError :message="basicErrors?.number || form.errors.number"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessEmployees?.startDate" />
      <InputDate v-model="form.start_date" />
      <InputError :message="basicErrors?.number || form.errors.number"/>
    </div>

    <!-- Departments -->
    <div>
      <InputLabel :value="translations?.businessEmployees?.departments" />
      <DropdownMultiSelect
        v-model="form.departments"
        :items="businessDepartments" 
        :placeholder="translations?.businessEmployees?.selectDepartments"
      />
   </div>
  <!-- Deactivate client (permission-based) -->
    <div
      v-if="can('employees.delete')"
      class="border-t border-baseColor-300 py-6 my-6 space-y-4"
    >
      <h4 class="text-sm font-semibold text-brandColor-900">
        {{ translations?.businessEmployees?.activeStatus }}
      </h4>
 
      <div class="flex items-start gap-2 mt-4">
        <ButtonToggle v-model="form.is_active" color="brand" />
        <div class="flex flex-col">
            <span class="text-sm text-baseColor-700 font-medium">
            {{ translations?.businessEmployees?.activeQ }}
            </span>
            <p class="text-xs text-baseColor-500 italic">
            {{ translations?.businessEmployees?.activeDesc }}
            </p>
        </div>
    </div>

      <!-- End date shown ONLY when inactive -->
      <div v-if="!form.is_active">
        <InputLabel :value="translations?.businessEmployees?.endDate" />
        <InputDate v-model="form.end_date" />
        <InputError :message="form.errors.end_date" />
      </div>
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
     import InputDate from '@/Components/Forms/InputDate.vue'; 
    import TextInput from '@/Components/Forms/TextInput.vue'
    import DropdownMultiSelect from '@/Components/Forms/InputSelectMulti.vue';
    import InputError from '@/Components/Forms/InputError.vue'
    import Button from '@/Components/Forms/Button.vue' 
    import ButtonToggle from "@/Components/Forms/ButtonToggle.vue";
    import { usePermissions } from '@/Composables/usePermissions'

    const props = defineProps({
        business: Object,
        selectedEmployee:Object, 
        businessDepartments:{
            type: Array,
            default: () => []
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close-file-modal']);
    const { can } = usePermissions() 
    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const initialData =  props.selectedEmployee || {};
 
    const form = useForm({ 
        id: initialData.id || '', 
        name: initialData.name || '',      
        email: initialData.email || '',    
        phone_number: initialData.number || '',      
        start_date: initialData.start_date || '',     
        end_date: initialData.end_date || '',        
        departments: initialData.departments ?? [] ,
        is_active: initialData.active ?? true,
        is_owner: initialData.owner ?? false,
        is_working: initialData.employee ?? true
    }); 
    watch(
      () => form.is_active,
      (active) => {
        if (active) {
          form.end_date = null
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
          form.post(
              route('business.employees.update', {
                  business: props.business.slug,
                  businessEmployee: form.id,
              }),
              options
          )
      } else {
          form.post(
              route('business.employees.store', {
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
            basicErrors.value.name = translations.value?.businessEmployees?.nameRequired
            valid = false;
        }

     
        return valid;
    }
</script>