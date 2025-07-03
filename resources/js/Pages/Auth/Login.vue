<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import { ref } from 'vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const feedback = ref(null);
const feedbackType = ref('error');

const submit = () => {
    feedback.value = null;
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onError: (errors) => {
            feedback.value = errors.login || errors.password || 'Nieprawidłowy login lub hasło.';
            feedbackType.value = 'error';
        },
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div v-if="feedback" :class="[feedbackType === 'success' ? 'bg-green-500' : 'bg-red-500', 'text-white p-4 mb-4 rounded text-center']">
            {{ feedback }}
        </div>

        <form @submit.prevent="submit" class="login-form p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8 text-white">Każde wejście głębiej wymaga nowego oddechu</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-8">
                <div>
                    <InputLabel for="login" value="Login lub e-mail" class="text-white" />
                    <TextInput
                        id="login"
                        v-model="form.login"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.login" />
                </div>
                <div>
                    <InputLabel for="password" value="Hasło" class="text-white" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
            </div>
            

            

            <div class="block mt-5 pt-5">
                <label class="flex items-center text-white">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm">Zapamiętaj mnie</span>
                </label>
            </div>

            <div class="flex flex-col items-center mt-8">
                <PrimaryButton class=" py-3 text-lg bg-white text-black font-bold flex items-center justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <svg v-if="form.processing" class="animate-spin mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span>Zaloguj się</span>
                </PrimaryButton>
                <Link v-if="canResetPassword" :href="route('password.request')" class="mt-4 underline text-sm text-gray-300 hover:text-white">
                    Nie pamiętasz hasła?
                </Link>
                <Link :href="route('register')" class="mt-4 underline text-sm text-gray-300 hover:text-white">
                    Nie masz konta? Zarejestruj się
                </Link>
            </div>
        </form>
    </AuthenticationCard>
</template>
