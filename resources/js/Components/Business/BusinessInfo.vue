<template>
  <Card :card-type="show === 'simple' ? 'isSimple' : 'isBrand100'" class="overflow-hidden relative">
    <!-- Cover Image -->
    <div v-if="show==='full'" class="relative w-full h-48 md:h-56 bg-baseColor-300 rounded-2xl">
      <img 
        v-if="businessDetails_check.cover_image" 
        :src="businessDetails_check.cover_image" 
        alt="Cover Image" 
        class="w-full h-full rounded-md object-cover"
      />
    </div>
    <!-- Profile Section -->
    <div class="relative flex flex-col lg:flex-row items-center px-4 lg:px-6 -top-4 space-y-4 lg:space-y-0 lg:space-x-6 text-center lg:text-left">
      <!-- Profile Picture -->
      <BusinessLogo  :business="businessDetails"/>

      <!-- Business Info --> 
      <div class="flex-1 pt-4">
        <h2 class="text-xl lg:text-2xl font-bold text-brandColor-800 mt-2 lg:mt-0">{{ businessDetails.name }}</h2>
        <p class="text-sm   text-brandColor-800 mt-2 lg:mt-0">{{ businessDetails.description }}</p>
        <div class="space-y-1">
          <p 
            v-for="(location, index) in businessDetails.locations" 
            :key="index" 
            class="text-sm text-baseColor-800"
          >
            {{ location.address }}, {{ location.city }}
          </p>
        </div>
      </div>
   
      <!-- Social Links --> 
         <div v-if="businessDetails.social_media?.length && show==='full'" class="flex space-x-4 mt-2">
                <a
                v-for="social in businessDetails.social_media"
                :key="social.name"
                :href="social.url"
                target="_blank"
                class="p-2 rounded-full  transition bg-transparent text-brandColor-600 hover:-translate-y-0.5  hover:bg-brandColor-100  hover:shadow-md"
                :title="social.name"
                >
                 <img :src="`/images/ui_social/`+social.icon" :alt="social.name" class="w-6 h-6 flex-shrink-0">
                
                </a>
            </div>
    </div>


    <!-- Business Types with Blur Effect (Centered) -->
    <div v-if="show==='full'" class="flex justify-center flex-wrap space-x-2">
      <Badge  v-for="(type, index) in businessDetails.business_types"
          :key="index"
          :title="type.name"
          color="brandSecondary" 
        />
      
    </div>
     <div class="flex  justify-center sm:justify-end  mb-4 mt-2">
        <!-- <Button btn-type="isBrandSecondary" 
                btn-icon="PencilSquareIcon"
                :title="translations?.general?.edit"
                :text="translations?.general?.edit" 
                @click="editBusiness()" /> -->
    </div> 
  </Card> 
</template>

<script setup :nonce="$page.props.cspNonce">
  import { reactive,computed } from 'vue'
  import {router, usePage } from '@inertiajs/vue3'; 
  import Card from '@/Components/Core/Card.vue'
  import Badge from '@/Components/Forms/Badge.vue'
  import BusinessLogo from '@/Components/Business/BusinessLogo.vue'
  import Button from '@/Components/Forms/Button.vue'
  import ButtonToggle from '@/Components/Forms/ButtonToggle.vue';
 
  const props = defineProps({
    show: {
      type: String,
      default: 'full',
      validator: (value) => ['full', 'simple'].includes(value),
    },
    businessDetails: {
      type: Object,
      required: true,
    },
  })
  const page = usePage();
  const translations = computed(() => page.props.translations || {});
  const lang = computed(() => page.props.language || 'en');

  const businessDetails_check = reactive({
    cover_image: '/images/bg_5.png',
  })
  const viewBusiness =()=>{
    const url = route('customer.view.business', {
      lang: lang.value,
      business: props.businessDetails.slug
    });

    window.open(url, '_blank');
  }
  const editBusiness = () => {    
      router.visit(route('business.edit', {
          business: props.businessDetails.slug 
      }));
  }; 
</script>

