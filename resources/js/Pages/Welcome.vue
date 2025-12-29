<script setup :nonce="$page.props.cspNonce">
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen flex flex-col items-center justify-center bg-brandColor-50 dark:bg-baseColor-900 text-black/70 dark:text-brandColor-50 px-4">
        <div class="text-center max-w-lg">
            <h1 class="text-5xl font-bold mb-4">Welcome to docDMS</h1>
            <p class="text-lg mb-6">Our platform is under construction. Exciting features are coming soon!</p>
        </div>
    </div>
</template>
