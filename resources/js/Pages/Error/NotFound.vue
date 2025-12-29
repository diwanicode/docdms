<template>
  <Head title="Page Not Found" />    
  <GuestLayout> 
    <div class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto space-y-4 sm:space-y-6 text-center px-4 sm:px-6 lg:px-8">
      
      <!-- Image -->
      <img
        src="/images/notFound.png"
        alt="404 Error"
        class="w-full max-w-[160px] sm:max-w-[200px] md:max-w-[240px] mx-auto"
      />

      <!-- Title -->
      <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-brandColor-800 leading-snug">
        {{ translations?.errorPreview?.notFound ?? 'Oops! Looks like you’re lost…' }}
      </h1>

      <!-- Note -->
      <p class="text-sm sm:text-base text-baseColor-600 max-w-prose mx-auto">
        {{ translations?.errorPreview?.notFoundNote ?? 'Sorry, we couldn’t find the page you’re looking for.' }}
      </p>

      <!-- Buttons -->
      <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center"> 
        <Button v-if="isAuthenticated"
          btn-type="isBrand"
          :title="translations?.welcome?.logIn"
          :text="translations?.welcome?.logIn ?? 'Log In'"
          @click="logIn()" 
        />         

        <Button
          @click="goBack()"
          :text="translations?.general?.goBack ?? 'Go Back'" 
          btn-icon="ArrowUturnLeftIcon"
          btn-type="isBrandSecondary"
        />
      </div>
    </div> 
  </GuestLayout> 
</template>

<script setup>
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Forms/Button.vue';

const props = defineProps({
  status: Number 
});

const page = usePage();
const translations = computed(() => page.props.translations || {});
const isAuthenticated = computed(() => !!page.props.auth?.user);

const logIn = () => {
  router.get(route('login'));
};

const goBack = () => {
  window.history.back();
};
</script>
