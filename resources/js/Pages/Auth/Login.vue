<template>
    <GuestLayout>
        <Head :title="translations?.welcome?.logIn" />

        <form @submit.prevent="submit" class="px-12">
            <div>
                <InputLabel for="email" :value="translations?.welcome?.email"  />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    :placeholder="translations?.welcome?.emailPlaceholder" 
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="translations?.welcome?.password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>


            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between py-4">
                <label class="flex items-center">
                  <Checkbox 
                    name="remember" 
                    v-model:checked="form.remember"
                    class="rounded border-baseColor-300 text-brandColor-600 focus:ring-brandColor-500"
                  />
                  <span class="ml-2 text-sm text-baseColor-600">{{ translations?.welcome?.rememberMe }}</span>
                </label>

                <Link
                  v-if="canResetPassword"
                  :href="route('password.request')"
                  class="text-sm font-medium text-brandColor-600 hover:text-brandColor-700 focus:outline-none focus:underline transition-colors"
                >
                  {{ translations?.welcome?.passwordForgot }}
                </Link>
            </div>

              <!-- Login Button -->
            <div>
            <Button 
                type="submit" 
                btn-type="isBrand"
                :text="translations?.welcome?.logIn" 
                :class="[
                'w-full flex',
                { 'opacity-50 cursor-not-allowed': form.processing }
                ]"
                :disabled="form.processing"
            />
            </div> 
        </form>
    </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Forms/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/Forms/InputError.vue';
import InputLabel from '@/Components/Forms/InputLabel.vue';
import Button from '@/Components/Forms/Button.vue'; 
import TextInput from '@/Components/Forms/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref,computed,onMounted } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

    const page = usePage();
    const translations = computed(() => page.props.translations || {});
    const flashMsg = computed(() => page.props.flash);
    //loading message and function
    const loading =ref(false);
    const loadingMsg =ref(null);
    const setLoadingMsg = (type, msg = '') => {
        loading.value = type
        loadingMsg.value = msg
    }
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

