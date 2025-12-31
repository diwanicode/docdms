<template>
  <form @submit.prevent="submit" class="space-y-5">
    <!-- Employee name -->
    <div>
      <InputLabel :value="translations?.businessClients?.name" />
      <TextInput
        type="text"
        v-model="form.name"
        :placeholder="translations?.businessClients?.name"
      />
       <InputError :message="basicErrors?.name || form.errors.name"/>
    </div>
     <div>
      <InputLabel :value="translations?.businessClients?.type" />
      <InputDropdown
        v-model="form.business_type_id"
        :items="businessTypes" 
        :placeholder="translations?.businessClients?.type"
      />
      <InputError :message="basicErrors?.business_type_id || form.errors.business_type_id"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessClients?.vatNumber" />
      <TextInput
        type="text"
        v-model="form.vat_number"
        :placeholder="translations?.businessClients?.vatNumber"
      />
      <InputError :message="basicErrors?.vatNumber || form.errors.vatNumber"/>
    </div>
     <div>
      <InputLabel :value="translations?.businessClients?.contact" />
      <TextInput
        type="text"
        v-model="form.contact"
        :placeholder="translations?.businessClients?.contact"
      />
       <InputError :message="basicErrors?.contact || form.errors.contact"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessClients?.email" />
      <TextInput
        type="text"
        v-model="form.email"
        :placeholder="translations?.businessClients?.enterEmail"
      />
       <InputError :message="basicErrors?.email || form.errors.email"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessClients?.number" />
      <TextInput
        type="text"
        v-model="form.number"
        :placeholder="translations?.businessClients?.enterNumber"
      />
       <InputError :message="basicErrors?.number || form.errors.number"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessClients?.startDate" />
      <InputDate v-model="form.start_date" />
      <InputError :message="basicErrors?.number || form.errors.number"/>
    </div>
    <div>
      <InputLabel :value="translations?.businessClients?.address" />
      <TextInput
        type="text"
        v-model="form.address"
        :placeholder="translations?.businessClients?.address"
      />
       <InputError :message="basicErrors?.address || form.errors.address"/>
    </div>
    <div class="pb-6">
      <InputLabel :value="translations?.businessClients?.city" />
      <TextInput
        type="text"
        v-model="form.city"
        :placeholder="translations?.businessClients?.city"
      />
       <InputError :message="basicErrors?.city || form.errors.city"/>
    </div>
 
    <!-- Deactivate client (permission-based) -->
    <div
      v-if="can('clients.delete')"
      class="border-t border-baseColor-300 py-6 my-6 space-y-4"
    >
      <h4 class="text-sm font-semibold text-brandColor-900">
        {{ translations?.businessClients?.activeStatus }}
      </h4>
 
      <div class="flex items-start gap-2 mt-4">
        <ButtonToggle v-model="form.is_active" color="brand" />
        <div class="flex flex-col">
            <span class="text-sm text-baseColor-700 font-medium">
            {{ translations?.businessClients?.activeQ }}
            </span>
            <p class="text-xs text-baseColor-500 italic">
            {{ translations?.businessClients?.activeDesc }}
            </p>
        </div>
    </div>

      <!-- End date shown ONLY when inactive -->
      <div v-if="!form.is_active">
        <InputLabel :value="translations?.businessClients?.endDate" />
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
    import InputDate from '@/Components/Forms/InputDate.vue'; 
    import TextInput from '@/Components/Forms/TextInput.vue' 
    import InputError from '@/Components/Forms/InputError.vue'
    import Button from '@/Components/Forms/Button.vue' 
    import InputDropdown from '@/Components/Forms/InputDropdown.vue'
    import ButtonToggle from "@/Components/Forms/ButtonToggle.vue";
    import { usePermissions } from '@/Composables/usePermissions'

    const props = defineProps({
        business: Object,
        selectedClient:Object,  
        businessTypes: Array,
        isEdit: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close-client-modal']);
        
    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const initialData =  props.selectedClient || {};
    const { can } = usePermissions()
    const form = useForm({ 
        id: initialData.id || '', 
        name: initialData.name || '',
        business_type_id: initialData.business_type_id || '',
        contact: initialData.contact || '',  
        vat_number: initialData.vat_number || '',  
        email: initialData.email || '',    
        number: initialData.number || '',      
        start_date: initialData.start_date || '',    
        end_date: initialData.end_date || '',         
        is_active: initialData.active ?? true,
        city: initialData.city ?? '',
        address: initialData.address ?? '',
        note: initialData.note ?? ''
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
              emit('close-client-modal')
              form.reset()
          },

          onError: (errors) => {
              console.error('Form errors:', errors) 
          }
      }

      if (props.isEdit) {
          form.post(
              route('business.clients.update', {
                  business: props.business.slug,
                  businessClient: form.id,
              }),
              options
          )
      } else {
          form.post(
              route('business.clients.store', {
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
            basicErrors.value.name = translations.value?.businessClients?.nameRequired
            valid = false;
        }

     
        return valid;
    }
</script>