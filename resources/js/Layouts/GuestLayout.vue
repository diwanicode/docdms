<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Card from '@/Components/Core/Card.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <Card
            card-type="isBrand100"
            class="w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-3xl p-6 sm:p-8 md:p-10 flex flex-col items-center"
        >
            <!-- Logo on top -->
            <div class="mb-6 text-center">
            <Link href="/">
                <ApplicationLogo heightClass="h-12 sm:h-16" />
            </Link>
            </div>
    
            <div class="w-full">
            <slot />
            </div>
        </Card>
    </div>

</template>
